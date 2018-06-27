<?php

namespace app\modules\api\v1;

use Yii;

/**
 * api_v1 module definition class
 */
class Module extends \yii\base\Module {

    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'app\modules\api\v1\controllers';

    /**
     * {@inheritdoc}
     */
    public function init() {
        parent::init();

        $this->setOutputFormat();
        $this->checkJsonData();
    }

    private function setOutputFormat() {
        if (Yii::$app->request->headers->get('Content-Type') == 'application/xml') {
            Yii::$app->response->format = \yii\web\Response::FORMAT_XML;
        } else {
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        }        
    }
    
    private function checkJsonData() {
        $methods_to_check = array('POST', 'PUT', 'PATCH');
        if (in_array(strtoupper(Yii::$app->request->getMethod()), $methods_to_check)) {
            $input = '';
            $file_handle = fopen('php://input', 'r');
            while (!feof($file_handle)) {
                $s = fread($file_handle, 64);
                $input .= $s;
            }
            fclose($file_handle);
            if (!empty($input)) {
                $_POST = array_merge($_POST, (array)json_decode($input));
            }
        }
    }

}
