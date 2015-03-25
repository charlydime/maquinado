<?php

namespace frontend\models\programacion;

use Yii;
use kartik\builder\TabularForm;
use kartik\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\data\ArrayDataProvider;

/**
 * This is the model class for table "Pedidos".
 *
 * @property integer $IdPedido
 * @property integer $IdAlmacen
 * @property integer $IdProducto
 * @property integer $Codigo
 * @property integer $Numero
 * @property string $Fecha
 * @property string $Cliente
 * @property string $OrdenCompra
 * @property integer $Estatus
 * @property string $Cantidad
 * @property string $SaldoCantidad
 * @property string $FechaEmbarque
 * @property integer $NivelRiesgo
 * @property string $Observaciones
 * @property string $TotalProgramado
 *
 * @property Programaciones[] $programaciones
 * @property Almacenes $idAlmacen
 * @property Productos $idProducto
 */
class Pedidos extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'Pedidos';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['IdAlmacen', 'IdProducto', 'Codigo', 'Numero', 'Fecha', 'Cliente', 'Estatus', 'Cantidad', 'SaldoCantidad'], 'required'],
            [['IdAlmacen', 'IdProducto', 'Codigo', 'Numero', 'Estatus', 'NivelRiesgo'], 'integer'],
            [['Fecha', 'FechaEmbarque'], 'safe'],
            [['Cliente', 'OrdenCompra', 'Observaciones'], 'string'],
            [['Cantidad', 'SaldoCantidad', 'TotalProgramado'], 'number']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'IdPedido' => 'Id Pedido',
            'IdAlmacen' => 'Id Almacen',
            'IdProducto' => 'Id Producto',
            'Codigo' => 'Codigo',
            'Numero' => 'Numero',
            'Fecha' => 'Fecha',
            'Cliente' => 'Cliente',
            'OrdenCompra' => 'Orden Compra',
            'Estatus' => 'Estatus',
            'Cantidad' => 'Cantidad',
            'SaldoCantidad' => 'Saldo Cantidad',
            'FechaEmbarque' => 'Fecha Embarque',
            'NivelRiesgo' => 'Nivel Riesgo',
            'Observaciones' => 'Observaciones',
            'TotalProgramado' => 'Total Programado',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProgramaciones()
    {
        return $this->hasMany(Programaciones::className(), ['IdPedido' => 'IdPedido']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdAlmacen()
    {
        return $this->hasOne(Almacenes::className(), ['IdAlmacen' => 'IdAlmacen']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdProducto()
    {
        return $this->hasOne(Productos::className(), ['IdProducto' => 'IdProducto']);
    }
    
    public function getSinProgramar()
    {
        $area = Yii::$app->session->get('area');
        $area = $area['IdArea'];

        $command = \Yii::$app->db;
        $result =$command->createCommand("SELECT * FROM v_PedidosNoProgramados Where IdPresentacion = $area")->queryAll();

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
    
    public function getMarcas()
    {
        $area = Yii::$app->session->get('area');
        $area = $area['IdArea'];

        $command = \Yii::$app->db;
        $result =$command->createCommand("SELECT Distinct 
            CASE WHEN Marca = '' THEN '' ELSE Marca END AS value,
            CASE WHEN Marca = '' THEN 'TODOS' ELSE Marca END AS text 
            FROM v_PedidosNoProgramados Where IdPresentacion = $area
        ")->queryAll();

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
    
    public function getFormAttribs()
    {
        return [
            'IdPedido' => [
                'type' => TabularForm::INPUT_STATIC,
            ],
            'IdAlmacen' => ['type' => TabularForm::INPUT_TEXT,'options'=>['class'=>'form-control long'],],
            'IdProducto' => ['type' => TabularForm::INPUT_TEXT,'options'=>['class'=>'form-control long'],],
            'Codigo' => ['type' => TabularForm::INPUT_TEXT,'options'=>['class'=>'form-control long'],],
            'Numero' => ['type' => TabularForm::INPUT_TEXT,'options'=>['class'=>'form-control long'],],
            'Fecha' => [
                'type'=> TabularForm::INPUT_WIDGET, 
                'widgetClass'=>\kartik\widgets\DatePicker::classname(), 
                'options' => [ 
                    'pluginOptions' => [
                        'format' => 'yyyy-mm-dd',
                        'todayHighlight' => true, 
                        'autoclose'=>true
                    ]
                ],
                'columnOptions'=>['width'=>'170px'],
            ],
            'Cliente' => ['type' => TabularForm::INPUT_TEXT,'options'=>['class'=>'form-control long'],],
            'OrdenCompra' => ['type' => TabularForm::INPUT_TEXT,'options'=>['class'=>'form-control long'],],
            'Estatus' => ['type' => TabularForm::INPUT_TEXT,'options'=>['class'=>'form-control long'],],
            'Cantidad' => ['type' => TabularForm::INPUT_TEXT,'options'=>['class'=>'form-control long'],],
            'SaldoCantidad' => ['type' => TabularForm::INPUT_TEXT,'options'=>['class'=>'form-control long'],],
            'FechaEmbarque' => [
                'type'=> TabularForm::INPUT_WIDGET, 
                'widgetClass'=>\kartik\widgets\DatePicker::classname(), 
                'options' => [ 
                    'pluginOptions' => [
                        'format' => 'yyyy-mm-dd',
                        'todayHighlight' => true, 
                        'autoclose'=>true
                    ]
                ],
                'columnOptions'=>['width'=>'170px'],
            ],
            'NivelRiesgo' => ['type' => TabularForm::INPUT_TEXT,'options'=>['class'=>'form-control long'],],
            'Observaciones' => ['type' => TabularForm::INPUT_TEXT,'options'=>['class'=>'form-control long'],],
            'TotalProgramado' => ['type' => TabularForm::INPUT_TEXT,'options'=>['class'=>'form-control long'],],
            
        ];
    }
}
