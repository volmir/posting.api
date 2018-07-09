<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;

class AboutController extends Controller {

    /**
     * Displays index page.
     *
     * @return string
     */
    public function actionIndex() {
        return $this->render('index');
    }

}
