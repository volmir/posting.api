<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "schedule".
 *
 * @property int $id
 * @property int $specialist_id
 * @property string $date_from
 * @property string $date_to
 *
 * @property Order $order
 * @property Specialist $specialist
 */
class Schedule extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'schedule';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['specialist_id', 'date_from', 'date_to'], 'required'],
            [['specialist_id'], 'integer'],
            [['date_from', 'date_to'], 'safe'],
            [['specialist_id'], 'exist', 'skipOnError' => true, 'targetClass' => Specialist::className(), 'targetAttribute' => ['specialist_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'specialist_id' => 'Specialist ID',
            'date_from' => 'Date From',
            'date_to' => 'Date To',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrder()
    {
        return $this->hasOne(Order::className(), ['schedule_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSpecialist()
    {
        return $this->hasOne(Specialist::className(), ['id' => 'specialist_id']);
    }
}
