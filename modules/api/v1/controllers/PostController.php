<?php

namespace app\modules\api\v1\controllers;

use Yii;
use yii\web\Controller;
use app\modules\api\v1\models\Post;

class PostController extends Controller {

    /**
     *
     * @var int
     */
    private $page_limit = 50;

    /**
     *
     * @var mixed
     */
    private $result;

    public function init() {
        $this->enableCsrfValidation = false;
    }

    /**
     * @inheritdoc
     */
    public function behaviors() {
        return array_merge(parent::behaviors(), [
            'corsFilter' => [
                'class' => \yii\filters\Cors::className(),
                'cors' => [
                    'Origin' => ['*'],
                    'Access-Control-Request-Method' => ['GET', 'POST', 'PUT', 'PATCH', 'DELETE'],
                    'Access-Control-Allow-Credentials' => true,
                    'Access-Control-Max-Age' => 3600,
                ],
            ],
        ]);
    }    

    /**
     * Renders the index view for the module
     * @return stdClass
     */
    public function actionIndex($id = 0) {
        $this->id = $id;

        if (Yii::$app->request->method == 'POST') {
            $this->post();
        } elseif (Yii::$app->request->method == 'PUT') {
            $this->put();
        } elseif (Yii::$app->request->method == 'PUT') {
            $this->patch();
        } elseif (Yii::$app->request->method == 'DELETE') {
            $this->delete();
        } else {
            $this->get();
        }

        return $this->result;
    }

    private function get() {
        if ($this->id > 0) {
            $this->result = Post::find()
                    ->where(['id' => $this->id])
                    ->one();
        } else {
            $this->result = Post::find()
                    ->limit($this->page_limit)
                    ->all();
        }
    }

    private function post() {
        if (!empty(Yii::$app->request->post()['title']) && !empty(Yii::$app->request->post()['content'])) {
            $post = new Post();
            $post->user_id = 1;
            $post->title = Yii::$app->request->post()['title'];
            $post->content = Yii::$app->request->post()['content'];
            if ($post->save()) {
                $this->result = 'Success';
            } else {
                Yii::$app->response->statusCode = 400;
                $this->result = 'Bad Request';
            }
        } else {
            Yii::$app->response->statusCode = 400;
            $this->result = 'Bad Request';
        }
        
    }

    private function put() {
        $this->result = 'PUT';
    }

    private function patch() {
        $this->result = 'PATCH';
    }

    private function delete() {
        $this->result = 'DELETE';
    }

}
