<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\Receita;

/**
 * ReceitaSearch represents the model behind the search form of `backend\models\Receita`.
 */
class ReceitaSearch extends Receita
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'id_projeto'], 'integer'],
            [['valor'], 'number'],
            [['data_cadastro', 'tipo'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
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
        $query = Receita::find();

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
            'valor' => $this->valor,
            'data_cadastro' => $this->data_cadastro,
            'id_projeto' => $this->id_projeto,
        ]);

        $query->andFilterWhere(['like', 'tipo', $this->tipo]);

        return $dataProvider;
    }
}
