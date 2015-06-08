<?php

namespace frontend\models\maquinado;

use Yii;

/**
 * This is the model class for table "pdp_maquinado_bl".
 *
 * @property string $pieza
 * @property string $fecha
 */
class PdpMaquinaBl extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'pdp_maquinado_bl';
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
            [['pieza'], 'string'],
            [['fecha'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'pieza' => 'Pieza',
            'fecha' => 'Fecha',
        ];
    }
}
