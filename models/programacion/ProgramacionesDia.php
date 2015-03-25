<?php

namespace frontend\models\programacion;

use Yii;

/**
 * This is the model class for table "ProgramacionesDia".
 *
 * @property integer $IdProgramacionDia
 * @property integer $IdProgramacionSemana
 * @property string $Dia
 * @property integer $Prioridad
 * @property integer $Programadas
 * @property integer $Hechas
 * @property integer $IdProceso
 * @property integer $IdTurno
 *
 * @property Procesos $idProceso
 * @property ProgramacionesSemana $idProgramacionSemana
 * @property Turnos $idTurno
 */
class ProgramacionesDia extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ProgramacionesDia';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['IdProgramacionSemana', 'Dia', 'Programadas', 'IdProceso', 'IdTurno'], 'required'],
            [['IdProgramacionSemana', 'Prioridad', 'Programadas', 'Hechas', 'IdProceso', 'IdTurno'], 'integer'],
            [['Dia'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'IdProgramacionDia' => 'Id Programacion Dia',
            'IdProgramacionSemana' => 'Id Programacion Semana',
            'Dia' => 'Dia',
            'Prioridad' => 'Prioridad',
            'Programadas' => 'Programadas',
            'Hechas' => 'Hechas',
            'IdProceso' => 'Id Proceso',
            'IdTurno' => 'Id Turno',
        ];
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
    public function getIdProgramacionSemana()
    {
        return $this->hasOne(ProgramacionesSemana::className(), ['IdProgramacionSemana' => 'IdProgramacionSemana']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdTurno()
    {
        return $this->hasOne(Turnos::className(), ['IdTurno' => 'IdTurno']);
    }
}
