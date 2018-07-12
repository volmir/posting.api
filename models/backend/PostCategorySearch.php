<?php

namespace app\models\backend;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\PostCategory;

/**
 * PostCategorySearch represents the model behind the search form of `app\models\PostCategory`.
 */
class PostCategorySearch extends PostCategory {

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['id', 'post_id', 'category_id'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios() {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params) {
        $query = PostCategory::find()
                ->with(['post', 'category']);

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => ['id' => SORT_DESC],
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'post_id' => $this->post_id,
            'category_id' => $this->category_id,
        ]);

        return $dataProvider;
    }

}
