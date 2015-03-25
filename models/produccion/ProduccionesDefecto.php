<?php

namespace frontend\models\produccion;

use Yii;
use yii\data\ArrayDataProvider;
use common\models\catalogos\Defectos;

/**
 * This is the model class for table "ProduccionesDefecto".
 *
 * @property integer $IdProduccionDefecto
 * @property integer $IdProduccionDetalle
 * @property integer $IdDefecto
 * @property integer $Rechazadas
 *
 * @property Defectos $idDefecto
 * @property ProduccionesDetalle $idProduccionDetalle
 */
class ProduccionesDefecto extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ProduccionesDefecto';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['IdProduccionDetalle', 'IdDefecto'], 'required'],
            [['IdProduccionDetalle', 'IdDefecto', 'Rechazadas'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'IdProduccionDefecto' => 'Id Produccion Defecto',
            'IdProduccionDetalle' => 'Id Produccion Detalle',
            'IdDefecto' => 'Id Defecto',
            'Rechazadas' => 'Rechazadas',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdDefecto()
    {
        return $this->hasOne(Defectos::className(), ['IdDefecto' => 'IdDefecto']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdProduccionDetalle()
    {
        return $this->hasOne(ProduccionesDetalle::className(), ['IdProduccionDetalle' => 'IdProduccionDetalle']);
    }
    
    public function getDefectos($IdProduccionDetalle){
        $result = $this->find()->where("IdProduccionDetalle = $IdProduccionDetalle")->AsArray()->all();
        foreach($result as &$res){
            $defecto = Defectos::findOne($res['IdDefecto'])->Attributes;
            $res['Defecto'] = $defecto['Descripcion'];
        }

        if(count($result)!=0){
            return new ArrayDataProvider([
                'allModels' => $result,
                'id'=>'IdPedido',
                'sort'=>array(
                    'attributes'=> $result[0],
                ),
                'pagination'=>false,
            ]);
        }
        return [];
    }
}
