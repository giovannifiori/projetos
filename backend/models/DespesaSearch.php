<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\Despesa;

/**
 * DespesaSearch represents the model behind the search form of `backend\models\Despesa`.
 */
class DespesaSearch extends Despesa
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'qtde', 'tipo_desp', 'id_beneficiario', 'id_fornecedor', 'id_item'], 'integer'],
            [['valor_unitario'], 'number'],
            [['status', 'data_emissao_NF', 'pendencias', 'numero_cheque', 'data_pgto', 'nf_recibo', 'objetivo'], 'safe'],
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
        $query = Despesa::find();

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
            'valor_unitario' => $this->valor_unitario,
            'qtde' => $this->qtde,
            'tipo_desp' => $this->tipo_desp,
            'data_emissao_NF' => $this->data_emissao_NF,
            'data_pgto' => $this->data_pgto,
            'id_beneficiario' => $this->id_beneficiario,
            'id_fornecedor' => $this->id_fornecedor,
            'id_item' => $this->id_item,
        ]);

        $query->andFilterWhere(['like', 'status', $this->status])
            ->andFilterWhere(['like', 'pendencias', $this->pendencias])
            ->andFilterWhere(['like', 'numero_cheque', $this->numero_cheque])
            ->andFilterWhere(['like', 'nf_recibo', $this->nf_recibo])
            ->andFilterWhere(['like', 'objetivo', $this->objetivo])
            ->orderBy('id DESC');

        return $dataProvider;
    }
}
