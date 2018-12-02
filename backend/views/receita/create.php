<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\Receita */

$this->title = 'Nova receita';
$this->params['breadcrumbs'][] = ['label' => 'Receitas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="receita-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
