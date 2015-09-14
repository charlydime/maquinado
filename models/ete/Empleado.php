<?php

namespace frontend\models\ete;

use Yii;

/**
 * This is the model class for table "Empleado".
 *
 * @property integer $nomina
 * @property integer $idEmpleado
 * @property integer $idturno
 * @property string $fecha
 * @property string $area
 * @property string $periodicidad
 *
 * @property Turnos $idturno0
 */
class Empleado extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'Empleado';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('db_ete');
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['idEmpleado'], 'required'],
            [['idEmpleado', 'idturno'], 'integer'],
            [['fecha'], 'safe'],
            [['area', 'periodicidad'], 'string']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'nomina' => 'Nomina',
            'idEmpleado' => 'Id Empleado',
            'idturno' => 'Idturno',
            'fecha' => 'Fecha',
            'area' => 'Area',
            'periodicidad' => 'Periodicidad',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdturno0()
    {
        return $this->hasOne(Turnos::className(), ['idturno' => 'idturno']);
    }
}
