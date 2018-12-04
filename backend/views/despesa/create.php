<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\Despesa */

$this->title = 'Nova despesa';
$this->params['breadcrumbs'][] = ['label' => 'Despesas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="despesa-create">

    <?= $this->render('_form', [
        'despesaModel' => $despesaModel,
        'fornecedorModel' => $fornecedorModel,
        'beneficiarioModel' => $beneficiarioModel,
        'itemModel' => $itemModel,
        'fornecedores' => $fornecedores,
        'despesapassagemModel' => $despesapassagemModel,
        'despesadiariaModel'  => $despesadiariaModel,
    ]) ?>

</div>
