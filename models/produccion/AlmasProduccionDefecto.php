<?php

namespace frontend\models\produccion;

use Yii;

/**
 * This is the model class for table "AlmasProduccionDefecto".
 *
 * @property integer $IdAlmaProduccionDefecto
 * @property integer $IdAlmaProduccionDetalle
 * @property integer $IdDefecto
 * @property integer $Rechazada
 *
 * @property AlmasProduccionDetalle $idAlmaProduccionDetalle
 * @property Defectos $idDefecto
 */
class AlmasProduccionDefecto extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'AlmasProduccionDefecto';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['IdAlmaProduccionDetalle', 'IdDefecto'], 'required'],
            [['IdAlmaProduccionDetalle', 'IdDefecto', 'Rechazada'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'IdAlmaProduccionDefecto' => 'Id Alma Produccion Defecto',
            'IdAlmaProduccionDetalle' => 'Id Alma Produccion Detalle',
            'IdDefecto' => 'Id Defecto',
            'Rechazada' => 'Rechazada',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdAlmaProduccionDetalle()
    {
        return $this->hasOne(AlmasProduccionDetalle::className(), ['IdAlmaProduccion' => 'IdAlmaProduccionDetalle']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdDefecto()
    {
        return $this->hasOne(Defectos::className(), ['IdDefecto' => 'IdDefecto']);
    }
}
