<?php

namespace frontend\models\maquinado;

use Yii;

/**
 * This is the model class for table "maquina_operador".
 *
 * @property integer $operador
 * @property string $maquina
 */
class MaquinaOperador extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'maquina_operador';
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
            [['operador'], 'integer'],
            [['maquina'], 'string']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'operador' => 'Operador',
            'maquina' => 'Maquina',
        ];
    }
}
