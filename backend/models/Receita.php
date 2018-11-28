<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "receita".
 *
 * @property int $id
 * @property double $valor
 * @property string $data_cadastro
 * @property string $tipo
 * @property int $id_projeto
 *
 * @property Projeto $projeto
 */
class Receita extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'receita';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['valor'], 'number'],
            [['data_cadastro'], 'safe'],
            [['id_projeto'], 'integer'],
            [['tipo'], 'string', 'max' => 30],
            // [['id_projeto'], 'exist', 'skipOnError' => true, 'targetClass' => Projeto::className(), 'targetAttribute' => ['id_projeto' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'Id',
            'valor' => 'Valor',
            'data_cadastro' => 'Data de cadastro',
            'tipo' => 'Tipo',
            'id_projeto' => 'Projeto',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    // public function getProjeto()
    // {
    //     return $this->hasOne(Projeto::className(), ['id' => 'id_projeto']);
    // }

    public function getTipos(){
        return [
            'recurso' => 'Recurso',
            'rendimento' => 'Rendimento'
        ];
    }
}
