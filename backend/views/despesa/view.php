<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use backend\models\Despesa;
use backend\models\DespesaPassagem;
use backend\models\DespesaDiaria;
use backend\models\Fornecedor;
use backend\models\Beneficiario;
use backend\models\Item;

/* @var $this yii\web\View */
/* @var $model backend\models\Despesa */

$this->title = "Despesa " . $despesaModel->id;
$this->params['breadcrumbs'][] = ['label' => 'Despesas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);

$viewAttributes = [
    'id',
    [
        'attribute' => 'valor_unitario',
        'value' => function($model){
            return "R$" . ($model->valor_unitario ? $model->valor_unitario : "0");
        }
    ],
    'qtde',
    [
        'label' => 'Valor total',
        'value' => function($model){
            return "R$" . ($model->valor_unitario * $model->qtde);
        }
    ],
    [
        'attribute' => 'tipo_desp',
        'label' => 'Tipo',
        'value' => function($model) {
            $t = $model->getTiposDespesa();
            return isset($t) ? $t[$model->tipo_desp] : "-";
        }
    ],
    [
        'attribute' => 'status',
        'value' => function($model){
            return $model->getStatus()[$model->status];
        }
    ],
    'data_emissao_NF',
    'pendencias:ntext',
    'numero_cheque',
    'data_pgto',
    'nf_recibo',
    'objetivo:ntext',
    [
        'attribute' => 'id_beneficiario',
        'label' => 'Beneficiário',
        'value' => function($model) {
            $b = Beneficiario::findOne($model->id_beneficiario);
            return isset($b) ? $b->nome . " - " . $b->rg : "Beneficiário não registrado";
        }
    ],
    [
        'attribute' => 'id_fornecedor',
        'label' => 'Fornecedor',
        'value' => function($model) {
            $f = Fornecedor::findOne($model->id_fornecedor);
            return isset($f) ? $f->nome . " - " . $f->cpf_cnpj : "Fornecedor não registrado";
        }
    ],
    [
        'attribute' => 'id_item',
        'label' => 'Item',
        'value' => function($model) {
            $item = Item::findOne($model->id_item);
            return isset($item) ? $item->descricao : "Item não registrado";
        }
    ],
];

if(isset($despesapassagemModel) && !empty($despesapassagemModel)){
    array_push($viewAttributes, [
        'label' => 'Data/Hora de ida',
        'value' => function($model){
            return DespesaPassagem::find()->where(['id_despesa' => $model->id])->one()->data_hora_ida;
        }
    ], [
        'label' => 'Data/Hora de volta',
        'value' => function($model){
            return DespesaPassagem::find()->where(['id_despesa' => $model->id])->one()->data_hora_volta;
        }
    ], [
        'label' => 'Localizador',
        'value' => function($model){
            return DespesaPassagem::find()->where(['id_despesa' => $model->id])->one()->localizador;
        }
    ], [
        'label' => 'Destino',
        'value' => function($model){
            return DespesaPassagem::find()->where(['id_despesa' => $model->id])->one()->destino;
        }
    ]);
}elseif (isset($despesadiariaModel) && !empty($despesadiariaModel)){
    array_push($viewAttributes, [
            'label' => 'Data/Hora de ida',
            'value' => function($model){
                return DespesaDiaria::find()->where(['id_despesa' => $model->id])->one()->data_hora_ida;
            }
        ], [
            'label' => 'Data/Hora de volta',
            'value' => function($model){
                return DespesaDiaria::find()->where(['id_despesa' => $model->id])->one()->data_hora_volta;
            }
        ], [
            'label' => 'Destino',
            'value' => function($model){
                return DespesaDiaria::find()->where(['id_despesa' => $model->id])->one()->destino;
            }
        ]);
}

?>
<div class="despesa-view">

    <p>
        <?= Html::Button('Voltar', ['class' => 'btn btn-warning', 'onclick' => 'history.go(-1)']) ?>
        <?= Html::a('Alterar', ['update', 'id' => $despesaModel->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Excluir', ['delete', 'id' => $despesaModel->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Deseja realmente excluir este item?',
                'method' => 'POST',
            ]
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $despesaModel,
        'attributes' => $viewAttributes,
    ]) ?>

</div>
