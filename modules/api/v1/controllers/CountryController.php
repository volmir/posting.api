<?php

namespace app\modules\api\v1\controllers;

use Yii;
use yii\web\Controller;
use app\modules\api\v1\exceptions\ApiException;
use app\models\Country;

class CountryController extends Controller {
    
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

    public function actionIndex() {
        if (Yii::$app->request->method == 'GET') {
            $country = Country::find()
                    ->andWhere(['!=', 'cc_iso', ''])
                    ->all();

            $this->result = $country;
        } else {
            ApiException::set(400);
        }

        return $this->result;
    }

}
