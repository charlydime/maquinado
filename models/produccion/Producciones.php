<?php

namespace frontend\models\produccion;

use Yii;

/**
 * This is the model class for table "Producciones".
 *
 * @property integer $IdProduccion
 * @property integer $IdCentroTrabajo
 * @property integer $IdMaquina
 * @property integer $IdEmpleado
 * @property integer $IdProduccionEstatus
 * @property string $Fecha
 * @property integer $IdProceso
 *
 * @property Empleados $idEmpleado
 * @property CentrosTrabajo $idCentroTrabajo
 * @property Maquinas $idMaquina
 * @property Procesos $idProceso
 * @property ProduccionesEstatus $idProduccionEstatus
 * @property ProduccionesDetalle[] $produccionesDetalles
 * @property TiemposMuerto[] $tiemposMuertos
 * @property Temperaturas[] $temperaturas
 * @property MaterialesVaciado[] $materialesVaciados
 * @property Vaciados[] $vaciados
 * @property AlmasProduccionDetalle[] $almasProduccionDetalles
 */
class Producciones extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'Producciones';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['IdCentroTrabajo', 'IdMaquina', 'IdEmpleado', 'IdProduccionEstatus', 'IdProceso'], 'required'],
            [['IdCentroTrabajo', 'IdMaquina', 'IdEmpleado', 'IdProduccionEstatus', 'IdProceso'], 'integer'],
            [['Fecha'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'IdProduccion' => 'Id Produccion',
            'IdCentroTrabajo' => 'Id Centro Trabajo',
            'IdMaquina' => 'Id Maquina',
            'IdEmpleado' => 'Id Empleado',
            'IdProduccionEstatus' => 'Id Produccion Estatus',
            'Fecha' => 'Fecha',
            'IdProceso' => 'Id Proceso',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdEmpleado()
    {
        return $this->hasOne(Empleados::className(), ['IdEmpleado' => 'IdEmpleado']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdCentroTrabajo()
    {
        return $this->hasOne(CentrosTrabajo::className(), ['IdCentroTrabajo' => 'IdCentroTrabajo']);
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
    public function getIdProceso()
    {
        return $this->hasOne(Procesos::className(), ['IdProceso' => 'IdProceso']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdProduccionEstatus()
    {
        return $this->hasOne(ProduccionesEstatus::className(), ['IdProduccionEstatus' => 'IdProduccionEstatus']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProduccionesDetalles()
    {
        return $this->hasMany(ProduccionesDetalle::className(), ['IdProduccion' => 'IdProduccion']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTiemposMuertos()
    {
        return $this->hasMany(TiemposMuerto::className(), ['IdProduccion' => 'IdProduccion']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTemperaturas()
    {
        return $this->hasMany(Temperaturas::className(), ['IdProduccion' => 'IdProduccion']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMaterialesVaciados()
    {
        return $this->hasMany(MaterialesVaciado::className(), ['IdProduccion' => 'IdProduccion']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVaciados()
    {
        return $this->hasMany(Vaciados::className(), ['IdProduccion' => 'IdProduccion']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAlmasProduccionDetalles()
    {
        return $this->hasMany(AlmasProduccionDetalle::className(), ['IdProduccion' => 'IdProduccion']);
    }
}
