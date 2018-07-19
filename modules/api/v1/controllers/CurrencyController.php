<?php

namespace app\modules\api\v1\controllers;

use Yii;
use yii\web\Controller;
use app\modules\api\v1\exceptions\ApiException;
use app\models\Currency;

class CurrencyController extends Controller {
    
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
     * 
     * @return stdClass
     */
    public function actionIndex() {
        if (Yii::$app->request->method == 'GET') {
            $currency = Currency::find()->all();

            $this->result = $currency;
        } else {
            ApiException::set(400);
        }

        return $this->result;
    }

}
