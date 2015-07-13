<?php

namespace frontend\models\maquinado;

use Yii;

/**
 * This is the model class for table "turnos".
 *
 * @property integer $idturno
 * @property string $Turno
 * @property string $hinicio
 * @property string $htermino
 * @property string $area
 * @property string $ncorto
 */
class Turnos extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'turnos';
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
            [['Turno', 'area', 'ncorto'], 'string'],
            [['hinicio', 'htermino'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'idturno' => 'Idturno',
            'Turno' => 'Turno',
            'hinicio' => 'Hinicio',
            'htermino' => 'Htermino',
            'area' => 'Area',
            'ncorto' => 'Ncorto',
        ];
    }
}
