<?php

namespace app\modules\api\v1\controllers;

use Yii;
use yii\web\Controller;
use app\modules\api\v1\exceptions\ApiException;
use app\modules\api\v1\models\Authentification;
use app\modules\api\v1\models\UserApi;
use app\models\User;
use app\models\Company;
use app\models\Service;
use app\models\Promotion;

class PromotionController extends Controller {

    /**
     *
     * @var mixed
     */
    private $result;

    /**
     *
     * @var int
     */
    private $limit = 50;

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
            $this->get();
        } elseif (Yii::$app->request->method == 'POST') {
            $this->post();
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
        $pomotion = Promotion::find();
        if ($this->row_id) {
            $pomotion = $pomotion->andWhere(['=', 'id', $this->row_id]);
        }
        $pomotion = $pomotion
                ->orderBy(['id' => SORT_DESC])
                ->limit($this->limit)
                ->asArray()
                ->all();

        $this->result = $pomotion;
    }

    private function post() {
        Authentification::verifyByType($this->user, UserApi::TYPE_COMPANY);

        $data = Yii::$app->request->post();
        if (!empty($data['title']) && !empty($data['description']) && !empty($data['date_start']) && !empty($data['date_end']) && strtotime($data['date_start']) < strtotime($data['date_end'])) {
            if (!empty($data['service_id'])) {
                $service = Service::find()
                        ->where([
                            'id' => $data['service_id'],
                            'company_id' => $this->user->id,
                        ])
                        ->one();
                if (!$service instanceof Service) {
                    ApiException::set(400);
                }
            }

            $pomotion = new Promotion();
            $pomotion->company_id = $this->user->id;
            $pomotion->service_id = (!empty($data['service_id']) ? $data['service_id'] : '');
            $pomotion->title = $data['title'];
            $pomotion->description = $data['description'];
            $pomotion->price = (!empty($data['price']) ? $data['price'] : '');
            $pomotion->currency_id = (!empty($data['currency_id']) ? $data['currency_id'] : '');
            $pomotion->discount = (!empty($data['discount']) ? $data['discount'] : '');
            $pomotion->date_start = $data['date_start'];
            $pomotion->date_end = $data['date_end'];
            if ($pomotion->save()) {
                Yii::$app->response->headers->set('Location', '/api/v1/promotion/' . $pomotion->getPrimaryKey());
                ApiException::set(201);
            } else {
                ApiException::set(400);
            }
        } else {
            ApiException::set(400);
        }
    }

    private function patch() {
        Authentification::verifyByType($this->user, UserApi::TYPE_COMPANY);
        
        $data = Yii::$app->request->post();
        if ($this->row_id > 0) {
            $pomotion = Promotion::find()
                    ->where([
                'id' => $this->row_id,
                'company_id' => $this->user->id,
            ]);
            $pomotion = $pomotion
                    ->one();

            if ($pomotion instanceof Promotion) {
                if (!empty($data['service_id'])) {
                    $service = Service::find()
                            ->where([
                                'id' => $data['service_id'],
                                'company_id' => $this->user->id,
                            ])
                            ->one();
                    if (!$service instanceof Service) {
                        ApiException::set(400);
                    }
                }
                if (!empty($data['date_start']) && !empty($data['date_end']) && strtotime($data['date_start']) > strtotime($data['date_end'])) {
                    ApiException::set(400);
                }

                if (!empty($data['title'])) {
                    $pomotion->title = $data['title'];
                }
                if (!empty($data['description'])) {
                    $pomotion->description = $data['description'];
                }
                if (!empty($data['service_id'])) {
                    $pomotion->service_id = $data['service_id'];
                }
                if (!empty($data['price'])) {
                    $pomotion->price = $data['price'];
                }
                if (!empty($data['currency_id'])) {
                    $pomotion->currency_id = $data['currency_id'];
                }
                if (!empty($data['discount'])) {
                    $pomotion->discount = $data['discount'];
                }
                if (!empty($data['date_start'])) {
                    $pomotion->date_start = $data['date_start'];
                }
                if (!empty($data['date_end'])) {
                    $pomotion->date_end = $data['date_end'];
                }

                if ($pomotion->save()) {
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
        Authentification::verifyByType($this->user, UserApi::TYPE_COMPANY);

        if ($this->row_id > 0) {
            $pomotion = Promotion::find()
                    ->where([
                'id' => $this->row_id,
                'company_id' => $this->user->id,
            ]);
            $pomotion = $pomotion
                    ->one();

            if ($pomotion instanceof Promotion) {
                if ($pomotion->delete()) {
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
