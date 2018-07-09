<?php

namespace app\modules\backend;

use Yii;
use yii\filters\AccessControl;
use app\modules\backend\rbac\Rbac as BackendRbac;

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
                        'roles' => [BackendRbac::PERMISSION_BACKEND_PANEL],
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
