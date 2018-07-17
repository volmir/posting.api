<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use app\models\Category;

class CategoryController extends Controller {

    public function getViewPath() {
        return Yii::getAlias('@app/views/frontend/category');
    }

    /**
     * Displays index page.
     *
     * @return string
     */
    public function actionIndex() {
        $categories = Category::find()
                ->where(['status' => Category::STATUS_ACTIVE])
                ->andWhere(['!=', 'id', 0])
                ->all();
        
        return $this->render('index', [
            'categories' => $categories,
        ]);
    }

}
