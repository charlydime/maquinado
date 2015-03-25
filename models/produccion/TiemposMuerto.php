<?php

namespace frontend\models\produccion;

use Yii;
use common\models\datos\Causas;

/**
 * This is the model class for table "TiemposMuerto".
 *
 * @property integer $IdTiempoMuerto
 * @property integer $IdProduccion
 * @property integer $IdCausa
 * @property string $Inicio
 * @property string $Fin
 * @property string $Descripcion
 *
 * @property Causas $idCausa
 * @property Producciones $idProduccion
 */
class TiemposMuerto extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'TiemposMuerto';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['IdProduccion', 'IdCausa', 'Inicio', 'Fin'], 'required'],
            [['IdProduccion', 'IdCausa'], 'integer'],
            [['Inicio', 'Fin'], 'safe'],
            [['Descripcion'], 'string']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'IdTiempoMuerto' => 'Id Tiempo Muerto',
            'IdProduccion' => 'Id Produccion',
            'IdCausa' => 'Causa',
            'Inicio' => 'Inicio',
            'Fin' => 'Fin',
            'Descripcion' => 'Descripcion',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdCausa()
    {
        return $this->hasOne(Causas::className(), ['IdCausa' => 'IdCausa']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdProduccion()
    {
        return $this->hasOne(Producciones::className(), ['IdProduccion' => 'IdProduccion']);
    }
}
