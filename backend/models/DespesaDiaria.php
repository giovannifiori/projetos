<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "despesa_diaria".
 *
 * @property int $id_despesa
 * @property string $data_hora_ida
 * @property string $data_hora_volta
 * @property string $destino
 * @property string $localizador
 * @property Despesa[] $despesas
 */

class DespesaDiaria extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'despesa_diaria';
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
           // [['id_despesa'], 'exist', 'skipOnError' => true, 'targetClass' => Despesa::className(), 'targetAttribute' => ['id_despesa' => 'id']],
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
}