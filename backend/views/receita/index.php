<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\ReceitaSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Receitas';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="receita-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Receita', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'valor',
            'data_cadastro',
            'tipo',
            'id_projeto',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
