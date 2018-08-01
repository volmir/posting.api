<?php

namespace app\modules\api\v1\controllers;

use Yii;
use yii\web\Controller;
use app\modules\api\v1\exceptions\ApiException;
use app\modules\api\v1\models\Authentification;
use app\models\Order;
use app\models\OrderStatus;
use app\models\OrderService;
use app\models\User;
use app\models\Client;
use app\models\Schedule;

class OrderController extends Controller {

    /**
     *
     * @var mixed
     */
    private $result;

    /**
     *
     * @var app\modules\api\v1\models\UserApi
     */
    protected $user;

    /**
     *
     * @var int
     */
    private $page_limit = 20;

    /**
     *
     * @var int
     */
    protected $row_id;

    public function init() {
        $this->enableCsrfValidation = false;
    }

    /**
     * @inheritdoc
     */
    public function behaviors() {
        $behaviors = parent::behaviors();
        $behaviors['corsFilter'] = [
            'class' => \yii\filters\Cors::className(),
            'cors' => [
                'Origin' => ['*'],
                'Access-Control-Request-Method' => ['GET', 'POST', 'PUT', 'PATCH', 'DELETE', 'HEAD', 'OPTIONS'],
                'Access-Control-Allow-Credentials' => true,
                'Access-Control-Max-Age' => 7200,
                'Access-Control-Allow-Headers' => ['Origin', 'X-Requested-With', 'Content-Type', 'Accept', 'Authorization'],
                'Access-Control-Allow-Origin' => ['*'],
            ],
        ];

        return $behaviors;
    }

    /**
     * 
     * @return stdClass
     */
    public function actionIndex($id = 0) {
        $this->row_id = $id;
        $this->user = Authentification::verify();

        if (Yii::$app->request->method == 'GET') {
            $this->getOrders();
        } elseif (Yii::$app->request->method == 'POST') {
            $this->addOrder();
        } elseif (Yii::$app->request->method == 'PATCH') {
            $this->patchOrder();
        } elseif (Yii::$app->request->method == 'DELETE') {
            $this->deleteOrder();
        } else {
            ApiException::set(405);
        }

        return $this->result;
    }

    private function getOrders() {
        $where = [];

        $orders = Order::find()
                ->with('orderServices', 'schedule');

        if ($this->row_id) {
            $where['order.id'] = $this->row_id;
        }

        if ($this->user->type == User::TYPE_CLIENT) {
            $where['client_id'] = $this->user->id;
        } elseif ($this->user->type == User::TYPE_SPECIALIST) {
            $orders = $orders->joinWith([
                'schedule' => function ($query) {
                    $query->where([
                        'specialist_id' => $this->user->id,
                    ]);
                },
            ]);
        } elseif ($this->user->type == User::TYPE_COMPANY) {
            $orders = $orders->joinWith([
                'schedule.specialist' => function ($query) {
                    $query->where([
                        'specialist.company_id' => $this->user->id,
                    ]);
                },
            ]);
        } else {
            ApiException::set(400);
        }

        if ($where) {
            $orders = $orders->where($where);
        }

        $orders = $this->getOrdersWhere($orders);

        if ($this->row_id) {
            $orders = $orders->asArray()->one();
        } else {
            $orders = $orders->limit($this->page_limit)
                    ->orderBy(['id' => SORT_DESC])
                    ->asArray()
                    ->all();
        }

        $this->result = $orders;
    }

    private function getOrdersWhere($orders) {
        $data = Yii::$app->request->get();

        if (!empty($data['client_id'])) {
            $orders = $orders->andWhere(['=', 'client_id', $data['client_id']]);
        }
        if (!empty($data['specialist_id'])) {
            $orders = $orders->andWhere(['=', 'schedule.specialist_id', $data['specialist_id']]);
        }
        if (!empty($data['date_from'])) {
            $orders = $orders->andWhere(['>=', 'schedule.date_from', $data['date_from']]);
        }
        if (!empty($data['date_to'])) {
            $orders = $orders->andWhere(['<=', 'schedule.date_to', $data['date_to']]);
        }
        if (!empty($data['status_client'])) {
            $orders = $orders->andWhere(['=', 'status_client', $data['status_client']]);
        }
        if (!empty($data['status_specialist'])) {
            $orders = $orders->andWhere(['=', 'status_specialist', $data['status_specialist']]);
        }

        return $orders;
    }

    private function addOrder() {
        $data = Yii::$app->request->post();
        
        if (empty($data['schedule_id'])) {
            ApiException::set(400);
        }

        $order = new Order();
        $order->client_id = (!empty($data['client_id']) ? $data['client_id'] : Client::VIRTUAL_CLIENT);
        $order->schedule_id = $data['schedule_id'];
        $order->status_client = (!empty($data['status_client']) ? $data['status_client'] : OrderStatus::ADDED);
        $order->status_specialist = (!empty($data['status_specialist']) ? $data['status_specialist'] : OrderStatus::ADDED);
        try {
            if ($order->save()) {
                if (!empty($data['services'])) {
                    $this->addOrderService($data['services'], $order);
                }
                Yii::$app->response->headers->set('Location', '/api/v1/order/' . $order->getPrimaryKey());
                ApiException::set(201);
            } else {
                ApiException::set(400);
            }
        } catch (\RuntimeException $e) {
            ApiException::set(400);
        }
    }

    private function addOrderService($services, $order) {
        $company_id = $order->schedule->specialist->company_id;
        if (count($services)) {
            foreach ($services as $service_id) {
                $order_service = new OrderService();
                $order_service->order_id = $order->id;
                $order_service->service_id = $service_id;
                $order_service->save();
            }
        }
    }

    /**
     * @return stdClass
     */
    public function actionStatus() {
        $this->user = Authentification::verify();

        if (Yii::$app->request->method == 'GET') {
            $order_status = OrderStatus::find()->all();

            $this->result = $order_status;
        } else {
            ApiException::set(400);
        }

        return $this->result;
    }

    private function patchOrder() {
        $data = Yii::$app->request->post();
        if ($this->row_id > 0 && (!empty($data['status_client']) || !empty($data['status_specialist']))) {

            $order = Order::find();

            if ($this->row_id) {
                $where['order.id'] = $this->row_id;
            }

            if ($this->user->type == User::TYPE_CLIENT) {
                $where['client_id'] = $this->user->id;
            } elseif ($this->user->type == User::TYPE_SPECIALIST) {
                $order = $order->joinWith([
                    'schedule' => function ($query) {
                        $query->where([
                            'specialist_id' => $this->user->id,
                        ]);
                    },
                ]);
            } elseif ($this->user->type == User::TYPE_COMPANY) {
                $order = $order->joinWith([
                    'schedule.specialist' => function ($query) {
                        $query->where([
                            'specialist.company_id' => $this->user->id,
                        ]);
                    },
                ]);
            } else {
                ApiException::set(400);
            }

            if ($where) {
                $order = $order->where($where);
            }

            $order = $order->one();

            if ($order instanceof Order) {
                if (!empty($data['status_client'])) {
                    $order->status_client = $data['status_client'];
                }
                if (!empty($data['status_specialist'])) {
                    $order->status_specialist = $data['status_specialist'];
                }

                if ($order->save()) {
                    ApiException::set(200);
                } else {
                    ApiException::set(400);
                }
            } else {
                ApiException::set(404);
            }
        } else {
            ApiException::set(400);
        }
    }

    private function deleteOrder() {
        if ($this->row_id > 0) {
            $order = Order::find()
                    ->where([
                'order.id' => $this->row_id,
            ]);

            if ($this->user->type == User::TYPE_SPECIALIST) {
                $order = $order->joinWith([
                    'schedule' => function ($query) {
                        $query->where([
                            'specialist_id' => $this->user->id,
                        ]);
                    },
                ]);
            } elseif ($this->user->type == User::TYPE_COMPANY) {
                $order = $order->joinWith([
                    'schedule.specialist' => function ($query) {
                        $query->where([
                            'specialist.company_id' => $this->user->id,
                        ]);
                    },
                ]);
            } else {
                ApiException::set(400);
            }

            $order = $order->one();

            if ($order instanceof Order) {
                $schedule = Schedule::findOne($order->schedule_id);
                if ($order->delete() && $schedule->delete()) {
                    ApiException::set(204);
                } else {
                    ApiException::set(400);
                }
            } else {
                ApiException::set(404);
            }
        } else {
            ApiException::set(400);
        }
    }

}
