<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use frontend\models\Drawing;

/**
 * DrawingSearch represents the model behind the search form about `frontend\models\Drawing`.
 */
class DrawingSearch extends Drawing
{
    /**
     * @inheritdoc
     */
    public $globalSearch;

    public function rules()
    {
        return [
            [['id', 'entity_id', 'date', 'doc_pdmweid', 'conf_name', 'conf_quantity', 'active'], 'integer'],
            [['type', 'vaultname', 'doc_aliasset', 'drawing_number', 'description1', 'description2', 'description3', 'noa', 'revision', 'item_name', 'creator', 'approver', 'product_responsible', 'state', 'xml_file_name', 'pdf_path', 'pdf_contents', 'pdf_contents_lc', 'pdf_contents_uc', 'xml_file_created', 'creation_date', 'approval_date', 'created', 'changed', 'globalSearch'], 'safe'],
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
        $query = Drawing::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
                'pagination' => [
                'pageSize' => 100,
            ],
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_ASC,
                ]
            ],            
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
/*        $query->andFilterWhere([
            'id' => $this->id,
            'entity_id' => $this->entity_id,
            'date' => $this->date,
            'doc_pdmweid' => $this->doc_pdmweid,
            'conf_name' => $this->conf_name,
            'conf_quantity' => $this->conf_quantity,
            'xml_file_created' => $this->xml_file_created,
            'creation_date' => $this->creation_date,
            'approval_date' => $this->approval_date,
            'created' => $this->created,
            'changed' => $this->changed,
        ]);
*/



        if ($this->globalSearch != FALSE  and $this->globalSearch != '') {

            $query->orFilterWhere(['like', 'type', $this->globalSearch])
                ->orFilterWhere(['like', 'vaultname', $this->globalSearch])
                ->orFilterWhere(['like', 'doc_aliasset', $this->globalSearch])
                ->orFilterWhere(['like', 'product_responsible', $this->globalSearch])
                ->orFilterWhere(['like', 'state', $this->globalSearch])
                ->orFilterWhere(['like', 'xml_file_name', $this->globalSearch])
                ->orFilterWhere(['like', 'pdf_contents', $this->globalSearch])
                ->orFilterWhere(['like', 'pdf_contents_lc', strtolower($this->globalSearch)])
                ->orFilterWhere(['like', 'pdf_contents_uc', strtoupper($this->globalSearch)])
                ->orFilterWhere(['like', 'description1', $this->globalSearch])
                ->orFilterWhere(['like', 'description2', $this->globalSearch])
                ->orFilterWhere(['like', 'description3', $this->globalSearch]);                


        }
        else {

        
           $query->andFilterWhere(['like', 'type', $this->type])
               ->andFilterWhere(['like', 'vaultname', $this->vaultname])
               ->andFilterWhere(['like', 'doc_aliasset', $this->doc_aliasset])
               ->andFilterWhere(['like', 'drawing_number', $this->drawing_number])
               ->andFilterWhere(['like', 'description1', $this->description1])
               ->andFilterWhere(['like', 'description2', $this->description2])
               ->andFilterWhere(['like', 'description3', $this->description3])
               ->andFilterWhere(['like', 'noa', $this->noa])
               ->andFilterWhere(['like', 'revision', $this->revision])
               ->andFilterWhere(['like', 'item_name', $this->item_name])
               ->andFilterWhere(['like', 'creator', $this->creator])
               ->andFilterWhere(['like', 'approver', $this->approver])
               ->andFilterWhere(['like', 'product_responsible', $this->product_responsible])
               ->andFilterWhere(['like', 'state', $this->state])
               ->andFilterWhere(['like', 'xml_file_name', $this->xml_file_name])
               ->andFilterWhere(['like', 'pdf_path', $this->pdf_path])
               ->andFilterWhere(['like', 'pdf_contents', $this->pdf_contents])
               ->andFilterWhere(['like', 'pdf_contents_lc', $this->pdf_contents_lc])
               ->andFilterWhere(['like', 'pdf_contents_uc', $this->pdf_contents_uc]);
        }



        return $dataProvider;
    }
}
