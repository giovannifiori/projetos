<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\MaskedInput;

/* @var $this yii\web\View */
/* @var $model backend\models\Receita */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="receita-form">

    <?php 
        $form = ActiveForm::begin(); 
        $model->id_projeto = 1;
    ?>

    <div class="row">
        <div class="col-md-3">
            <?= $form->field($model, 'valor')->widget(\kartik\money\MaskMoney::class, [
                'pluginOptions' => [
                    'prefix' => 'R$',
                    'thousands' => '.',
                    'decimal' => ','
                ]
            ]) ?>
        </div>
        <div class="col-md-3">
            <?= $form->field($model, 'data_cadastro')->widget(MaskedInput::classname(), [
                'clientOptions' => [
                    'alias' =>  'dd/mm/yyyy'
                ]
            ])?>
        </div>
        <div class="col-md-3">
            <?= $form->field($model, 'tipo')->dropdownList($model->getTipos()) ?>
        </div>
    </div>

    <div class="form-group">
        <?= Html::Button('Cancelar', ['class' => 'btn btn-default', 'onclick' => 'history.go(-1)']) ?>
        <?= Html::submitButton('Salvar', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
