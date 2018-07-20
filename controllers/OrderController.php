<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\helpers\Url;
use app\models\payment\LiqPay;

class OrderController extends Controller {

    public function getViewPath() {
        return Yii::getAlias('@app/views/frontend/order');
    }

    /**
     *
     * @return string
     */
    public function actionIndex() {

        $public_key = '';
        $private_key = '';

        $liqpay = new LiqPay($public_key, $private_key);
        $payment_form = $liqpay->cnb_form(array(
            'action' => 'pay',
            'amount' => '1.00',
            'currency' => 'UAH',
            'description' => 'Payment - '. date('Y-m-d H:i:s'),
            'order_id' => 'LP-' . date('YmdHis'),
            'version' => '3',
            'result_url' => Url::to('order/thankyou', true),
            'server_url' => Url::to('order/thankyou', true),
            'language' => 'ru',
            'sandbox' => '1',
        ));

        return $this->render('index', [
            'payment_form' => $payment_form,
        ]);
    }

    public function actionThankyou() {
        $filename = Yii::getAlias('@runtime') . '/payment/callback.txt';
        $result = json_decode(base64_decode(Yii::$app->request->post('data')));
        file_put_contents($filename, var_export($result, true) . PHP_EOL, FILE_APPEND | LOCK_EX);
        
        if (isset($result->status) && $result->status == 'success') {
            $sql = "UPDATE `orders` SET paid=1 WHERE id=%d";
        }

        return $this->render('thankyou');
    }

}
