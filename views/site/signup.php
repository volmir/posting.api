<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
?>

<h1>Регистрация пользователя</h1>

<?php $form = ActiveForm::begin() ?>
<?= $form->field($model, 'username') ?>
<?= $form->field($model, 'password')->passwordInput() ?>
<?= $form->field($model, 'email') ?>
<?= $form->field($model, 'firstname') ?>
<?= $form->field($model, 'lastname') ?>
<div class="form-group">
 <div>
 <?= Html::submitButton('Отправить', ['class' => 'btn btn-primary']) ?>
 </div>
</div>
<?php ActiveForm::end() ?>

