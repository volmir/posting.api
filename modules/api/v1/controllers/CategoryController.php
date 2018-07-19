<?php

namespace app\modules\api\v1\controllers;

use Yii;
use yii\web\Controller;
use app\modules\api\v1\exceptions\ApiException;
use app\modules\api\v1\models\Authentification;
use app\models\Category;
use app\models\User;

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
     * 
     * @return stdClass
     */
    public function actionIndex() {
        $this->user = Authentification::verify();

        if (Yii::$app->request->method == 'GET') {
            $this->get();
        } elseif (Yii::$app->request->method == 'POST') {
            $this->post();
        } else {
            ApiException::set(400);
        }

        return $this->result;
    }

    private function get() {
        $category = Category::find()
                ->select(['id', 'parent_id', 'name'])
                ->where(['status' => Category::STATUS_ACTIVE])
                ->andWhere(['!=', 'id', 0])
                ->all();

        $this->result = $category;
    }
    
    private function post() {
        Authentification::verifyByType($this->user, User::TYPE_COMPANY);
        
        $data = Yii::$app->request->post();
        if (isset($data['parent_id']) && !empty($data['name'])) {
            $category = Category::find()
                    ->where([
                        'parent_id' => $data['parent_id'],
                        'name' => $data['name'],
                    ])
                    ->one();
            if ($category instanceof Category) {
                ApiException::set(400);
            }

            $category = new Category();
            $category->parent_id = (int)$data['parent_id'];
            $category->name = $data['name'];
            if ($category->save()) {
                Yii::$app->response->headers->set('Location', '/api/v1/category/' . $category->getPrimaryKey());
                ApiException::set(201);
            } else {
                ApiException::set(400);
            }
        } else {
            ApiException::set(400);
        }
    }

}
