<?php

use yii\helpers\Html;

/* @var $user app\model\User */

$confirmLink = \Yii::$app->urlManager->createAbsoluteUrl(['user/signup-confirm', 'token' => $user->email_confirm_token]);
?>
<div class="password-reset">
    <p>Hello <?= Html::encode($user->username) ?>,</p>

    <p>Follow the link below to confirm your email:</p>

    <p><?= Html::a(Html::encode($confirmLink), $confirmLink) ?></p>
</div>
