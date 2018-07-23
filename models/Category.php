<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "categories".
 *
 * @property int $id
 * @property int $parent_id
 * @property string $name
 * @property int $status
 */
class Category extends \yii\db\ActiveRecord {

    const STATUS_WAIT = 0; 
    const STATUS_ACTIVE = 1;
    const STATUS_BLOCKED = 2;     
    
    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'category';
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['parent_id'], 'integer'],
            [['name'], 'required'],
            [['name'], 'string', 'max' => 250],
            [['status'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'parent_id' => 'Parent ID',
            'name' => 'Name',
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

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTree() {
        return $this->hasOne(self::className(), ['id' => 'tree_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTrees() {
        return $this->hasMany(self::className(), ['tree_id' => 'id']);
    }

}
