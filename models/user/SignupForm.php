<?php

namespace app\models\user;

use Yii;
use yii\base\Model;
use app\models\User;

class SignupForm extends Model {

    public $username;
    public $password;
    public $email;
    public $firstname;
    public $lastname;
    public $verifyCode;

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
            ['verifyCode', 'captcha'],
        ];
    }

    /**
     * Signs user up.
     *
     * @return User|null the saved model or null if saving fails
     */
    public function signup() {
        if (!$this->validate()) {
            return null;
        }

        $user = new User();
        $user->username = $this->username;
        $user->password = \Yii::$app->security->generatePasswordHash($this->password);
        $user->email = $this->email;
        $user->generateAuthKey();
        $user->generateAccessToken();
        $user->generateEmailConfirmToken();
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
            'verifyCode' => 'Код верификации',
        ];
    }

    public function sentEmailConfirm(User $user) {
        $sent = \Yii::$app->mailer
                ->compose(
                        ['html' => 'userSignupConfirm-html', 'text' => 'userSignupConfirm-text'], 
                        ['user' => $user]
                        )
                ->setTo($user->email)
                ->setFrom(Yii::$app->params['adminEmail'])
                ->setSubject('Confirmation of registration')
                ->send();
        if (!$sent) {
            throw new \RuntimeException('Sending error.');
        }
    }

    public function confirmation($token) {
        if (empty($token)) {
            throw new \DomainException('Empty confirm token.');
        }

        $user = User::findOne(['email_confirm_token' => $token]);
        if (!$user) {
            throw new \DomainException('User is not found.');
        }

        $user->email_confirm_token = '';
        $user->status = User::STATUS_ACTIVE;
        if (!$user->save()) {
            throw new \RuntimeException('Saving error.');
        }

        if (!Yii::$app->getUser()->login($user)) {
            throw new \RuntimeException('Error authentication.');
        }
    }

}
