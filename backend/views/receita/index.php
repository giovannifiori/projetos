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

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Nova receita', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

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

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
