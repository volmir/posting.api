<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use leandrogehlen\treegrid\TreeGrid;
use app\models\Category;
use app\components\grid\SetColumn;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\rpg\models\TreeSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Categories';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tree-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?php
        if ($dataProvider->count == 0)
            echo Html::a('Создать корневой элемент', ['add'], ['class' => 'btn btn-success']);
        ?>
    </p>
    <?=
    TreeGrid::widget([
        'dataProvider' => $dataProvider,
        'keyColumnName' => 'id',
        'showOnEmpty' => FALSE,
        'parentColumnName' => 'parent_id',
        'columns' => [
            'name',
           [
                'class' => SetColumn::className(),
                'filter' => Category::getStatusesArray(),
                'attribute' => 'status',
                'name' => 'statusName',
                'cssCLasses' => [
                    Category::STATUS_WAIT => 'default',
                    Category::STATUS_ACTIVE => 'success',
                    Category::STATUS_BLOCKED => 'warning',
                ],
            ],
            ['class' => 'yii\grid\ActionColumn',
                'template' => '{view} {update} {delete} {add}',
                'urlCreator' => function ($action, $model, $key, $index) {
                    return Url::to(['category/'.$action, 'id' => $model->id]);
                },
                'buttons' => [
                    'add' => function ($url, $model, $key)
                    {
                        return Html::a('<span class="glyphicon glyphicon-plus"></span>', $url);
                    },
                ]
            ]
        ]
    ]);
    ?>

</div>
