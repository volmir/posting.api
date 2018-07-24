<?php

namespace app\modules\api\v1\controllers;

use Yii;
use yii\web\Controller;
use app\modules\api\v1\models\UserApi;
use app\modules\api\v1\exceptions\ApiException;
use app\modules\api\v1\models\Authentification;
use app\models\user\SignupForm;
use app\models\Company;
use app\models\Specialist;
use app\models\Upload;
use app\models\User;

class CompanyController extends Controller {

    /**
     *
     * @var mixed
     */
    private $result;

    /**
     *
     * @var app\modules\api\v1\models\User
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
    public function actionIndex() {
        $this->user = Authentification::verify();
        Authentification::verifyByType($this->user, User::TYPE_COMPANY);

        if (Yii::$app->request->method == 'GET') {

            $user = User::find()
                    ->with('company', 'uploads')
                    ->where(['id' => $this->user->id])
                    ->one();            
            
            $upload_files = [];
            $uploads = $user->uploads;
            if (!empty($uploads)) {
                foreach ($uploads as $upload) {
                    $upload_files[] = $upload->getWebFilePath(User::TYPE_COMPANY, $this->user->id);
                }
            }
            
            $company = new \stdClass();
            $company->id = $user->id;
            $company->username = $user->username;
            $company->email = $user->email;
            $company->phone = $user->phone;
            $company->fullname = $user->company->fullname;
            $company->address = $user->company->address;
            $company->description = $user->company->description;
            $company->created_at = $user->created_at;
            if (count($upload_files)) {
                $company->uploads = $upload_files;
            }

            $this->result = $company;
        } else {
            ApiException::set(400);
        }

        return $this->result;
    }

    public function actionCreate() {
        if (Yii::$app->request->method == 'POST') {
            $data = Yii::$app->request->post();
            if (!empty($data['username']) && !empty($data['password']) && !empty($data['email'])) {

                $user = new User();
                $user->username = $data['username'];
                $user->password = \Yii::$app->security->generatePasswordHash($data['password']);
                $user->email = $data['email'];
                $user->phone = (!empty($data['phone']) ? $data['phone'] : '');
                $user->generateAuthKey();
                $user->generateAccessToken();
                $user->generateEmailConfirmToken();
                $user->status = User::STATUS_ACTIVE;
                $user->type = User::TYPE_COMPANY;

                try {
                    if ($user->save()) {
                        $company = new Company();
                        $company->id = $user->id;
                        $company->fullname = (!empty($data['fullname']) ? $data['fullname'] : '');
                        $company->address = (!empty($data['address']) ? $data['address'] : '');
                        $company->description = (!empty($data['description']) ? $data['description'] : '');
                        try {
                            if ($company->save()) {
                                Yii::$app->response->headers->set('Location', '/api/v1/company/');
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
                $user = User::find()
                        ->select(['access_token', 'password'])
                        ->where([
                            'username' => $data['username'],
                            'status' => User::STATUS_ACTIVE,
                            'type' => User::TYPE_COMPANY,
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

    public function actionSpecialist() {
        $this->user = Authentification::verify();
        Authentification::verifyByType($this->user, User::TYPE_COMPANY);        
        
        if (Yii::$app->request->method == 'GET') {
            $specialist = User::find()
                    ->select(['user.id', 'username', 'email', 'firstname', 'lastname', 'phone', 'status'])
                    ->joinWith('specialist')
                    ->where(['specialist.company_id' => $this->user->id]);
            $specialist = $specialist
                    ->asArray()
                    ->all();

            $this->result = $specialist;
        } else {
            ApiException::set(400);
        }

        return $this->result;
    }    
}
