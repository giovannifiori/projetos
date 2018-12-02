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
            [['data_cadastro'], 'date', 'format' => 'dd/mm/yyyy'],
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
            0 => 'Recurso',
            1 => 'Rendimento'
        ];
    }

    public function beforeSave($insert){
        if(!parent::beforeSave($insert)){
            return false;
        }
        if($this->data_cadastro != NULL){
            $this->data_cadastro = \DateTime::createFromFormat('d/m/Y', $this->data_cadastro)->format('Y-m-d');
        }
        
        return true;
      }

    public function afterFind(){
        parent::afterFind();
        if($this->data_cadastro != NULL){
            $this->data_cadastro = date('d/m/Y', strtotime($this->data_cadastro));
        }
        return true;
    }
}
