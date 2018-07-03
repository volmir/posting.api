<?php

namespace app\modules\api\v1\controllers;

use Yii;
use yii\web\Controller;
use app\modules\api\v1\models\ApiException;

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
                'Access-Control-Max-Age' => 3600,
            ],
        ];

        return $behaviors;
    }

    /**
     * Renders the index view for the module
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
        $this->result = [
            'access_token' => '2KfdjUr34K5k73HJIKkrdf92dkLk',
        ];
    }
    
}
