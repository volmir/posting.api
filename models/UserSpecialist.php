<?php

namespace app\models;

use Yii;
use app\models\User;

class UserSpecialist extends User {

    /**
     * 
     * @param User $user
     */
    public function verify($user) {
        if ($user->type != self::TYPE_SPECIALIST) {
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            throw new \yii\web\HttpException(401); 
        }
    }

}
