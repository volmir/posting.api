<?php

namespace app\modules\api\v1\exceptions;

use Yii;

class ApiException {
    
    public static function set($code = 404) {
        if (Yii::$app->request->headers->get('Accept') == 'application/xml') {
            Yii::$app->response->format = \yii\web\Response::FORMAT_XML;
        } else {
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        } 
        
        throw new \yii\web\HttpException($code);       
    }
    
}
