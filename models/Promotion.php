<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "promotion".
 *
 * @property int $id
 * @property int $company_id
 * @property int $service_id
 * @property string $title
 * @property string $description
 * @property string $price
 * @property int $currency_id
 * @property int $discount
 * @property string $date_start
 * @property string $date_end
 *
 * @property Company $company
 * @property Service $service
 * @property Currency $currency
 */
class Promotion extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'promotion';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['company_id', 'description'], 'required'],
            [['company_id', 'service_id', 'currency_id', 'discount'], 'integer'],
            [['description'], 'string'],
            [['price'], 'number'],
            [['date_start', 'date_end'], 'safe'],
            [['title'], 'string', 'max' => 500],
            [['company_id'], 'exist', 'skipOnError' => true, 'targetClass' => Company::className(), 'targetAttribute' => ['company_id' => 'id']],
            [['service_id'], 'exist', 'skipOnError' => true, 'targetClass' => Service::className(), 'targetAttribute' => ['service_id' => 'id']],
            [['currency_id'], 'exist', 'skipOnError' => true, 'targetClass' => Currency::className(), 'targetAttribute' => ['currency_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'company_id' => 'Company ID',
            'service_id' => 'Service ID',
            'title' => 'Title',
            'description' => 'Description',
            'price' => 'Price',
            'currency_id' => 'Currency ID',
            'discount' => 'Discount',
            'date_start' => 'Date Start',
            'date_end' => 'Date End',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCompany()
    {
        return $this->hasOne(Company::className(), ['id' => 'company_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getService()
    {
        return $this->hasOne(Service::className(), ['id' => 'service_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCurrency()
    {
        return $this->hasOne(Currency::className(), ['id' => 'currency_id']);
    }
}
