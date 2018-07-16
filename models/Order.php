<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "order".
 *
 * @property int $id
 * @property int $client_id
 * @property int $schedule_id
 * @property int $status_client
 * @property int $status_specialist
 * @property string $created_at
 *
 * @property Client $client
 * @property Schedule $schedule
 * @property OrderVsService[] $orderVsServices
 * @property Service[] $services
 */
class Order extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'order';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['client_id', 'schedule_id'], 'required'],
            [['client_id', 'schedule_id', 'status_client', 'status_specialist'], 'integer'],
            [['created_at'], 'safe'],
            [['schedule_id'], 'unique'],
            [['client_id'], 'exist', 'skipOnError' => true, 'targetClass' => Client::className(), 'targetAttribute' => ['client_id' => 'id']],
            [['schedule_id'], 'exist', 'skipOnError' => true, 'targetClass' => Schedule::className(), 'targetAttribute' => ['schedule_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'client_id' => 'Client ID',
            'schedule_id' => 'Schedule ID',
            'status_client' => 'Status Client',
            'status_specialist' => 'Status Specialist',
            'created_at' => 'Created At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getClient()
    {
        return $this->hasOne(Client::className(), ['id' => 'client_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSchedule()
    {
        return $this->hasOne(Schedule::className(), ['id' => 'schedule_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrderServices()
    {
        return $this->hasMany(OrderService::className(), ['order_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getServices()
    {
        return $this->hasMany(Service::className(), ['id' => 'service_id'])->viaTable('order_vs_service', ['order_id' => 'id']);
    }
}
