<?php

return [
    'components' => [
        'user_api' => [
            'class' => 'yii\web\User',
            'identityClass' => 'app\modules\api\v1\models\User',
            'enableSession' => false,
        ],
    ],
];

