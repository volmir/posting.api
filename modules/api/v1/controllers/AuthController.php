<?php

namespace app\modules\api\v1\controllers;

use Yii;
use yii\web\Controller;
use app\modules\api\v1\models\User;
use app\modules\api\v1\exceptions\ApiException;

class AuthController extends Controller {

    /**
     *
     * @var mixed
     */
    private $result;

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
    public function actionIndex() {
        if (Yii::$app->request->method == 'POST') {
            $this->post();
        } else {
            ApiException::set(400);
        }

        return $this->result;
    }

    private function post() {
        $data = Yii::$app->request->post();
        if (!empty($data['username']) && !empty($data['password'])) {
            $this->result = User::find()
                    ->select(['access_token'])
                    ->where([
                        'username' => $data['username'],
                        'password' => $data['password'],
                        'is_active' => User::STATUS_ACTIVE,
                    ])
                    ->limit(1)
                    ->all();
            
            if (!count($this->result)) {
                ApiException::set(404);
            }
        } else {
            ApiException::set(400);
        }
    }

}
