<?php

namespace frontend\models\programacion;

use Yii;

/**
 * This is the model class for table "ProgramacionesAlmaDia".
 *
 * @property integer $IdProgramacionAlmaDia
 * @property integer $IdProgramacionAlmaSemana
 * @property string $Dia
 * @property integer $Prioridad
 * @property integer $Programadas
 * @property integer $Hechas
 *
 * @property ProgramacionesAlmaSemana $idProgramacionAlmaSemana
 */
class ProgramacionesAlmaDia extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ProgramacionesAlmaDia';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['IdProgramacionAlmaSemana', 'Dia', 'Programadas'], 'required'],
            [['IdProgramacionAlmaSemana', 'Prioridad', 'Programadas', 'Hechas'], 'integer'],
            [['Dia'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'IdProgramacionAlmaDia' => 'Id Programacion Alma Dia',
            'IdProgramacionAlmaSemana' => 'Id Programacion Alma Semana',
            'Dia' => 'Dia',
            'Prioridad' => 'Prioridad',
            'Programadas' => 'Programadas',
            'Hechas' => 'Hechas',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdProgramacionAlmaSemana()
    {
        return $this->hasOne(ProgramacionesAlmaSemana::className(), ['IdProgramacionAlmaSemana' => 'IdProgramacionAlmaSemana']);
    }
}
