<?php

namespace app\modules\api\v1\controllers;

use Yii;
use yii\web\Controller;
use app\modules\api\v1\exceptions\ApiException;
use app\modules\api\v1\models\Authentification;
use app\modules\api\v1\models\UserApi;
use app\models\User;
use app\models\Order;
use app\models\Service;
use app\models\OrderService;

class OrderserviceController extends Controller {

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
                'Access-Control-Request-Method' => ['GET', 'POST', 'PUT', 'PATCH', 'DELETE'],
                'Access-Control-Allow-Credentials' => true,
                'Access-Control-Max-Age' => 7200,
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
        Authentification::verifyByType($this->user, User::TYPE_COMPANY);

        if (Yii::$app->request->method == 'GET') {
            $this->get();
        } elseif (Yii::$app->request->method == 'POST') {
            $this->post();
        } elseif (Yii::$app->request->method == 'DELETE') {
            $this->delete();
        } else {
            ApiException::set(400);
        }

        return $this->result;
    }

    private function get() {
        $data = Yii::$app->request->get();

        if (empty($data['order_id'])) {
            ApiException::set(400);
        }
        
        $order_service = OrderService::find()
                ->with('service.category')
                ->where(['order_id' => $data['order_id']])
                ->joinWith([
                    'order.schedule.specialist' => function($query) {
                        $query->where([
                            'specialist.company_id' => $this->user->id,
                        ]);
                    },
                ])
                ->asArray()
                ->all();
        foreach ($order_service as $key => $one_order_service) {
            unset($order_service[$key]['order']);
        }

        $this->result = $order_service;
    }

    private function post() {
        $data = Yii::$app->request->post();
        $order_id = Yii::$app->request->get()['order_id'];
        
        if (!empty($order_id) && !empty($data['services']) && count($data['services'])) {
            foreach ($data['services'] as $service_id) {
                $order_service = OrderService::find()
                        ->where([
                            'order_id' => $order_id,
                            'service_id' => $service_id,
                        ])
                        ->one();
                if (!$order_service instanceof OrderService) {
                    $service = Service::find()
                            ->where([
                                'id' => $service_id,
                                'company_id' => $this->user->id,
                            ])
                            ->one();
                    if (!$service instanceof Service) {
                        ApiException::set(400);
                    }

                    $order_service = new OrderService();
                    $order_service->order_id = $order_id;
                    $order_service->service_id = $service_id;
                    if (!$order_service->save()) {
                        ApiException::set(400);
                    }
                }
            }
            Yii::$app->response->headers->set('Location', '/api/v1/orderservice?order_id=' . $order_id);
            ApiException::set(201);
        } else {
            ApiException::set(400);
        }
    }

    private function delete() {
        if ($this->row_id > 0) {
            $order_service = OrderService::find()
                    ->where(['order_vs_service.id' => $this->row_id])
                    ->joinWith([
                        'order.schedule.specialist' => function($query) {
                            $query->where([
                                'specialist.company_id' => $this->user->id,
                            ]);
                        },
                    ])
                    ->one();
            if ($order_service instanceof OrderService) {
                if ($order_service->delete()) {
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
