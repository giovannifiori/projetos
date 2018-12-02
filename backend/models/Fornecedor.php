<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "fornecedor".
 *
 * @property int $id
 * @property string $nome
 * @property string $cpf_cnpj
 */
class Fornecedor extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'fornecedor';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['nome'], 'string', 'max' => 200],
            [['cpf_cnpj'], 'string', 'max' => 30],
            [['cpf_cnpj', 'nome'], 'unique', 'message' => 'Fornecedor já cadastrado']
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'nome' => 'Nome',
            'cpf_cnpj' => 'CPF/CNPJ',
        ];
    }

    public function beforeSave($insert){
        if(!parent::beforeSave($insert)){
            return false;
        }
        if(empty($this->nome) || empty($this->cpf_cnpj)){
            return false;
        }
        return true;
    }
}
