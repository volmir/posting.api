<?php

namespace app\modules\api\v1\controllers;

use Yii;
use yii\web\Controller;
use app\modules\api\v1\exceptions\ApiException;
use app\modules\api\v1\models\Authentification;
use app\modules\api\v1\models\UserApi;
use app\models\Service;
use app\models\Currency;
use app\models\User;

class ServiceController extends Controller {

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
        Authentification::verifyByType($this->user, UserApi::TYPE_COMPANY);

        if (Yii::$app->request->method == 'GET') {
            $this->get();
        } elseif (Yii::$app->request->method == 'POST') {
            $this->post();
        } elseif (Yii::$app->request->method == 'PUT') {
            $this->put();
        } elseif (Yii::$app->request->method == 'PATCH') {
            $this->patch();            
        } elseif (Yii::$app->request->method == 'DELETE') {
            $this->delete();            
        } else {
            ApiException::set(405);
        }

        return $this->result;
    }

    private function get() {
        $service = Service::find()
                ->where(['company_id' => $this->user->id]);
        if ($this->row_id) {
            $service = $service->andWhere(['=', 'id', $this->row_id]);
        }
        $service = $service->all();

        $this->result = $service;
    }
    
    private function post() {
        $data = Yii::$app->request->post();
        if (isset($data['category_id']) && !empty($data['price'])) {
            $service = Service::find()
                    ->where([
                        'category_id' => $data['category_id'],
                        'company_id' => $this->user->id,
                    ])
                    ->one();
            if ($service instanceof Service) {
                ApiException::set(400);
            }

            $service = new Service();
            $service->category_id = $data['category_id'];
            $service->company_id = $this->user->id;
            $service->price = $data['price'];
            $service->currency_id = (!empty($data['currency_id']) ? $data['currency_id'] : Currency::UAH);
            if ($service->save()) {
                Yii::$app->response->headers->set('Location', '/api/v1/service/' . $service->getPrimaryKey());
                ApiException::set(201);
            } else {
                ApiException::set(400);
            }
        } else {
            ApiException::set(400);
        }
    }
    
    private function put() {
        $data = Yii::$app->request->post();
        if ($this->row_id > 0 && !empty($data['price']) && !empty($data['currency_id'])) {
            $service = Service::find()
                    ->where([
                        'id' => $this->row_id,
                        'company_id' => $this->user->id,
                        ])
                    ->one();
            if ($service instanceof Service) {
                $service->price = $data['price'];
                $service->currency_id = $data['currency_id'];
                if ($service->save()) {
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

    private function patch() {
        $data = Yii::$app->request->post();
        if ($this->row_id > 0 && (!empty($data['price']) || !empty($data['currency_id']))) {
            $service = Service::find()
                    ->where([
                        'id' => $this->row_id,
                        'company_id' => $this->user->id,
                        ])
                    ->one();
            if ($service instanceof Service) {
                if (!empty($data['price'])) {
                    $service->price = $data['price'];
                }
                if (!empty($data['currency_id'])) {
                    $service->currency_id = $data['currency_id'];
                }

                if ($service->save()) {
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
    
    private function delete() {
        if ($this->row_id > 0) {
            $service = Service::find()
                    ->where([
                        'id' => $this->row_id,
                        'company_id' => $this->user->id,
                    ])
                    ->one();
            if ($service instanceof Service) {
                if ($service->delete()) {
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
