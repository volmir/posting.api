<?php

namespace app\modules\api\v1\models;

use Yii;

/**
 * This is the model class for table "posts_vs_categories".
 *
 * @property int $id
 * @property int $post_id
 * @property int $category_id
 */
class PostCategory extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'post_vs_category';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'post_id', 'category_id'], 'required'],
            [['id', 'post_id', 'category_id'], 'integer'],
            [['post_id', 'category_id'], 'unique', 'targetAttribute' => ['post_id', 'category_id']],
            [['id'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'post_id' => 'Post ID',
            'category_id' => 'Category ID',
        ];
    }
}
