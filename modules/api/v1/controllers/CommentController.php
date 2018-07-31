<?php

namespace app\modules\api\v1\controllers;

use Yii;
use yii\web\Controller;
use app\modules\api\v1\exceptions\ApiException;
use app\modules\api\v1\models\Authentification;
use app\modules\api\v1\models\UserApi;
use app\models\User;
use app\models\Specialist;
use app\models\Company;
use app\models\Comment;

class CommentController extends Controller {

    /**
     *
     * @var mixed
     */
    private $result;
    
    /**
     *
     * @var int
     */
    private $limit = 50;    
    /**
     *
     * @var int
     */
    private $offset = 0;    

    /**
     *
     * @var app\modules\api\v1\models\UserApi
     */
    protected $user;
    
    /**
     *
     * @var int
     */
    protected $row_id;    

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
    public function actionIndex($id = 0) {
        $this->row_id = $id;
        
        $this->user = Authentification::verify();

        if (Yii::$app->request->method == 'GET') {
            $this->get();
        } elseif (Yii::$app->request->method == 'POST') {
            $this->post();
        } elseif (Yii::$app->request->method == 'PATCH') {
            $this->patch();            
        } elseif (Yii::$app->request->method == 'DELETE') {
            $this->delete();            
        } else {
            ApiException::set(405);
        }

        return $this->result;
    }

    private function get() {
        $data = Yii::$app->request->get();
        $this->setLimitOffset($data);
        
        $comment = Comment::find()
                ->joinWith(['client.user clnt', 'specialist.user splt', 'company.user cmpn']);
        $comment = $this->getWhereUserType($comment);
        if ($this->row_id) {
            $comment = $comment->andWhere(['=', 'comment.id', $this->row_id]);
        }
        $comment = $comment
                ->orderBy(['comment.id' => SORT_DESC])
                ->limit($this->limit)
                ->offset($this->offset)
                ->asArray()
                ->all();
        
        foreach ($comment as $key => $one_comment) {
            $client = [
                'username' => $one_comment['client']['user']['username'],
                'email' => $one_comment['client']['user']['email'],
                'firstname' => $one_comment['client']['user']['firstname'],
                'lastname' => $one_comment['client']['user']['lastname'],
            ];
            $specialist = [
                'username' => $one_comment['specialist']['user']['username'],
                'email' => $one_comment['specialist']['user']['email'],
                'firstname' => $one_comment['specialist']['user']['firstname'],
                'lastname' => $one_comment['specialist']['user']['lastname'],
            ];
            $company = [
                'fullname' => $one_comment['company']['fullname'],
                'email' => $one_comment['company']['user']['email'],
            ];
            
            unset($comment[$key]['client']);
            unset($comment[$key]['specialist']);
            unset($comment[$key]['company']);
            
            $comment[$key]['client'] = $client;
            $comment[$key]['specialist'] = $specialist;
            $comment[$key]['company'] = $company;
        }

        $this->result = $comment;
    }

    private function post() {
        Authentification::verifyByType($this->user, UserApi::TYPE_CLIENT);
        
        $data = Yii::$app->request->post();
        if (!empty($data['company_id']) && !empty($data['text'])) {
            $company = Company::find()
                    ->where(['id' => $data['company_id']])
                    ->one();
            if (!$company instanceof Company) {
                ApiException::set(400);
            }

            if (!empty($data['specialist_id'])) {
                $specialist = Specialist::find()
                        ->where(['id' => $data['specialist_id']])
                        ->one();
                if (!$specialist instanceof Specialist || $specialist->company_id != $company->id) {
                    ApiException::set(400);
                }
            }

            if (!$this->checkRatingValue($data['rating'])) {
                unset($data['rating']);
            }

            $comment = new Comment();
            $comment->company_id = $data['company_id'];
            $comment->specialist_id = (!empty($data['specialist_id']) ? $data['specialist_id'] : '');
            $comment->text = $data['text'];
            $comment->rating = (!empty($data['rating']) ? $data['rating'] : '');
            $comment->client_id = $this->user->id;
            if ($comment->save()) {
                Yii::$app->response->headers->set('Location', '/api/v1/comment/' . $comment->getPrimaryKey());
                ApiException::set(201);
            } else {
                ApiException::set(400);
            }
        } else {
            ApiException::set(400);
        }
    }

    private function patch() {
        Authentification::verifyByType($this->user, UserApi::TYPE_CLIENT);
        
        $data = Yii::$app->request->post();
        if ($this->row_id > 0 && (!empty($data['text']) || !empty($data['rating']))) {
            $comment = Comment::find()->where(['id' => $this->row_id]);
            $comment = $this->getWhereUserType($comment);                    
            $comment = $comment->one();
            
            if (!$this->checkRatingValue($data['rating'])) {
                unset($data['rating']);
            }
            
            if ($comment instanceof Comment) {
                if (!empty($data['text'])) {
                    $comment->text = $data['text'];
                }
                if (!empty($data['rating'])) {
                    $comment->rating = $data['rating'];
                }

                if ($comment->save()) {
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
        if ($this->row_id > 0) {
            if (!in_array($this->user->type, [User::TYPE_COMPANY, User::TYPE_CLIENT])) {
                ApiException::set(403);
            }

            $comment = Comment::find()->where(['id' => $this->row_id]);
            $comment = $this->getWhereUserType($comment);                    
            $comment = $comment->one();
            
            if ($comment instanceof Comment) {
                if ($comment->delete()) {
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

    private function checkRatingValue($rating) {
        if (!empty($rating) && ($rating < 1 || $rating > 5)) {
            return false;
        }

        return true;
    }

    private function getWhereUserType($comment) {
        if ($this->user->type == User::TYPE_COMPANY) {
            $comment = $comment->andWhere(['=', 'comment.company_id', $this->user->id]);
        } elseif ($this->user->type == User::TYPE_SPECIALIST) {
            $comment = $comment->andWhere(['=', 'comment.specialist_id', $this->user->id]);
        } elseif ($this->user->type == User::TYPE_CLIENT) {
            $comment = $comment->andWhere(['=', 'comment.client_id', $this->user->id]);
        }
        
        return $comment;
    }

    private function setLimitOffset($data = []) {
        if (!empty($data['limit'])) {
            $this->limit = $data['limit'];
        }
        if (!empty($data['offset'])) {
            $this->offset = $data['offset'];
        }
    }

}
