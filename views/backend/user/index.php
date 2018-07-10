<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\models\User;
use app\components\grid\SetColumn;
use app\components\grid\LinkColumn;

/* @var $this yii\web\View */
/* @var $searchModel app\models\backend\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Users';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create User', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?=
    GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'id',
            [
                'class' => LinkColumn::className(),
                'attribute' => 'username',
            ],
            //'password',
            'email:email',
            //'auth_key',
            //'access_token',
            //'password_reset_token',
            //'email_confirm_token:email',
            'firstname',
            'lastname',
            [
                'class' => SetColumn::className(),
                'filter' => User::getStatusesArray(),
                'attribute' => 'status',
                'name' => 'statusName',
                'cssCLasses' => [
                    User::STATUS_WAIT => 'default',
                    User::STATUS_ACTIVE => 'success',
                    User::STATUS_BLOCKED => 'warning',
                ],
            ],
            'created_at',
            [
                'class' => 'yii\grid\ActionColumn',
                'contentOptions' => [
                    'class' => 'action-column',
                ],
            ],
        ],
    ]);
    ?>
</div>
