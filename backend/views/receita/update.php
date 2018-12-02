<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\Receita */

$this->title = 'Editar receita: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Receitas', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="receita-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
