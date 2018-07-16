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
     * @return stdClass
     */
    public function actionIndex() {
        $this->user = Authentification::verify();

        if (Yii::$app->request->method == 'GET') {
            $this->getOrders();
        } else {
            ApiException::set(400);
        }

        return $this->result;
    }

    private function getOrders() {
        $orders = Order::find()
                ->with('orderServices', 'schedule');
        
        if ($this->user->type == User::TYPE_CLIENT) {
            $orders = $orders->where([
                    'client_id' => $this->user->id,
                ]);
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
        
        $orders = $orders->limit($this->page_limit)
                ->orderBy(['id' => SORT_DESC])
                ->asArray()
                ->all();
        
        $this->result = $orders;
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

}
