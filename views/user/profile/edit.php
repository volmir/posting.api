<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
 
/* @var $this yii\web\View */
/* @var $model app\models\User */
 
$this->title = 'Редактирование профиля';
$this->params['breadcrumbs'][] = ['label' => 'Профиль', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<?php $form = ActiveForm::begin([]); ?>
 
<?= $form->field($model, 'username')->input('username', ['disabled' => 'disabled']) ?>
<?= $form->field($model, 'email')->input('email', ['disabled' => 'disabled']) ?>
<?= $form->field($model, 'firstname')->input('firstname', ['maxlength' => true]) ?>
<?= $form->field($model, 'lastname')->input('lastname', ['maxlength' => true]) ?>
 
<div class="form-group">
    <?= Html::submitButton('Сохранить', ['class' => 'btn btn-primary']) ?>
</div>
 
<?php ActiveForm::end(); ?>
