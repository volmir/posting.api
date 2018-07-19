<?php

namespace app\modules\api\v1\controllers;

use Yii;
use yii\web\Controller;
use app\modules\api\v1\models\UserApi;
use app\modules\api\v1\exceptions\ApiException;
use app\modules\api\v1\models\Authentification;
use app\models\user\SignupForm;
use app\models\Company;
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

            $upload_files = [];
            $uploads = $this->user->uploads;
            if (!empty($uploads)) {
                foreach ($uploads as $upload) {
                    $upload_files[] = $upload->getWebFilePath(User::TYPE_COMPANY, $this->user->id);
                }
            }

            $user = new \stdClass();
            $user->id = $this->user->id;
            $user->username = $this->user->username;
            $user->email = $this->user->email;
            $user->phone = $this->user->phone;
            $user->description = $this->user->company->description;
            $user->created_at = $this->user->created_at;
            if (count($upload_files)) {
                $user->uploads = $upload_files;
            }

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

    /**
     * 
     * @return stdClass
     */
    public function actionUpload() {
        $this->user = Authentification::verify();
        Authentification::verifyByType($this->user, User::TYPE_COMPANY);

        if (!empty($_FILES)) {
            $done_files = [];
            $max_size = 2000 * 1024;
            $valid_extentions = ["jpg", "png", "gif", "jpeg"];
            $upload_dir = Yii::getAlias('@webroot') . Upload::getPath(User::TYPE_COMPANY, $this->user->id);

            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0777);
            }

            foreach ($_FILES as $file) {
                $file_size = filesize($file['tmp_name']);
                $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
                if (in_array($ext, $valid_extentions)) {
                    if ($file_size < $max_size && $file_size > 0) {
                        $upload = new Upload();
                        $upload->user_id = $this->user->id;
                        $upload->ext = $ext;
                        try {
                            if (!$upload->save()) {
                                ApiException::set(400);
                            }
                        } catch (\RuntimeException $e) {
                            ApiException::set(400);
                        }

                        $filename = $upload->getPrimaryKey() . '.' . $ext;

                        if (move_uploaded_file($file['tmp_name'], $upload_dir . "/" . $filename)) {
                            $done_files[] = realpath($upload_dir . "/" . $filename);
                        }
                    } else {
                        Yii::$app->response->headers->set('Error', 'Incorrect image size');
                    }
                } else {
                    Yii::$app->response->headers->set('Error', 'Incorrect image extention');
                }
            }

            if (count($done_files)) {
                Yii::$app->response->headers->set('Location', '/api/v1/company/');
                ApiException::set(201);
            } else {
                ApiException::set(400);
            }
        } else {
            ApiException::set(400);
        }

        return $this->result;
    }

}
