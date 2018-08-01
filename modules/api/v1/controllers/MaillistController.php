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
use app\models\Maillist;

class MaillistController extends Controller {

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
        Authentification::verifyByType($this->user, UserApi::TYPE_COMPANY);

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
        $maillist = Maillist::find()
                ->where(['company_id' => $this->user->id]);
        if ($this->row_id) {
            $maillist = $maillist->andWhere(['=', 'id', $this->row_id]);
        }
        $maillist = $maillist
                ->orderBy(['id' => SORT_DESC])
                ->limit($this->limit)
                ->asArray()
                ->all();

        $this->result = $maillist;
    }

    private function post() {
        $data = Yii::$app->request->post();
        if (!empty($data['title']) && !empty($data['text']) && !empty($data['type']) && in_array($data['type'], [Maillist::TYPE_EMAIL, Maillist::TYPE_SMS])) {
            $maillist = new Maillist();
            $maillist->company_id = $this->user->id;
            $maillist->title = $data['title'];
            $maillist->text = $data['text'];
            $maillist->type = $data['type'];
            $maillist->status = Maillist::STATUS_NEW;
            if ($maillist->save()) {
                Yii::$app->response->headers->set('Location', '/api/v1/maillist/' . $maillist->getPrimaryKey());
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
        if ($this->row_id > 0) {
            $maillist = Maillist::find()
                    ->where([
                'id' => $this->row_id,
                'company_id' => $this->user->id,
            ]);
            $maillist = $maillist
                    ->one();

            if ($maillist instanceof Maillist) {
                if (!empty($data['title'])) {
                    $maillist->title = $data['title'];
                }
                if (!empty($data['text'])) {
                    $maillist->text = $data['text'];
                }

                if ($maillist->save()) {
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
            $maillist = Maillist::find()
                    ->where([
                'id' => $this->row_id,
                'company_id' => $this->user->id,
            ]);
            $maillist = $maillist
                    ->one();

            if ($maillist instanceof Maillist) {
                if ($maillist->delete()) {
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
