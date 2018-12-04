<?php

namespace backend\models;

use Yii;
/**
 * Created by PhpStorm.
 * User: mouhamadou
 * Date: 17/11/18
 * Time: 22:24
 */

/**
 * This is the model class for table "despesa_passagem".
 *
 * @property int $id_despesa
 * @property string $data_hora_ida
 * @property string $data_hora_volta
 * @property string $destino
 * @property string $localizador

 * @property Despesa[] $despesas
 */
class DespesaPassagem extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'despesa_passagem';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['data_hora_ida'], 'safe'],
            [['id_despesa'], 'integer'],
            [['data_hora_volta'], 'safe'],
            [['destino'], 'string', 'max' => 200],
            [['localizador'], 'string', 'max'=> 50],
            [['data_hora_volta'], 'compare', 'compareAttribute'=>'data_hora_ida', 'operator'=>'>', 'message'=>'A data de volta deve ser posterior a data de ida.'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'data_hora_ida' => 'Data e Hora da Ida',
            'data_hora_volta' => 'Data e Hora da Volta',
            'destino' => 'Destino',
            'localizador' => 'Localizador',
            'id_despesa' => 'Id. Despesa',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */

    public function getDespesa()
    {
        return $this->hasOne(Despesa::className(), ['id' => 'id_despesa']);
    }

    public function beforeSave($insert){
        if(!parent::beforeSave($insert)){
            return false;
        }
        if($this->data_hora_ida != NULL){
            $this->data_hora_ida = \DateTime::createFromFormat('d/m/Y H:i', $this->data_hora_ida)->format('Y-m-d H:i');
        }
        if($this->data_hora_volta != NULL){
            $this->data_hora_volta = \DateTime::createFromFormat('d/m/Y H:i', $this->data_hora_volta)->format('Y-m-d H:i');
        }
        
        return true;
      }

    public function afterFind(){
        parent::afterFind();
        if($this->data_hora_ida != NULL){
            $this->data_hora_ida = date('d/m/Y H:i', strtotime($this->data_hora_ida));
        }
        if($this->data_hora_volta != NULL){
            $this->data_hora_volta = date('d/m/Y H:i', strtotime($this->data_hora_volta));
        }
        return true;
    }
}
