<?php

namespace frontend\models\ete;

use Yii;

/**
 * This is the model class for table "reportes".
 *
 * @property integer $idreporte
 * @property integer $nomina
 * @property string $descripcion
 */
class Reportes extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'reportes';
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
            
            [[ 'nomina'], 'integer'],
            [['descripcion'], 'string']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'idreporte' => 'Idreporte',
            'nomina' => 'Nomina',
            'descripcion' => 'Descripcion',
        ];
    }
}
