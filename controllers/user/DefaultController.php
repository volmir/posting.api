<?php

namespace app\controllers\user;

use yii\web\Controller;

/**
 * Default controller for the `backend` module
 */
class DefaultController extends Controller
{
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        return $this->redirect('user/profile');
    }
    public function actionLogin()
    {
        echo 'asdfZSDfs';
    }    
    public function actionLogout()
    {
        echo 'asdzfvzxdfvsdfg 32';
    }     
}
