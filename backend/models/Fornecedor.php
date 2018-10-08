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
            [['nome'], 'string', 'max' => 120],
            [['cpf_cnpj'], 'string', 'max' => 14],
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
}
