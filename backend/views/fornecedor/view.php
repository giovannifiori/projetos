<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\Fornecedor */

$this->title = $model->nome;
$this->params['breadcrumbs'][] = ['label' => 'Fornecedores', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="fornecedor-view">

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
            'nome',
            'cpf_cnpj',
        ],
    ]) ?>

</div>
