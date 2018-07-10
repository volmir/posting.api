<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;

class AboutController extends Controller {

    public function getViewPath() {
        return Yii::getAlias('@app/views/frontend/about');
    }

    /**
     * Displays index page.
     *
     * @return string
     */
    public function actionIndex() {
        return $this->render('index');
    }

}
