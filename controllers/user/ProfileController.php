<?php

namespace app\controllers\user;

use app\models\User;
use app\models\user\ProfileEditForm;
use app\models\user\PasswordChangeForm;
use yii\filters\AccessControl;
use yii\web\Controller;
use Yii;

class ProfileController extends Controller {

    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    public function actionIndex() {
        return $this->render('index', [
                    'model' => $this->findModel(),
        ]);
    }

    public function actionEdit() {
        $user = $this->findModel();
        $model = new ProfileEditForm($user);

        if ($model->load(Yii::$app->request->post()) && $model->update()) {
            return $this->redirect(['index']);
        } else {
            return $this->render('edit', [
                        'model' => $model,
            ]);
        }
    }
    
    public function actionPasswordChange()
    {
        $user = $this->findModel();
        $model = new PasswordChangeForm($user);
 
        if ($model->load(Yii::$app->request->post()) && $model->changePassword()) {
            return $this->redirect(['index']);
        } else {
            return $this->render('passwordChange', [
                'model' => $model,
            ]);
        }
    }    

    /**
     * @return User the loaded model
     */
    private function findModel() {
        return User::findOne(Yii::$app->user->identity->getId());
    }

}
