<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;
use app\models\Company;
use app\models\Specialist;
use app\models\Client;
use app\models\Upload;

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
 * @property int $status
 * @property string $date_created
 *
 * @property Post[] $posts
 */
class User extends \yii\db\ActiveRecord implements \yii\web\IdentityInterface {

    const STATUS_WAIT = 0; 
    const STATUS_ACTIVE = 1;
    const STATUS_BLOCKED = 2; 
    
    const TYPE_USER = 0;
    const TYPE_ADMIN = 1;
    const TYPE_COMPANY = 2;
    const TYPE_SPECIALIST = 3;
    const TYPE_CLIENT = 4;
    
    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'user';
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['status'], 'integer'],
            [['date_created'], 'safe'],
            [['username', 'password'], 'string', 'max' => 255],
            [['email', 'firstname', 'lastname'], 'string', 'max' => 100],
            [['auth_key', 'access_token'], 'string', 'max' => 60],
            [['username'], 'unique'],
            [['email'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'username' => 'Username',
            'password' => 'Password',
            'email' => 'Email',
            'auth_key' => 'Auth Key',
            'access_token' => 'Access Token',
            'firstname' => 'Firstname',
            'lastname' => 'Lastname',
            'status' => 'Status',
            'date_created' => 'Date Created',
        ];
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentity($id) {
        return static::findOne($id);
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentityByAccessToken($token, $type = null) {
        return static::findOne(['access_token' => $token]);
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username) {
        return static::findOne(['username' => $username]);
    }

    /**
     * {@inheritdoc}
     */
    public function getId() {
        return $this->id;
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthKey() {
        return $this->auth_key;
    }

    public function generateAuthKey() {
        $this->auth_key = \Yii::$app->security->generateRandomString();
    }

    public function generateAccessToken() {
        $this->access_token = \Yii::$app->security->generateRandomString();
    }
    
    public function generateEmailConfirmToken() {
        $this->email_confirm_token = \Yii::$app->security->generateRandomString();
    }    

    /**
     * {@inheritdoc}
     */
    public function validateAuthKey($authKey) {
        return $this->auth_key === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password) {
        return \Yii::$app->security->validatePassword($password, $this->password);
    }

    public static function findByPasswordResetToken($token) {

        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::findOne([
                    'password_reset_token' => $token,
                    'status' => self::STATUS_ACTIVE,
        ]);
    }

    public static function isPasswordResetTokenValid($token) {
        if (empty($token)) {
            return false;
        }

        $timestamp = (int) substr($token, strrpos($token, '_') + 1);
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        return $timestamp + $expire >= time();
    }

    public function generatePasswordResetToken() {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    public function removePasswordResetToken() {
        $this->password_reset_token = '';
    }
    
    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password = Yii::$app->security->generatePasswordHash($password);
    }    
    
    public function getStatusName()
    {
        return ArrayHelper::getValue(self::getStatusesArray(), $this->status);
    }
    
    public static function getStatusesArray()
    {
        return [
            self::STATUS_WAIT => 'WAIT',
            self::STATUS_ACTIVE => 'ACTIVE',
            self::STATUS_BLOCKED => 'BLOCKED',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPosts() {
        return $this->hasMany(Post::className(), ['user_id' => 'id']);
    }    
    
    /**
     * @return \yii\db\ActiveQuery
     */    
    public function getCompany()
    {
        return $this->hasMany(Company::className(), ['id' => 'id']);
    }    

    /**
     * @return \yii\db\ActiveQuery
     */    
    public function getSpecialist()
    {
        return $this->hasMany(Specialist::className(), ['id' => 'id']);
    }     
    
    /**
     * @return \yii\db\ActiveQuery
     */    
    public function getClient()
    {
        return $this->hasMany(Client::className(), ['id' => 'id']);
    }     

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUploads() {
        return $this->hasMany(Upload::className(), ['user_id' => 'id']);
    }     
    
}
