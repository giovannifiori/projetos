<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\Fornecedor */

$this->title = 'Novo fornecedor';
$this->params['breadcrumbs'][] = ['label' => 'Fornecedores', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="fornecedor-create">


    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
