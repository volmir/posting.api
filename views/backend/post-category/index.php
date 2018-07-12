<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\models\Category;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $searchModel app\models\backend\PostCategorySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Post Categories';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="post-category-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Post Category', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            [
                'attribute' => 'post_id',
                'value' => 'post.title',
                'header' => 'Post',
            ],
            [
                'attribute' => 'category_id',
                'value' => 'category.name',
                'header' => 'Category',
                'filter' => ArrayHelper::map(Category::find()->all(), 'id', 'name'),
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
