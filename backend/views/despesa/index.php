<?php

use yii\helpers\Html;
use yii\grid\GridView;
use backend\models\Fornecedor;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\DespesaSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Despesas';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="despesa-index">

    <p>
        <?= Html::a('Nova despesa', ['despesa/create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            
            [
                'attribute' => 'tipo_desp',
                'label' => 'Tipo',
                'value' => function($model) {
                    $t = $model->getTiposDespesa();
                    return isset($t) ? $t[$model->tipo_desp] : "-";
                }
            ],
            [
                'attribute' => 'id_fornecedor',
                'label' => 'Fornecedor',
                'value' => function($model) {
                    $f = Fornecedor::findOne($model->id_fornecedor);
                    return isset($f) ? $f->nome : "Fornecedor não cadastrado";
                }
            ],
            'numero_cheque',
            [
                'label' => 'Valor total',
                'value' => function($model){
                    return "R$" . ($model->valor_unitario * $model->qtde);
                }
            ],
            [
                'attribute' => 'status',
                'value' => function($model){
                    return $model->getStatus()[$model->status];
                }
            ],
            ['class' => 'yii\grid\ActionColumn'],
        ],
        'emptyText' => 'Nenhum resultado encontrado.',
        'showOnEmpty' => true,
    ]); ?>
</div>
