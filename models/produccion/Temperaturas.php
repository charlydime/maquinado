<?php

namespace frontend\models\produccion;

use Yii;

/**
 * This is the model class for table "Temperaturas".
 *
 * @property integer $IdTemperatura
 * @property integer $IdProduccion
 * @property integer $IdMaquina
 * @property string $Fecha
 * @property string $Temperatura
 *
 * @property Maquinas $idMaquina
 * @property Producciones $idProduccion
 */
class Temperaturas extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'Temperaturas';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['IdProduccion', 'IdMaquina', 'Fecha'], 'required'],
            [['IdProduccion', 'IdMaquina'], 'integer'],
            [['Fecha'], 'safe'],
            [['Temperatura'], 'number']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'IdTemperatura' => 'Id Temperatura',
            'IdProduccion' => 'Id Produccion',
            'IdMaquina' => 'Id Maquina',
            'Fecha' => 'Fecha',
            'Temperatura' => 'Temperatura',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdMaquina()
    {
        return $this->hasOne(Maquinas::className(), ['IdMaquina' => 'IdMaquina']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdProduccion()
    {
        return $this->hasOne(Producciones::className(), ['IdProduccion' => 'IdProduccion']);
    }
}
