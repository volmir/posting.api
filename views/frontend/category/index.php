<?php

/* @var $this yii\web\View */

use yii\helpers\Html;
use app\widgets\Catalog;

$this->title = 'Categories';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-category">
    <h1><?= Html::encode($this->title) ?></h1>

    <p>
 <?= Catalog::widget(['data' => $categories]); ?>
    </p>

</div>
