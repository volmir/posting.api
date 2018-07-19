<?php

namespace app\modules\api\v1\controllers;

use Yii;
use yii\web\Controller;
use app\modules\api\v1\exceptions\ApiException;
use app\modules\api\v1\models\Authentification;
use app\modules\api\v1\models\UserApi;
use app\models\User;
use app\models\Specialist;
use app\models\Company;
use app\models\Schedule;
use yii\helpers\ArrayHelper;

class ScheduleController extends Controller {
    
    /**
     *
     * @var int
     */
    protected $row_id;
    
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
            ApiException::set(400);
        }

        return $this->result;
    }

    private function get() {
        $data = Yii::$app->request->get();
        
        if (Specialist::findOne($this->user->id) instanceof Specialist) {
            $schedule = Schedule::find()
                ->where(['specialist_id' => $this->user->id]);
        } elseif (Company::findOne($this->user->id) instanceof Company) {
            $specialists = Specialist::find()
                    ->select('id')
                    ->where(['company_id' => $this->user->id])
                    ->asArray()
                    ->all();
            $specialist_ids = ArrayHelper::getColumn($specialists, 'id');
            $schedule = Schedule::find()
                ->where(['IN', 'specialist_id', $specialist_ids]);
        } else {
            ApiException::set(400);
        }
        
        if (!empty($data['specialist_id'])) {
            $schedule = $schedule->andWhere(['=', 'specialist_id', $data['specialist_id']]);
        }
        if (!empty($data['date_from'])) {
            $schedule = $schedule->andWhere(['>=', 'date_from', $data['date_from']]);
        }
        if (!empty($data['date_to'])) {
            $schedule = $schedule->andWhere(['<=', 'date_to', $data['date_to']]);
        }
        $schedule = $schedule->limit(1000)->all();

        $this->result = $schedule;
    }

    private function post() {
        $data = Yii::$app->request->post();
        
        if ($this->user->id != $data['specialist_id'] && !UserApi::checkSpecialistCompany($data['specialist_id'], $this->user)) {
            ApiException::set(400);
        }
        
        if (!empty($data['specialist_id']) && !empty($data['date_from']) && !empty($data['date_to'])) {
            $schedule = Schedule::find()
                    ->where([
                        'specialist_id' => $data['specialist_id'],
                        'date_from' => $data['date_from'],
                        'date_to' => $data['date_to'],
                    ])
                    ->one();
            if ($schedule instanceof Schedule) {
                ApiException::set(400);
            }

            $schedule = new Schedule();
            $schedule->specialist_id = $data['specialist_id'];
            $schedule->date_from = $data['date_from'];
            $schedule->date_to = $data['date_to'];
            if ($schedule->save()) {
                Yii::$app->response->headers->set('Location', '/api/v1/schedule/' . $schedule->getPrimaryKey());
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
        if ($this->row_id > 0 && !empty($data['specialist_id']) && !empty($data['date_from']) && !empty($data['date_to'])) {
            $schedule = Schedule::find()
                    ->where(['id' => $this->row_id])
                    ->one();
            if ($schedule instanceof Schedule) {
                if ($this->user->id != $schedule->specialist_id && !UserApi::checkSpecialistCompany($schedule->specialist_id, $this->user)) {
                    ApiException::set(400);
                }
                if ($this->user->id != $data['specialist_id'] && !UserApi::checkSpecialistCompany($data['specialist_id'], $this->user)) {
                    ApiException::set(400);
                }

                $schedule->specialist_id = $data['specialist_id'];
                $schedule->date_from = $data['date_from'];
                $schedule->date_to = $data['date_to'];
                if ($schedule->save()) {
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
        if ($this->row_id > 0 && (!empty($data['specialist_id']) || !empty($data['date_from']) || !empty($data['date_to']))) {
            $schedule = Schedule::find()
                    ->where(['id' => $this->row_id])
                    ->one();
            if ($schedule instanceof Schedule) {
                if ($this->user->id != $schedule->specialist_id && !UserApi::checkSpecialistCompany($schedule->specialist_id, $this->user)) {
                    ApiException::set(400);
                }
                
                if (!empty($data['specialist_id'])) {
                    if ($this->user->id != $data['specialist_id'] && !UserApi::checkSpecialistCompany($data['specialist_id'], $this->user)) {
                        ApiException::set(400);
                    }
                    $schedule->specialist_id = $data['specialist_id'];
                }
                if (!empty($data['date_from'])) {
                    $schedule->date_from = $data['date_from'];
                }
                if (!empty($data['date_to'])) {
                    $schedule->date_to = $data['date_to'];
                }
                
                if ($schedule->save()) {
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
            $schedule = Schedule::find()
                    ->where(['id' => $this->row_id])
                    ->one();
            if ($schedule instanceof Schedule) {
                if ($this->user->id != $schedule->specialist_id && !UserApi::checkSpecialistCompany($schedule->specialist_id, $this->user)) {
                    ApiException::set(400);
                }
                
                if ($schedule->delete()) {
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
