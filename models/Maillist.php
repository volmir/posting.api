<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "maillist".
 *
 * @property int $id
 * @property int $company_id
 * @property int $type
 * @property int $status
 * @property string $title
 * @property string $text
 *
 * @property Company $company
 */
class Maillist extends \yii\db\ActiveRecord
{
    const TYPE_EMAIL = 1;
    const TYPE_SMS = 2;
    
    const STATUS_NEW = 0; 
    const STATUS_ACTIVE = 1;
    const STATUS_SENT = 2;     
    
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'maillist';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['company_id', 'type', 'text'], 'required'],
            [['company_id', 'type', 'status'], 'integer'],
            [['text'], 'string'],
            [['title'], 'string', 'max' => 255],
            [['company_id'], 'exist', 'skipOnError' => true, 'targetClass' => Company::className(), 'targetAttribute' => ['company_id' => 'id']],
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
            'type' => 'Type',
            'status' => 'Status',
            'title' => 'Title',
            'text' => 'Text',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCompany()
    {
        return $this->hasOne(Company::className(), ['id' => 'company_id']);
    }
}
