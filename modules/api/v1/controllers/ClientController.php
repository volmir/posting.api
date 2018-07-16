<?php

namespace app\modules\api\v1\controllers;

use Yii;
use yii\web\Controller;
use app\modules\api\v1\models\UserApi;
use app\modules\api\v1\exceptions\ApiException;
use app\modules\api\v1\models\Authentification;
use app\models\user\SignupForm;

class ClientController extends Controller {

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
     * @return stdClass
     */
    public function actionIndex() {
        $this->user = Authentification::verify();
        Authentification::verifyByType($this->user, UserApi::TYPE_CLIENT);

        if (Yii::$app->request->method == 'GET') {
            $user = new \stdClass();
            $user->id = $this->user->id;
            $user->username = $this->user->username;
            $user->email = $this->user->email;
            $user->firstname = $this->user->firstname;
            $user->lastname = $this->user->lastname;
            $user->created_at = $this->user->created_at;

            $this->result = $user;
        } else {
            ApiException::set(400);
        }

        return $this->result;
    }

    public function actionCreate() {
        if (Yii::$app->request->method == 'POST') {
            $data = Yii::$app->request->post();
            if (!empty($data['username']) && !empty($data['password']) && !empty($data['email'])) {
                $user = new UserApi();
                $user->username = $data['username'];
                $user->password = \Yii::$app->security->generatePasswordHash($data['password']);
                $user->email = $data['email'];
                $user->firstname = (!empty($data['firstname']) ? $data['firstname'] : '');
                $user->lastname = (!empty($data['lastname']) ? $data['lastname'] : '');
                $user->phone = (!empty($data['phone']) ? $data['phone'] : '');
                $user->generateAuthKey();
                $user->generateAccessToken();
                $user->generateEmailConfirmToken();
                $user->status = UserApi::STATUS_ACTIVE;
                $user->type = UserApi::TYPE_CLIENT;
                try {
                    if ($user->save()) {
                        Yii::$app->response->headers->set('Location', '/api/v1/client/');
                        ApiException::set(201);
                    }
                } catch (\RuntimeException $e) {
                    ApiException::set(400);
                }
            } else {
                ApiException::set(400);
            }
        } else {
            ApiException::set(400);
        }

        return $this->result;
    }

    /**
     * @return stdClass
     */
    public function actionAuth() {
        if (Yii::$app->request->method == 'POST') {
            $data = Yii::$app->request->post();
            if (!empty($data['username']) && !empty($data['password'])) {
                $user = UserApi::find()
                        ->select(['access_token', 'password'])
                        ->where([
                            'username' => $data['username'],
                            'status' => UserApi::STATUS_ACTIVE,
                            'type' => UserApi::TYPE_CLIENT,
                        ])
                        ->limit(1)
                        ->one();
                if (count($user) && \Yii::$app->security->validatePassword($data['password'], $user->password)) {
                    $this->result = ['access_token' => $user->access_token];
                } else {
                    ApiException::set(404);
                }
            } else {
                ApiException::set(400);
            }
        } else {
            ApiException::set(400);
        }

        return $this->result;
    }

}
