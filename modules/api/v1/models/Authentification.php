<?php

namespace app\modules\api\v1\models;

use Yii;
use yii\filters\auth\HttpBearerAuth;
use app\modules\api\v1\exceptions\ApiException;
use app\models\User;

class Authentification {
    
    /**
     * 
     * @return app\modules\api\v1\models\UserApi
     */
    public static function verify() {
        $auth = new HttpBearerAuth();
        $user = $auth->authenticate(Yii::$app->getModule('v1')->user_api, Yii::$app->request, Yii::$app->response);
        if ($user && $user->status == User::STATUS_ACTIVE) {
            return $user;
        } else {
            ApiException::set(401);
        } 
    }
    
    /**
     * 
     * @param User $user
     * @param int $type_id
     */
    public function verifyByType($user, $type_id) {
        if ($user->type != $type_id) {
            ApiException::set(401);
        }
    }    
    
}
