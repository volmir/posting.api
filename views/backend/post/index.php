<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\models\Post;
use app\components\grid\SetColumn;
use app\models\backend\PostSearch;

/* @var $this yii\web\View */
/* @var $searchModel app\models\backend\PostSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Posts';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="post-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Post', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            [
                'attribute' => 'user_id',
                'value' => 'user.username',
                'header' => 'User',
                'filter' => PostSearch::getUserList(),
            ],
            'title',
            'content:ntext',
            'date_create',
            [
                'class' => SetColumn::className(),
                'filter' => Post::getStatusesArray(),
                'attribute' => 'status',
                'name' => 'statusName',
                'cssCLasses' => [
                    Post::STATUS_WAIT => 'default',
                    Post::STATUS_ACTIVE => 'success',
                    Post::STATUS_BLOCKED => 'warning',
                ],
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'contentOptions' => [
                    'class' => 'action-column',
                ],
            ],
        ],
    ]); ?>
</div>
