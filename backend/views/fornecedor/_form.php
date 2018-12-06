<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\MaskedInput;
use yii\web\View;

/* @var $this yii\web\View */
/* @var $model backend\models\Fornecedor */
/* @var $form yii\widgets\ActiveForm */

$script = <<< JS
    $(document).ready(function(){
        $('input').attr('autocomplete','off');
    });
JS;
$this->registerJs($script, View::POS_READY);

?>

<div class="fornecedor-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="col-md-6">
        <?= $form->field($model, 'nome')->textInput(['maxlength' => true]) ?>
        <?= $form->field($model, 'cpf_cnpj')->widget(MaskedInput::className(), [
            'mask' => ['999.999.999-99', '99.999.999/9999-99'],
        ]) ?>
        <div class="form-group">
            <?= Html::Button('Cancelar', ['class' => 'btn btn-default', 'onclick' => 'history.go(-1)']) ?>
            <?= Html::submitButton('Salvar', ['class' => 'btn btn-success']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>
