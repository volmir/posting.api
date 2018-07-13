<?php

namespace app\modules\api\v1\models;

use Yii;
use app\modules\api\v1\models\UserApi;
use app\modules\api\v1\exceptions\ApiException;

class UserSpecialist extends UserApi {

    /**
     * 
     * @param User $user
     */
    public function verify($user) {
        if ($user->type != self::TYPE_SPECIALIST) {
            ApiException::set(401);
        }
    }

}
