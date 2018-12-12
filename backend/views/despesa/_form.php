<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\web\View;
use yii\jui\AutoComplete;
use yii\widgets\MaskedInput;

/* @var $this yii\web\View */
/* @var $despesaModel backend\models\Despesa */
/* @var $form yii\widgets\ActiveForm */

$script = <<< JS
    const TIPOS = {
        MATERIAL_SERVICO: [1, 2, 7],
        PASSAGEM_NACIONAL: 3,
        PASSAGEM_INTERNACIONAL: 4,
        DIARIA_NACIONAL: 5,
        DIARIA_INTERNACIONAL: 6
    };
    
    $(document).ready(function(){
        $('input').attr('autocomplete','off');
        toggleFields();
        $('#despesa-valor_unitario-disp').on("keyup", function(){
            let unitario = clearValue($('#despesa-valor_unitario-disp').val());
            let valorTotal = unitario * $('#despesa-qtde').val();
            $('#valor_total').val('R$' + valorTotal.toFixed(2));
        });
        $('#despesa-qtde').on("keyup", function(){  
            let unitario = clearValue($('#despesa-valor_unitario-disp').val());
            let valorTotal = unitario * $('#despesa-qtde').val();
            $('#valor_total').val('R$' + valorTotal.toFixed(2));
        });
        
        $('#despesa-tipo_desp').on("change", function(){
           toggleFields();
        });
    });
    
    function clearValue(val){
        return parseFloat(val.replace('R$', '').replace('.', '').replace(',', '.'));
    }
    
    function toggleFields() {
        let tipo = parseInt($('#despesa-tipo_desp').val());
        if (tipo === TIPOS.PASSAGEM_NACIONAL || tipo === TIPOS.PASSAGEM_INTERNACIONAL){
            $('.beneficiario-fields').show();
            $('.despesapassagem-fields').show();
            $('.despesadiaria-fields').hide();
        } else if (tipo === TIPOS.DIARIA_NACIONAL || tipo === TIPOS.DIARIA_INTERNACIONAL){
            $('.beneficiario-fields').show();
            $('.despesapassagem-fields').hide();
            $('.despesadiaria-fields').show();
        }  else {
            $('.beneficiario-fields').hide();
            $('.despesapassagem-fields').hide();
            $('.despesadiaria-fields').hide();
        }
    }
JS;
$this->registerJs($script, View::POS_READY);
?>


<div class="despesa-form">

    <?php
        $form = ActiveForm::begin();
        $hideTipos = false;
        if(!$despesaModel->isNewRecord){
            $hideTipos = true;
        }

    ?>

    <div class="row">
        <div class="col-xs-4">
            <?= $form->field($despesaModel, 'tipo_desp')->dropdownList($despesaModel->getTiposDespesa(), [
                    'prompt' => 'Selecione um tipo',
                    'disabled' => $hideTipos
                ]
            )?>
        </div>

        <!-- item com auto complete do campo descrição se existir-->
        <div class="col-xs-4">
            <?= $form->field($itemModel, 'numero_item')->textInput([
                'onkeyup' => '
                    if(!$(this).val()){
                        $("#item_alert").hide();
                    }else{
                        $.get( "'.Url::toRoute(['/despesa/getiteminfo']).'", { numero : $(this).val(), tipo: $("#despesa-tipo_desp").val() })
                        .done(function(item) {
                            if(item.id !== null){
                                $("#item-descricao").val(item.descricao ? item.descricao : "N/A");
                                $("#despesa-id_item").val(item.id);
                                $("#item_alert").hide();
                            }else{
                                $("#item_alert").show();
                                $("#item-descricao").val("");
                                $("#despesa-id_item").val(null);
                            }
                        });
                    }'
            ])->label('Item <span class="item-alert" id="item_alert">- Este item não existe.</span>') ?>
        </div>

        <div class="col-xs-4">
            <?= $form->field($itemModel, 'descricao')->textInput([
                'readonly' => true
            ])->label('Descricão item projeto') ?>
        </div>
    </div>

    <div class="row">
        <div class="col-xs-4">
            <label for="fornecedor-nome">Fornecedor</label>
            <?= AutoComplete::widget([
                'model' => $fornecedorModel,
                'attribute' => 'nome',
                'clientOptions' => [
                    'source' => $fornecedores,
                ],
                'options' => [
                    'class' => 'form-control',
                    'id' => 'fornecedor-nome',
                    'onblur' => '$.get( "'.Url::toRoute(['/despesa/getfornecedorinfo']).'", { nome : $(this).val() })
                                    .done(function(fornecedor) {
                                        if(fornecedor.id !== null){
                                            $("#fornecedor-cpf_cnpj").val(fornecedor.cpf_cnpj);
                                        }else{
                                            $("#despesa-id_fornecedor").val(null);
                                        }
                                });'
                ]
            ]); ?>

        </div>

        <div class="col-xs-4">
            <?= $form->field($fornecedorModel, 'cpf_cnpj')->widget(MaskedInput::className(), [
                'mask' => ['999.999.999-99', '99.999.999/9999-99'],
            ]) ?>
        </div>

        <div class="col-xs-4">
            <?= $form->field($despesaModel, 'numero_cheque')->textInput(['maxlength' => true]) ?>
        </div>
    </div>

    <div class="row">
        <div class="col-xs-4">
        <?= $form->field($despesaModel, 'data_pgto')->widget(MaskedInput::className(), [
            'clientOptions' => ['alias' =>  'date']
        ]) ?>
        </div>

        <div class="col-xs-4">
            <?= $form->field($despesaModel, 'nf_recibo')->textInput(['maxlength' => true]) ?>
        </div>

        <div class="col-xs-4">
            <?= $form->field($despesaModel, 'data_emissao_NF')->widget(MaskedInput::className(), [
            'clientOptions' => ['alias' =>  'date']
        ]) ?>
        </div>

        <div class="col-xs-4">
            <?= $form->field($despesaModel, 'valor_unitario')->widget(\kartik\money\MaskMoney::class, [
                    'pluginOptions' => [
                    'prefix' => 'R$',
                    'thousands' => '.',
                    'decimal' => ','
                ]
            ]) ?>
        </div>

        <div class="col-xs-4">
            <?= $form->field($despesaModel, 'qtde')->textInput() ?>
        </div>

        <div class="col-xs-4">
            <label for="valor_total">Valor total</label>
            <?= Html::textInput('valor_total', 'R$' .$despesaModel->valor_unitario * $despesaModel->qtde, [
                'class' => 'form-control', 
                'id' => 'valor_total',
                'readonly' => true,
                'autocomplete' => 'off'
            ]);?>
        </div>
    </div>

    <div class="row">
        <div class="col-xs-4">
            <?= $form->field($despesaModel, 'objetivo')->textInput() ?>
        </div>
        <div class="col-xs-4">
            <?= $form->field($despesaModel, 'pendencias')->textInput() ?>
        </div>
        <div class="col-xs-4">
            <?= $form->field($despesaModel, 'status')->dropdownList($despesaModel->getStatus()) ?>
        </div>
    </div>

    <div class="row beneficiario-fields" >
        <div class="col-xs-4 ">
            <?= $form->field($beneficiarioModel, 'nome')->textInput() ?>
        </div>
        <div class="col-xs-4 ">
            <?= $form->field($beneficiarioModel, 'rg')->textInput()?>
        </div>
        <div class="col-xs-4">
            <?= $form->field($beneficiarioModel, 'orgao_emissor')->textInput() ?>
        </div>
        <div class="col-xs-4">
            <?= $form->field($beneficiarioModel, 'nivel_academico')->textInput() ?>
        </div>
    </div>


    <!-- despesa passagem -->
    <div class="row despesapassagem-fields">
        <div class="col-xs-4 ">
            <?= $form->field($despesapassagemModel, 'data_hora_ida')->widget(MaskedInput::className(), [
                'clientOptions' => ['alias' =>  'datetime']
            ]) ?>
        </div>

        <div class="col-xs-4">
            <?= $form->field($despesapassagemModel, 'data_hora_volta')->widget(MaskedInput::className(), [
                'clientOptions' => ['alias' =>  'datetime']
            ]) ?>
        </div>

        <div class="col-xs-4 ">
            <?= $form->field($despesapassagemModel, 'destino')->textInput() ?>
        </div>

        <div class="col-xs-4 ">
            <?= $form->field($despesapassagemModel, 'localizador')->textInput() ?>
        </div>
    </div>

    <!-- despesa diaria -->
    <div class="row despesadiaria-fields">
        <div class="col-xs-4 ">
            <?= $form->field($despesadiariaModel, 'data_hora_ida')->widget(MaskedInput::className(), [
                'clientOptions' => ['alias' =>  'datetime']
            ]) ?>
        </div>

        <div class="col-xs-4 ">
            <?= $form->field($despesadiariaModel, 'data_hora_volta')->widget(MaskedInput::className(), [
                'clientOptions' => ['alias' =>  'datetime']
            ]) ?>
        </div>

        <div class="col-xs-4 ">
            <?= $form->field($despesadiariaModel, 'destino')->textInput() ?>
        </div>
    </div>

    <div class="row">
        <div class="col-xs-4">
            <?= $form->field($despesaModel, 'anexo')->fileInput() ?>
        </div>
    </div>

    <div class="form-group">
        <?= Html::Button('Cancelar', ['class' => 'btn btn-default', 'onclick' => 'history.go(-1)']) ?>
        <?= Html::submitButton('Salvar', ['class' => 'btn btn-success']) ?>
    </div>
    <?= $form->field($despesaModel, 'id_item')->hiddenInput()->label(false) ?>

    <?php ActiveForm::end(); ?>

</div>
