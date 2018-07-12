<?php

use yii\helpers\Html;
use app\models\Category;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\PostCategory */

$this->title = 'Update Post Category: ' . $model->post->title;
$this->params['breadcrumbs'][] = ['label' => 'Post Categories', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->post->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="post-category-update">

    <h1><?= Html::encode($this->title) ?></h1>

<?php
$form = ActiveForm::begin();
?>

<?= $form->field($model->post, 'title')->input('text', ['disabled' => 'disabled']) ?>
<?= $form->field($model, 'category_id')->dropDownList(ArrayHelper::map(Category::find()->all(), 'id', 'name')) ?>
<div class="form-group">
    <?= Html::submitButton('Сохранить', ['class' => 'btn btn-primary']) ?>
</div>

<?php ActiveForm::end(); ?>


</div>
