<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\Receita */

$this->title = 'Receita '.$model->id;
$this->params['breadcrumbs'][] = ['label' => 'Receitas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="receita-view">

    <p>
        <?= Html::Button('Voltar', ['class' => 'btn btn-warning', 'onclick' => 'history.go(-1)']) ?>
        <?= Html::a('Alterar', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Excluir', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Deseja realmente excluir este item?',
                'method' => 'POST',
            ]
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            [
                'attribute' => 'valor',
                'format' => [
                    'currency',
                    'BRL'
                ]
            ],
            'data_cadastro',
            [
                'attribute' => 'tipo',
                'value' => function($model) {
                    $tipos = $model->getTipos();
                    return $tipos[$model->tipo];
                }
            ],
        ],
    ]) ?>

</div>
