<?php

namespace app\modules\api\v1\models;

use Yii;

/**
 * This is the model class for table "user".
 *
 * @property int $id
 * @property string $username
 * @property string $password
 * @property string $email
 * @property string $auth_key
 * @property string $access_token
 * @property string $firstname
 * @property string $lastname
 * @property int $is_active
 * @property int $is_deleted
 * @property string $date_created
 *
 * @property Post[] $posts
 */
class UserApi extends \app\models\User {

    public function init() {
        parent::init();
        \Yii::$app->user->enableSession = false;
    }

}
