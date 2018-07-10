<?php

namespace app\modules\api\v1\controllers;

use Yii;
use yii\web\Controller;
use app\models\Post;
use app\modules\api\v1\models\Authentification;
use app\modules\api\v1\exceptions\ApiException;

class PostController extends Controller {

    /**
     *
     * @var int
     */
    private $page_limit = 10;

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
        $this->user = Authentification::verify();
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
    public function actionIndex($id = 0) {
        $this->id = $id;

        if (Yii::$app->request->method == 'POST') {
            $this->post();
        } elseif (Yii::$app->request->method == 'PUT') {
            $this->put();
        } elseif (Yii::$app->request->method == 'PATCH') {
            $this->patch();
        } elseif (Yii::$app->request->method == 'DELETE') {
            $this->delete();
        } elseif (Yii::$app->request->method == 'GET') {
            $this->get();
        } else {
            ApiException::set(400);
        }

        return $this->result;
    }

    private function get() {
        if ($this->id > 0) {
            $this->result = Post::find()
                    ->where([
                        'id' => $this->id,
                        'user_id' => $this->user->id,
                    ])
                    ->one();
        } else {
            $this->result = Post::find()
                    ->where([
                        'user_id' => $this->user->id,
                    ])
                    ->limit($this->page_limit)
                    ->all();
        }
        if (!count($this->result)) {
            ApiException::set(404);
        }
    }

    private function post() {
        $data = Yii::$app->request->post();
        if (!empty($data['title']) && !empty($data['content'])) {
            $post = new Post();
            $post->user_id = $this->user->id;
            $post->title = $data['title'];
            $post->content = $data['content'];
            if ($post->save()) {
                Yii::$app->response->headers->set('Location', '/v1/posts/' . $post->getPrimaryKey());
                ApiException::set(201);
            } else {
                ApiException::set(400);
            }
        } else {
            ApiException::set(400);
        }
    }

    private function put() {
        $data = Yii::$app->request->post();
        if ($this->id > 0 && !empty($data['title']) && !empty($data['content'])) {
            $post = Post::find()
                    ->where([
                        'id' => $this->id,
                        'user_id' => $this->user->id,
                    ])
                    ->one();
            if ($post instanceof Post) {
                $post->title = $data['title'];
                $post->content = $data['content'];

                if ($post->save()) {
                    ApiException::set(200);
                } else {
                    ApiException::set(400);
                }
            } else {
                ApiException::set(404);
            }
        } else {
            ApiException::set(400);
        }
    }

    private function patch() {
        $data = Yii::$app->request->post();
        if ($this->id > 0 && (!empty($data['title']) || !empty($data['content']))) {
            $post = Post::find()
                    ->where([
                        'id' => $this->id,
                        'user_id' => $this->user->id,
                    ])
                    ->one();
            if ($post instanceof Post) {
                if (!empty($data['title'])) {
                    $post->title = $data['title'];
                }
                if (!empty($data['content'])) {
                    $post->content = $data['content'];
                }

                if ($post->save()) {
                    ApiException::set(200);
                } else {
                    ApiException::set(400);
                }
            } else {
                ApiException::set(404);
            }
        } else {
            ApiException::set(400);
        }
    }

    private function delete() {
        if ($this->id > 0) {
            $post = Post::find()
                    ->where([
                        'id' => $this->id,
                        'user_id' => $this->user->id,
                    ])
                    ->one();
            if ($post instanceof Post) {
                if ($post->delete()) {
                    ApiException::set(204);
                } else {
                    ApiException::set(400);
                }
            } else {
                ApiException::set(404);
            }
        } else {
            ApiException::set(400);
        }
    }

}
