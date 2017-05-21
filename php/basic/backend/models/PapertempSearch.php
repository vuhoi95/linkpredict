<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\Papertemp;

/**
 * PapertempSearch represents the model behind the search form about `backend\models\Papertemp`.
 */
class PapertempSearch extends Papertemp
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_paper', 'year'], 'integer'],
            [['paper', 'abstracts', 'title', 'authors'], 'safe'],
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
        $query = Papertemp::find();

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
            'id_paper' => $this->id_paper,
            'year' => $this->year,
        ]);

        $query->andFilterWhere(['like', 'paper', $this->paper])
            ->andFilterWhere(['like', 'abstracts', $this->abstracts])
            ->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'authors', $this->authors]);

        return $dataProvider;
    }
}
