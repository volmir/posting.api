<?php

namespace app\modules\api\v1\controllers;

use yii\web\Controller;


class DefaultController extends Controller
{
    
    public function init()
    {
        $this->enableCsrfValidation = false;
    }    

    /**
     * @inheritdoc
     */
    public function behaviors() {
        return array_merge(parent::behaviors(), [
            'corsFilter' => [
                'class' => \yii\filters\Cors::className(),
                'cors' => [
                    'Origin' => ['*'],
                    'Access-Control-Request-Method' => ['GET', 'POST', 'PUT', 'PATCH', 'DELETE'],
                    'Access-Control-Allow-Credentials' => true,
                    'Access-Control-Max-Age' => 3600,
                ],
            ],
        ]);
    }
    
    /**
     * Renders the index view for the module
     * @return stdClass
     */
    public function actionIndex()
    {
        $result = new \stdClass();
        $result->status = 'Success';
        $result->version = '1.0';
        
        return $result;
    }
}
