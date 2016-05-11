<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\Entity;

/**
 * EntitySearch represents the model behind the search form about `backend\models\Entity`.
 */
class EntitySearch extends Entity
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'updated_at', 'created_at'], 'integer'],
            [['name', 'name_short', 'country', 'county', 'city', 'street', 'zip_code'], 'safe'],
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
        $query = Entity::find();

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
            'id' => $this->id,
            'updated_at' => $this->updated_at,
            'created_at' => $this->created_at,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'name_short', $this->name_short])
            ->andFilterWhere(['like', 'country', $this->country])
            ->andFilterWhere(['like', 'county', $this->county])
            ->andFilterWhere(['like', 'city', $this->city])
            ->andFilterWhere(['like', 'street', $this->street])
            ->andFilterWhere(['like', 'zip_code', $this->zip_code]);

        return $dataProvider;
    }
}
