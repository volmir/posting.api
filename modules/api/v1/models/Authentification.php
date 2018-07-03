<?php

namespace app\modules\api\v1\models;

use Yii;
use yii\filters\auth\HttpBearerAuth;
use app\modules\api\v1\models\ApiException;

class Authentification {
    
    public static function verify() {
        $auth = new HttpBearerAuth();
        if (!$auth->authenticate(Yii::$app->getModule('v1')->user_api, Yii::$app->request, Yii::$app->response)) {
            ApiException::set(401);
        }        
    }
    
}
