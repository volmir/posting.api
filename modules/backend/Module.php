<?php

namespace app\modules\backend;

use yii\filters\AccessControl;

/**
 * backend module definition class
 */
class Module extends \yii\base\Module
{
    
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();

    }
}
