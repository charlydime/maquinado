<?php

namespace frontend\models\programacion;

use Yii;
use yii\data\ActiveDataProvider;
use yii\data\ArrayDataProvider;
use kartik\builder\TabularForm;
use kartik\grid\GridView;
use yii\base\Model;

/**
 * This is the model class for table "v_pedidos".
 *
 * @property integer $IdPedido
 * @property integer $IdAlmacen
 * @property integer $IdProducto
 * @property integer $Codigo
 * @property integer $Numero
 * @property string $Producto
 * @property string $Almacen
 * @property string $Fecha
 * @property string $Cliente
 * @property string $OrdenCompra
 * @property integer $Estatus
 * @property string $Cantidad
 * @property string $SaldoCantidad
 * @property string $FechaEmbarque
 * @property integer $NivelRiesgo
 * @property string $TotalProgramado
 * @property string $Observaciones
 */
class VPedidos extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'v_pedidos';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['IdPedido', 'IdAlmacen', 'IdProducto', 'Codigo', 'Numero', 'Producto', 'Almacen', 'Fecha', 'Cliente', 'Estatus', 'Cantidad', 'SaldoCantidad', 'NivelRiesgo', 'TotalProgramado'], 'required'],
            [['IdPedido', 'IdAlmacen', 'IdProducto', 'Codigo', 'Numero', 'Estatus', 'NivelRiesgo'], 'integer'],
            [['Producto', 'Almacen', 'Cliente', 'OrdenCompra', 'Observaciones'], 'string'],
            [['Fecha', 'FechaEmbarque'], 'safe'],
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
            'Producto' => 'Producto',
            'Almacen' => 'Almacen',
            'Fecha' => 'Fecha',
            'Cliente' => 'Cliente',
            'OrdenCompra' => 'Orden Compra',
            'Estatus' => 'Estatus',
            'Cantidad' => 'Cantidad',
            'SaldoCantidad' => 'Saldo Cantidad',
            'FechaEmbarque' => 'Fecha Embarque',
            'NivelRiesgo' => 'Nivel Riesgo',
            'TotalProgramado' => 'Total Programado',
            'Observaciones' => 'Observaciones',
        ];
    }
    
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }
    
    public function getPedidos($data)
    {
        //obtengo datos desde un f_GetProgramaciones mediante un SELECT
        $params = implode(",",[
            $data['semana1']['año'],
            $data['semana1']['semana'],
            $data['semana2']['año'],
            $data['semana2']['semana'],
            $data['semana3']['año'],
            $data['semana3']['semana']
        ]);
        $command = \Yii::$app->db;
        $result =$command->createCommand("SELECT * FROM f_GetProgramaciones($params) Where Estatus = 'Abierto'")->queryAll();
        
        return new ArrayDataProvider([
            'allModels' => $result,
            'key'=>['IdProgramacionSemana1','IdProgramacionSemana2','IdProgramacionSemana3'],
            'id'=>'IdProgramacion',
            'sort'=>array(
                'attributes'=>array_keys($result[0]),
            ),
            'pagination'=>false,
        ]);
        
    }
    
    public function search($params)
    {
        $query = VPedidos::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'IdPedido' => $this->IdPedido,
            'IdAlmacen' => $this->IdAlmacen,
            'IdProducto' => $this->IdProducto,
            'Codigo' => $this->Codigo,
            'Numero' => $this->Numero,
            'Producto' => $this->Producto,
            'Almacen' => $this->Almacen,
            'Fecha' => $this->Fecha,
            'Cliente' => $this->Cliente,
            'OrdenCompra' => $this->OrdenCompra,
            'Estatus' => $this->Estatus,
            'Cantidad' => $this->Cantidad,
            'SaldoCantidad' => $this->SaldoCantidad,
            'FechaEmbarque' => $this->FechaEmbarque,
            'NivelRiesgo' => $this->NivelRiesgo,
            'TotalProgramado' => $this->TotalProgramado,
            'Observaciones' => $this->Observaciones,
        ]);

        $query->andFilterWhere(['like', 'Cliente', $this->Cliente])
            ->andFilterWhere(['like', 'OrdenCompra', $this->OrdenCompra])
            ->andFilterWhere(['like', 'Observaciones', $this->Observaciones]);

        return $dataProvider;
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
