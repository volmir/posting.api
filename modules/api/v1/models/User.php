<?php

namespace app\modules\api\v1\models;

use Yii;

/**
 * This is the model class for table "users".
 *
 * @property int $id
 * @property string $login
 * @property string $email
 * @property string $password
 * @property string $firstname
 * @property string $lastname
 * @property int $is_active
 * @property int $is_deleted
 * @property string $date_created
 */
class User extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['login', 'email', 'password', 'firstname', 'lastname', 'date_created'], 'required'],
            [['is_active', 'is_deleted'], 'integer'],
            [['date_created'], 'safe'],
            [['login', 'email', 'password', 'firstname', 'lastname'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'login' => 'Login',
            'email' => 'Email',
            'password' => 'Password',
            'firstname' => 'Firstname',
            'lastname' => 'Lastname',
            'is_active' => 'Is Active',
            'is_deleted' => 'Is Deleted',
            'date_created' => 'Date Created',
        ];
    }
}
