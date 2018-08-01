<?php

namespace app\modules\api\v1\controllers;

use Yii;
use yii\web\Controller;
use app\modules\api\v1\models\Authentification;
use app\modules\api\v1\exceptions\ApiException;
use yii\helpers\ArrayHelper;
use app\models\Order;
use app\models\Schedule;
use app\models\User;
use app\models\Company;
use app\models\Specialist;

class SessionController extends Controller {

    /**
     *
     * @var mixed
     */
    private $result;

    /**
     *
     * @var int
     */
    protected $company_id;

    /**
     *
     * @var app\modules\api\v1\models\UserApi
     */
    protected $user;

    /**
     *
     * @var Company
     */
    protected $company;

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
                'Access-Control-Request-Method' => ['GET', 'POST', 'PUT', 'PATCH', 'DELETE', 'HEAD', 'OPTIONS'],
                'Access-Control-Allow-Credentials' => true,
                'Access-Control-Max-Age' => 7200,
                'Access-Control-Allow-Headers' => ['Origin', 'X-Requested-With', 'Content-Type', 'Accept', 'Authorization'],
                'Access-Control-Allow-Origin' => ['*'],
            ],
        ];

        return $behaviors;
    }

    public function actionIndex($id = 0) {
        $this->company_id = $id;
        $this->user = Authentification::verify();

        $this->loadCompany();

        if (Yii::$app->request->method == 'GET') {
            $this->get();
        } else {
            ApiException::set(405);
        }

        return $this->result;
    }

    private function get() {
        $data = Yii::$app->request->get();

        $sql = "SELECT SUBSTR(date_from, 1, 10) as date_col
                FROM `schedule` 
                WHERE `date_from` > '" . date('Y-m-d H:i:s') . "'
                        " . $this->getWhereSql($data) . "
                GROUP BY date_col
                ORDER BY date_col";
        $schedule = Yii::$app->db->createCommand($sql)->queryAll();

        $result = ArrayHelper::map($schedule, 'date_col', 'date_col');
        sort($result);

        $this->result = $result;
    }

    private function getWhereSql($data) {
        $where_sql = '';

        $specialist = Specialist::find()
                ->select('id')
                ->where(['company_id' => $this->company_id])
                ->asArray()
                ->all();
        $specialist_ids = ArrayHelper::map($specialist, 'id', 'id');

        if (!empty($data['specialist_id'])) {
            if (!in_array($data['specialist_id'], $specialist_ids)) {
                ApiException::set(400);
            }
            $where_sql .= " AND `specialist_id` = " . (int) $data['specialist_id'];
        } else {
            $where_sql .= " AND `specialist_id` IN (" . implode(',', $specialist_ids) . ") ";
        }
        if (!empty($data['date_from']) || !empty($data['date_to'])) {
            if (!empty($data['date_from'])) {
                $date_from = strtotime($data['date_from']);
                $where_sql .= " AND `date_from` > '" . date('Y-m-d H:i:s', $date_from) . "' ";
            }
            if (!empty($data['date_to'])) {
                $date_to = strtotime($data['date_to']);
                $where_sql .= " AND `date_from` < '" . date('Y-m-d H:i:s', $date_to) . "' ";
            }
        } else {
            $where_sql .= " AND `date_from` < '" . date('Y-m-d H:i:s', mktime() + 3600 * 24 * 7) . "' ";
        }

        return $where_sql;
    }

    private function loadCompany() {
        $this->company = Company::find()
                ->where(['id' => $this->company_id])
                ->one();
        if (!$this->company instanceof Company) {
            ApiException::set(400);
        }
    }

    public function actionSchedule($id = 0) {
        $data = Yii::$app->request->get();

        $this->company_id = $id;
        $this->user = Authentification::verify();

        $this->loadCompany();

        if (Yii::$app->request->method == 'GET') {
            $this->getSchedule();
        } else {
            ApiException::set(400);
        }

        return $this->result;
    }

    private function getSchedule() {
        $data = Yii::$app->request->get();

        $sql = "SELECT `s`.`id`
                FROM `schedule` `s`
                LEFT JOIN `order` `ord` ON `ord`.`schedule_id` = `s`.`id`
                WHERE `ord`.`id` IS NULL 
                        AND `s`.`date_from` > '" . date('Y-m-d H:i:s') . "'
                        " . $this->getScheduleWhereSql($data);
        $schedule = Yii::$app->db->createCommand($sql)->queryAll();

        $ids = ArrayHelper::map($schedule, 'id', 'id');
        if (count($ids)) {
            $schedule = Schedule::find()
                    ->with('order.services', 'specialist.user')
                    ->where(['IN', 'id', $ids])
                    ->asArray()
                    ->all();
        } else {
            $schedule = [];
        }
        
        foreach ($schedule as $key => $one_schedule) {
            $specialist = [
                'specialist_id' => $one_schedule['specialist']['user']['id'],
                'username' => $one_schedule['specialist']['user']['username'],
                'email' => $one_schedule['specialist']['user']['email'],
                'firstname' => $one_schedule['specialist']['user']['firstname'],
                'lastname' => $one_schedule['specialist']['user']['lastname'],
                'phone' => $one_schedule['specialist']['user']['phone'],
            ];
            
            unset($schedule[$key]['specialist']);
            $schedule[$key]['specialist'] = $specialist;
        }

        $this->result = $schedule;
    }

    private function getScheduleWhereSql($data) {
        $where_sql = '';

        $specialist = Specialist::find()
                ->select('id')
                ->where(['company_id' => $this->company_id])
                ->asArray()
                ->all();
        $specialist_ids = ArrayHelper::map($specialist, 'id', 'id');

        if (!empty($data['specialist_id'])) {
            if (!in_array($data['specialist_id'], $specialist_ids)) {
                ApiException::set(400);
            }
            $where_sql .= " AND `s`.`specialist_id` = " . (int) $data['specialist_id'];
        } else {
            $where_sql .= " AND `s`.`specialist_id` IN (" . implode(',', $specialist_ids) . ") ";
        }

        if (!empty($data['date'])) {
            $day = $data['date'];
        } else {
            $day = date('Y-m-d');
        }
        $where_sql .= " AND (
                            `s`.`date_from` >= '" . date('Y-m-d', strtotime($day)) . " 00:00:00' AND 
                            `s`.`date_from` <= '" . date('Y-m-d', strtotime($day)) . " 23:59:59' 
                            ) ";

        return $where_sql;
    }

}
