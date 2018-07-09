<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use app\models\ContactForm;

class ContactController extends Controller {

    /**
     * Displays index page.
     *
     * @return Response|string
     */
    public function actionIndex() {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('index', [
                    'model' => $model,
        ]);
    }

}
