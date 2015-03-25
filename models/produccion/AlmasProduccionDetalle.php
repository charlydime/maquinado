<?php

namespace frontend\models\produccion;

use Yii;

/**
 * This is the model class for table "AlmasProduccionDetalle".
 *
 * @property integer $IdAlmaProduccion
 * @property integer $IdProduccion
 * @property integer $IdProgramacionAlma
 * @property integer $IdAlma
 * @property string $Inicio
 * @property string $Fin
 * @property integer $Programadas
 * @property integer $Hechas
 * @property integer $Rechazadas
 *
 * @property Almas $idAlma
 * @property Producciones $idProduccion
 * @property ProgramacionesAlma $idProgramacionAlma
 * @property AlmasProduccionDefecto[] $almasProduccionDefectos
 */
class AlmasProduccionDetalle extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'AlmasProduccionDetalle';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['IdProduccion', 'IdProgramacionAlma', 'IdAlma', 'Inicio', 'Fin', 'Programadas', 'Hechas', 'Rechazadas'], 'required'],
            [['IdProduccion', 'IdProgramacionAlma', 'IdAlma', 'Programadas', 'Hechas', 'Rechazadas'], 'integer'],
            [['Inicio', 'Fin'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'IdAlmaProduccion' => 'Id Alma Produccion',
            'IdProduccion' => 'Id Produccion',
            'IdProgramacionAlma' => 'Id Programacion Alma',
            'IdAlma' => 'Id Alma',
            'Inicio' => 'Inicio',
            'Fin' => 'Fin',
            'Programadas' => 'Programadas',
            'Hechas' => 'Hechas',
            'Rechazadas' => 'Rechazadas',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdAlma()
    {
        return $this->hasOne(Almas::className(), ['IdAlma' => 'IdAlma']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdProduccion()
    {
        return $this->hasOne(Producciones::className(), ['IdProduccion' => 'IdProduccion']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdProgramacionAlma()
    {
        return $this->hasOne(ProgramacionesAlma::className(), ['IdProgramacionAlma' => 'IdProgramacionAlma']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAlmasProduccionDefectos()
    {
        return $this->hasMany(AlmasProduccionDefecto::className(), ['IdAlmaProduccionDetalle' => 'IdAlmaProduccion']);
    }
}
