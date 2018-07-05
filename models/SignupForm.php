<?php

namespace app\models;

use yii\base\Model;
use app\models\User;

class SignupForm extends Model {

    public $username;
    public $password;
    public $email;
    public $firstname;
    public $lastname;

    public function rules() {
        return [
            [['username', 'password', 'email'], 'required', 'message' => 'Заполните поле'],
            ['email', 'email'],
            ['username', 'trim'],
            ['username', 'required'],
            ['username', 'unique', 'targetClass' => User::className(), 'message' => 'Этот логин уже занят'],
            ['username', 'string', 'min' => 2, 'max' => 255],
            ['email', 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            ['email', 'unique', 'targetClass' => User::className(), 'message' => 'Этот емейл уже занят'],
            ['password', 'required'],
            ['password', 'string', 'min' => 6],
            [['firstname', 'lastname'], 'string'],
        ];
    }
    
    /**
     * Signs user up.
     *
     * @return User|null the saved model or null if saving fails
     */
    public function signup()
    {
        if (!$this->validate()) {
            return null;
        }

        $user = new User();
        $user->username = $this->username;
        $user->password = \Yii::$app->security->generatePasswordHash($this->password);
        $user->email = $this->email;
        $user->generateAuthKey();
        $user->generateAccessToken();
        $user->firstname = $this->firstname;
        $user->lastname = $this->lastname;

        return $user->save() ? $user : null;
    }

    public function attributeLabels() {
        return [
            'username' => 'Логин',
            'password' => 'Пароль',
            'email' => 'Электронная почта',
            'firstname' => 'Имя',
            'lastname' => 'Фамилия',
        ];
    }

}
