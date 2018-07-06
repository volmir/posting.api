<?php

namespace app\modules\api\v1\models;

use Yii;
use yii\filters\auth\HttpBearerAuth;
use app\modules\api\v1\exceptions\ApiException;

class Authentification {
    
    /**
     * 
     * @return app\modules\api\v1\models\UserApi
     */
    public static function verify() {
        $auth = new HttpBearerAuth();
        $user = $auth->authenticate(Yii::$app->getModule('v1')->user_api, Yii::$app->request, Yii::$app->response);
        if ($user) {
            return $user;
        } else {
            ApiException::set(401);
        } 
    }
    
}
