<?php

namespace frontend\models\ete;

use Yii;

/**
 * This is the model class for table "periodicidad".
 *
 * @property integer $id
 * @property string $periodo
 * @property integer $hrs
 * @property integer $min_sem
 * @property integer $min_dia
 * @property string $lun
 * @property string $mar
 * @property string $mie
 * @property string $jue
 * @property string $vie
 * @property string $sab
 * @property string $dom
 */
class Periodicidad extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'periodicidad';
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
            [['periodo', 'hrs'], 'required'],
            [['periodo', 'lun', 'mar', 'mie', 'jue', 'vie', 'sab', 'dom'], 'string'],
            [['hrs', 'min_sem', 'min_dia'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'periodo' => 'Periodo',
            'hrs' => 'Hrs',
            'min_sem' => 'Min Sem',
            'min_dia' => 'Min Dia',
            'lun' => 'Lun',
            'mar' => 'Mar',
            'mie' => 'Mie',
            'jue' => 'Jue',
            'vie' => 'Vie',
            'sab' => 'Sab',
            'dom' => 'Dom',
        ];
    }
}
