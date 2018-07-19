<?php

namespace app\models;

use Yii;
use app\models\User;

/**
 * This is the model class for table "upload".
 *
 * @property int $id
 * @property int $user_id
 * @property string $ext
 *
 * @property User $user
 */
class Upload extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'upload';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'ext'], 'required'],
            [['user_id'], 'integer'],
            [['ext'], 'string', 'max' => 20],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'ext' => 'Ext',
        ];
    }
    
    
    /**
     * 
     * @param int $type
     * @param int $id
     * @return string
     */
    public function getWebFilePath($type, $id) {
        return self::getPath($type, $id) . $this->id . '.' . $this->ext;
    }
    
    /**
     * 
     * @param int $type
     * @param int $id
     * @return string
     * @throws \yii\web\HttpException
     */
    public static function getPath($type, $id) {
        if (!isset($type) || !isset($id)) {
            throw new \yii\web\HttpException(400); 
        }
        
        $path = '/uploads/';        
        if ($type == User::TYPE_COMPANY) {
            $path .= 'company/';
        }
        $path .= substr(md5($id), 0, 12) . '/';
        
        return $path;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
}
