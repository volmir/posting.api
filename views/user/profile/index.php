<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
 
/* @var $this yii\web\View */
/* @var $model app\models\User */
 
$this->title = 'Профиль';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-profile">
 
    <h1><?= Html::encode($this->title) ?></h1>
    
    <p>
        <?= Html::a('Редактировать', ['edit'], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Сменить пароль', ['password-change'], ['class' => 'btn btn-primary']) ?>
    </p>    
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'username',
            'email',
            'firstname',
            'lastname',
        ],
    ]) ?>
 
</div>
