<?php

namespace app\modules\api\v1\controllers;

use Yii;
use yii\web\Controller;
use app\modules\api\v1\exceptions\ApiException;
use app\modules\api\v1\models\Authentification;
use app\models\User;
use app\models\Schedule;

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
     * Renders the view for the module
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
            ApiException::set(400);
        }

        return $this->result;
    }

    private function get() {
        $data = Yii::$app->request->get();
        if (!empty($data['specialist_id']) && $this->user->id == $data['specialist_id'] && !empty($data['date_from']) && !empty($data['date_to'])) {
            $schedule = Schedule::find()
                ->where(['specialist_id' => $data['specialist_id']])
                ->andWhere('>', 'date_from', $data['date_from'])
                ->andWhere('<', 'date_to', $data['date_to'])
                ->all();

            $this->result = $schedule;
        } else {
            ApiException::set(400);
        }
    }

    private function post() {
        $data = Yii::$app->request->post();
        if (isset($data['parent_id']) && !empty($data['name'])) {
            $schedule = Schedule::find()
                    ->where([
                        'parent_id' => (int)$data['parent_id'],
                        'name' => $data['name'],
                    ])
                    ->one();
            if ($schedule instanceof Schedule) {
                ApiException::set(400);
            }

            $schedule = new Schedule();
            $schedule->parent_id = (int)$data['parent_id'];
            $schedule->name = $data['name'];
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
    
    private function patch() {
        $data = Yii::$app->request->post();
        if ($this->row_id > 0 && (!empty($data['title']) || !empty($data['content']))) {
            $schedule = Schedule::find()
                    ->where([
                        'id' => $this->row_id,
                        'specialist_id' => $this->user->id,
                    ])
                    ->one();
            if ($schedule instanceof Schedule) {
                if (!empty($data['title'])) {
                    $schedule->title = $data['title'];
                }
                if (!empty($data['content'])) {
                    $schedule->content = $data['content'];
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
                    ->where([
                        'id' => $this->row_id,
                        'specialist_id' => $this->user->id,
                    ])
                    ->one();
            if ($schedule instanceof Schedule) {
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
