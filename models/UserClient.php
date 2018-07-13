<?php

namespace app\models;

use Yii;
use app\models\User;

class UserClient extends User {

    /**
     * 
     * @param User $user
     */
    public function verify($user) {
        if ($user->type != self::TYPE_CLIENT) {
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            throw new \yii\web\HttpException(401); 
        }
    }

}
