<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "posts".
 *
 * @property int $id
 * @property int $user_id
 * @property string $title
 * @property string $content
 * @property string $date_create
 * @property int $status
 */
class Post extends \yii\db\ActiveRecord
{
    
    const STATUS_WAIT = 0; 
    const STATUS_ACTIVE = 1;
    const STATUS_BLOCKED = 2;      
    
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'post';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'title', 'content'], 'required'],
            [['user_id', 'status'], 'integer'],
            [['content'], 'string'],
            [['date_create'], 'safe'],
            [['title'], 'string', 'max' => 250],
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
            'title' => 'Title',
            'content' => 'Content',
            'date_create' => 'Date Create',
            'status' => 'Status',
        ];
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
    
}
