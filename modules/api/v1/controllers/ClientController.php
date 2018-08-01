<?php

namespace app\modules\api\v1\controllers;

use Yii;
use yii\web\Controller;
use app\modules\api\v1\models\UserApi;
use app\modules\api\v1\exceptions\ApiException;
use app\modules\api\v1\models\Authentification;
use app\models\user\SignupForm;
use app\models\Client;

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
    public function actionIndex() {
        $this->user = Authentification::verify();
        Authentification::verifyByType($this->user, UserApi::TYPE_CLIENT);

        if (Yii::$app->request->method == 'GET') {
            $client = new \stdClass();
            $client->id = $this->user->id;
            $client->username = $this->user->username;
            $client->email = $this->user->email;
            $client->firstname = $this->user->firstname;
            $client->lastname = $this->user->lastname;
            $client->created_at = $this->user->created_at;

            $this->result = $client;
        } else {
            ApiException::set(405);
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
                        $client = new Client();
                        $client->id = $user->id;
                        $client->description = (!empty($data['description']) ? $data['description'] : '');
                        try {
                            if ($client->save()) {
                                Yii::$app->response->headers->set('Location', '/api/v1/client/');
                                ApiException::set(201);
                            } else {
                                $user->delete();
                                ApiException::set(400);
                            }
                        } catch (\RuntimeException $e) {
                            ApiException::set(400);
                        }
                    }
                } catch (\RuntimeException $e) {
                    ApiException::set(400);
                }
            } else {
                ApiException::set(400);
            }
        } else {
            ApiException::set(405);
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
            ApiException::set(405);
        }

        return $this->result;
    }

}
