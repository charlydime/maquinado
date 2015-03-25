<?php

namespace frontend\models\produccion;

use Yii;
use yii\data\ArrayDataProvider;
use common\models\dux\Productos;

/**
 * This is the model class for table "ProduccionesDetalle".
 *
 * @property integer $IdProduccionDetalle
 * @property integer $IdProduccion
 * @property integer $IdProgramacion
 * @property integer $IdProductos
 * @property string $Inicio
 * @property string $Fin
 * @property integer $CiclosMolde
 * @property integer $PiezasMolde
 * @property integer $Programadas
 * @property integer $Hechas
 * @property integer $Rechazadas
 *
 * @property Producciones $idProduccion
 * @property Productos $idProductos
 * @property Programaciones $idProgramacion
 * @property ProduccionesDefecto[] $produccionesDefectos
 */
class ProduccionesDetalle extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ProduccionesDetalle';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['IdProduccion', 'IdProgramacion', 'IdProductos'], 'required'],
            [['IdProduccion', 'IdProgramacion', 'IdProductos', 'CiclosMolde', 'PiezasMolde', 'Programadas', 'Hechas', 'Rechazadas'], 'integer'],
            [['Inicio', 'Fin'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'IdProduccionDetalle' => 'Id Produccion Detalle',
            'IdProduccion' => 'Id Produccion',
            'IdProgramacion' => 'Id Programacion',
            'IdProductos' => 'Id Productos',
            'Inicio' => 'Inicio',
            'Fin' => 'Fin',
            'CiclosMolde' => 'Ciclos Molde',
            'PiezasMolde' => 'Piezas Molde',
            'Programadas' => 'Programadas',
            'Hechas' => 'Hechas',
            'Rechazadas' => 'Rechazadas',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdProduccion()
    {
        return $this->hasOne(Producciones::className(), ['IdProduccion' => 'IdProduccion']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdProductos()
    {
        return $this->hasOne(Productos::className(), ['IdProducto' => 'IdProductos']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdProgramacion()
    {
        return $this->hasOne(Programaciones::className(), ['IdProgramacion' => 'IdProgramacion']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProduccionesDefectos()
    {
        return $this->hasMany(ProduccionesDefecto::className(), ['IdProduccionDetalle' => 'IdProduccionDetalle']);
    }
    
    public function getDetalle($produccion){
        /*$result = $this->find()->where("IdProduccionDetalle = 6")->all();
        var_dump($result);exit;*/
        $result = $this->find()->where("IdProduccion= $produccion")->asArray()->all();

        foreach ($result as &$res){
            $productos = Productos::findOne($res['IdProductos'])->Attributes;
            $res['Producto'] = $productos['Identificacion'];
            $res['Fin'] = date('H:i:s',strtotime($res['Fin']));
            $res['Inicio'] = date('H:i:s',strtotime($res['Inicio']));
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
