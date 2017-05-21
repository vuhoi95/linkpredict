<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\Authortemp;

/**
 * AuthortempSearch represents the model behind the search form about `backend\models\Authortemp`.
 */
class AuthortempSearch extends Authortemp
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_author'], 'integer'],
            [['author'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
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
    public function search($params)
    {
        $query = Authortemp::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id_author' => $this->id_author,
        ]);

        $query->andFilterWhere(['like', 'author', $this->author]);

        return $dataProvider;
    }
}
