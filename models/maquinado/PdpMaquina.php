<?php

namespace frontend\models\maquinado;

use Yii;

/**
 * This is the model class for table "pdp_maquina".
 *
 * @property string $Maquina
 * @property string $Descripcion
 * @property double $TiempoDisponible
 * @property string $Area
 * @property string $activa
 * @property integer $id
 */
class pdpmaquina extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'pdp_maquina';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('db_mysql');
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['Maquina', 'Descripcion', 'Area', 'activa'], 'string'],
            [['TiempoDisponible'], 'number']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'Maquina' => 'Maquina',
            'Descripcion' => 'Descripcion',
            'TiempoDisponible' => 'Tiempo Disponible',
            'Area' => 'Area',
            'activa' => 'Activa',
            'id' => 'ID',
        ];
    }
}
