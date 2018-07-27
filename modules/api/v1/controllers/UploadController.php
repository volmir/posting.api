<?php

namespace app\modules\api\v1\controllers;

use Yii;
use yii\web\Controller;
use app\modules\api\v1\models\UserApi;
use app\modules\api\v1\exceptions\ApiException;
use app\modules\api\v1\models\Authentification;
use app\models\user\SignupForm;
use app\models\Client;
use app\models\Company;
use app\models\Specialist;
use app\models\Upload;
use app\models\User;

class UploadController extends Controller {
    
    const ORIGINAL_FOLDER = 'original';

    /**
     *
     * @var mixed
     */
    private $result;

    /**
     *
     * @var app\modules\api\v1\models\User
     */
    protected $user;

    /**
     *
     * @var int
     */
    protected $row_id;

    /**
     *
     * @var int
     */
    private $max_size = 2000 * 1024;

    /**
     *
     * @var array
     */
    private $image_extentions = ["jpg", "png", "gif", "jpeg"];

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

    public function actionIndex($id = 0) {
        $this->row_id = $id;
        $this->user = Authentification::verify();
        if (!in_array($this->user->type, [User::TYPE_COMPANY])) {
            ApiException::set(403);
        }

        if (Yii::$app->request->method == 'POST') {
            $this->post();
        } elseif (Yii::$app->request->method == 'DELETE') {
            $this->delete();
        } else {
            ApiException::set(400);
        }
    }

    private function post() {
        if (!empty($_FILES)) {
            foreach ($_FILES as $file) {
                if ($this->checkNoLimits($file)) {
                    if ($this->uploadFile($file)) {
                        ApiException::set(201);
                    } else {
                        ApiException::set(400);
                    }
                }
            }
        } else {
            ApiException::set(400);
        }
    }

    private function delete() {
        if ($this->row_id > 0) {
            $upload = Upload::find()
                    ->with('user')
                    ->where(['id' => $this->row_id])
                    ->one();
            if ($upload instanceof Upload) {
                if ($this->deleteFile($upload) && $upload->delete()) {
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

    /**
     * 
     * @param Upload $upload
     * @return boolean
     */
    private function deleteFile($upload) {
        $upload_dir = $this->getUploadDir($upload->user);
        $filename = $upload->getPrimaryKey() . '.' . $upload->ext;
        $file = $upload_dir . '/' . $filename;
        $file_original = $upload_dir . self::ORIGINAL_FOLDER . '/' . $filename;
        if (unlink($file) && unlink($file_original)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 
     * @param array $file
     * @return boolean
     */
    private function uploadFile($file) {
        $user = $this->getUserData();
        $upload_dir = $this->getUploadDir($user);
        $file_ext = $this->getFileExt($file['name']);

        $upload = new Upload();
        $upload->user_id = $user->id;
        $upload->type = Upload::TYPE_IMAGE_PROFILE;
        $upload->ext = $file_ext;
        try {
            if (!$upload->save()) {
                ApiException::set(400);
            }
        } catch (\RuntimeException $e) {
            ApiException::set(400);
        }

        $filename = $upload->getPrimaryKey() . '.' . $file_ext;

        if (!move_uploaded_file($file['tmp_name'], $upload_dir . self::ORIGINAL_FOLDER . "/" . $filename)) {
            Yii::$app->response->headers->set('Error', 'File ' . $file['name'] . ' is not uploaded');
            
            return false;
        } else {
            $source_path = $upload_dir . self::ORIGINAL_FOLDER . "/" . $filename;
            $destination_path = $upload_dir . $filename;
            $new_width = 250;
            $this->imageResize($source_path, $destination_path, $new_width, false, 90);
            
            return true;
        }
    }

    /**
     * 
     * @return \stdClass
     */
    private function getUserData() {
        $data = Yii::$app->request->post();

        $user = new \stdClass();
        $user->id = $this->user->id;
        $user->type = User::TYPE_COMPANY;

        if ($this->user->type == User::TYPE_COMPANY && !empty($data['specialist_id'])) {
            $specialist = Specialist::find()
                    ->where(['id' => $data['specialist_id']])
                    ->one();
            if (!$specialist instanceof Specialist || $specialist->company_id != $this->user->id) {
                ApiException::set(400);
            }

            $user->id = $specialist->id;
            $user->type = User::TYPE_SPECIALIST;
        }

        return $user;
    }

    /**
     * 
     * @param \stdClass $user
     * @return string
     */
    private function getUploadDir($user) {
        $upload_dir = Yii::getAlias('@webroot') . Upload::getPath($user->type, $user->id);

        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777);
        }
        if (!is_dir($upload_dir . self::ORIGINAL_FOLDER)) {
            mkdir($upload_dir . self::ORIGINAL_FOLDER, 0777);
        }

        return $upload_dir;
    }

    /**
     * 
     * @param array $file
     * @return boolean
     */
    private function checkNoLimits($file) {
        $file_size = filesize($file['tmp_name']);
        if (in_array($this->getFileExt($file['name']), $this->image_extentions)) {
            if ($file_size < $this->max_size && $file_size > 0) {
                return true;
            } else {
                Yii::$app->response->headers->set('Error', 'Incorrect file size');
            }
        } else {
            Yii::$app->response->headers->set('Error', 'Incorrect file extention');
        }

        ApiException::set(400);
    }

    /**
     * 
     * @param string $filename
     * @return string
     */
    protected function getFileExt($filename) {
        $file_ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

        return $file_ext;
    }

    protected function imageResize($source_path, $destination_path, $newwidth, $newheight = false, $quality = false) {

        ini_set("gd.jpeg_ignore_warning", 1); 

        list($oldwidth, $oldheight, $type) = getimagesize($source_path);

        switch ($type) {
            case IMAGETYPE_JPEG: $typestr = 'jpeg';
                break;
            case IMAGETYPE_GIF: $typestr = 'gif';
                break;
            case IMAGETYPE_PNG: $typestr = 'png';
                break;
        }
        $function = "imagecreatefrom" . $typestr;
        $src_resource = $function($source_path);

        if (!$newheight) {
            $newheight = round($newwidth * $oldheight / $oldwidth);
        } elseif (!$newwidth) {
            $newwidth = round($newheight * $oldwidth / $oldheight);
        }
        $destination_resource = imagecreatetruecolor($newwidth, $newheight);

        imagecopyresampled($destination_resource, $src_resource, 0, 0, 0, 0, $newwidth, $newheight, $oldwidth, $oldheight);

        if ($type = 2) {
            imageinterlace($destination_resource, 1); 
            imagejpeg($destination_resource, $destination_path, $quality);
        } else {
            $function = "image" . $typestr;
            $function($destination_resource, $destination_path);
        }

        imagedestroy($destination_resource);
        imagedestroy($src_resource);
    }

}
