<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\Receita */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="receita-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'valor')->textInput() ?>

    <?= $form->field($model, 'data_cadastro')->textInput() ?>

    <?= $form->field($model, 'tipo')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'id_projeto')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
