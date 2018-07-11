<?php

namespace app\widgets;

use Yii;

class Catalog extends \yii\bootstrap\Widget {

    public $data;
    /**
     *
     * @var type 
     */
    public $cats = [];
    /**
     *
     * @var type 
     */
    public $parent_id = 0;

    public function init() {
        parent::init();
    }

    public function run() {
        $this->restructurizeData();
        
        return $this->render('catalog', [
            'cats' => $this->cats,
            'parent_id' => $this->parent_id,
        ]);
    }

    public function restructurizeData() {
        if ($this->data) {
            foreach ($this->data as $data) {
                $this->cats[$data->parent_id][$data->id] = $data;
            }
        }
    }
    
}
