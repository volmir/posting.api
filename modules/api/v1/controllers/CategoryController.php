<?php

namespace app\modules\api\v1\controllers;

use Yii;
use yii\web\Controller;
use app\modules\api\v1\exceptions\ApiException;
use app\modules\api\v1\models\Authentification;
use app\models\Category;

class CategoryController extends Controller {
    
    /**
     *
     * @var mixed
     */
    private $result;

    /**
     *
     * @var app\modules\api\v1\models\UserApi
     */
    protected $user;    

    public function init() {
        $this->enableCsrfValidation = false;
    }

    /**
     * @inheritdoc
     */
    public function behaviors() {
        $behaviors = parent::behaviors();
        $behaviors['corsFilter'] = [
            'class' => \yii\filters\Cors::className(),
            'cors' => [
                'Origin' => ['*'],
                'Access-Control-Request-Method' => ['GET', 'POST', 'PUT', 'PATCH', 'DELETE'],
                'Access-Control-Allow-Credentials' => true,
                'Access-Control-Max-Age' => 7200,
            ],
        ];

        return $behaviors;
    }

    /**
     * Renders the view for the module
     * @return stdClass
     */
    public function actionIndex() {
        $this->user = Authentification::verify();
        
        if (Yii::$app->request->method == 'GET') {
            $category = Category::find()->all();

            $this->result = $category;
        } else {
            ApiException::set(400);
        }

        return $this->result;
    }

}
