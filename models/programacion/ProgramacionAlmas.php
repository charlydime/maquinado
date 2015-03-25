<?php

namespace frontend\models\programacion;

use Yii;
use yii\data\ArrayDataProvider;

/**
 * This is the model class for table "Programaciones".
 *
 * @property integer $IdProgramacion
 * @property integer $IdPedido
 * @property integer $IdArea
 * @property integer $IdEmpleado
 * @property integer $IdProgramacionEstatus
 * @property integer $IdProducto
 * @property integer $Programadas
 * @property integer $Hechas
 *
 * @property ProgramacionesSemana[] $programacionesSemanas
 * @property Empleados $idEmpleado
 * @property Areas $idArea
 * @property Pedidos $idPedido
 * @property Productos $idProducto
 * @property ProgramacionesEstatus $idProgramacionEstatus
 * @property ProduccionesDetalle[] $produccionesDetalles
 * @property ProgramacionesAlma[] $programacionesAlmas
 */
class Programacion extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'Programaciones';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['IdPedido', 'IdArea', 'IdEmpleado', 'IdProgramacionEstatus', 'IdProducto', 'Programadas', 'Hechas'], 'required'],
            [['IdPedido', 'IdArea', 'IdEmpleado', 'IdProgramacionEstatus', 'IdProducto', 'Programadas', 'Hechas'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'IdProgramacion' => 'Id Programacion',
            'IdPedido' => 'Id Pedido',
            'IdArea' => 'Id Area',
            'IdEmpleado' => 'Id Empleado',
            'IdProgramacionEstatus' => 'Id Programacion Estatus',
            'IdProducto' => 'Id Producto',
            'Programadas' => 'Programadas',
            'Hechas' => 'Hechas',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProgramacionesSemanas()
    {
        return $this->hasMany(ProgramacionesSemana::className(), ['IdProgramacion' => 'IdProgramacion']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdEmpleado()
    {
        return $this->hasOne(Empleados::className(), ['IdEmpleado' => 'IdEmpleado']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdArea()
    {
        return $this->hasOne(Areas::className(), ['IdArea' => 'IdArea']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdPedido()
    {
        return $this->hasOne(Pedidos::className(), ['IdPedido' => 'IdPedido']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdProducto()
    {
        return $this->hasOne(Productos::className(), ['IdProducto' => 'IdProducto']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdProgramacionEstatus()
    {
        return $this->hasOne(ProgramacionesEstatus::className(), ['IdProgramacionEstatus' => 'IdProgramacionEstatus']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProduccionesDetalles()
    {
        return $this->hasMany(ProduccionesDetalle::className(), ['IdProgramacion' => 'IdProgramacion']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProgramacionesAlmas()
    {
        return $this->hasMany(ProgramacionesAlma::className(), ['IdProgramacion' => 'IdProgramacion']);
    }
    
    public function getProgramacionSemanal($data)
    {
        //obtengo datos desde un f_GetProgramaciones mediante un SELECT
        $area = Yii::$app->session->get('area');
        $area = $area['IdArea'];
        
        $params = implode(",",[
            $data['semana1']['año'],
            $data['semana1']['semana'],
            $data['semana2']['año'],
            $data['semana2']['semana'],
            $data['semana3']['año'],
            $data['semana3']['semana']
        ]);
        $command = \Yii::$app->db;
        $result =$command->createCommand("SELECT * FROM f_GetProgramaciones($area,$params) Where Estatus = 'Abierto'")->queryAll();
        
        if(count($result)!=0){
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
        return [];
    }
    
    public function getProgramacionDiaria($data,$proceso,$turno)
    {
        //obtengo datos desde un f_GetProgramaciones mediante un SELECT
        $area = Yii::$app->session->get('area');
        $area = $area['IdArea'];
        $year = $data['semana1']['año'];
        $week = $data['semana1']['semana'];
        $fecha = strtotime($year."W".$week."1");
        $fecha = date('Y-m-d',$fecha);
        $params = implode(",",[
            $data['semana1']['año'],
            $data['semana1']['semana'],
            "'$fecha'"
        ]);
        $command = \Yii::$app->db;
        $result =$command->createCommand("SELECT * FROM f_GetProgramacionesDiaria($area,$params,$proceso,$turno)")->queryAll();

        if(count($result)!=0){
            return new ArrayDataProvider([
                'allModels' => $result,
                'key'=>['IdProgramacionSemana'],
                'id'=>'IdProgramacion',
                'sort'=>array(
                    'attributes'=>array_keys($result[0]),
                ),
                'pagination'=>false,
            ]);
        }
        return [];
    }
    
    public function setProgramacionDiaria($data)
    {
        $command = \Yii::$app->db;
        $result =$command->createCommand("EXECUTE p_SetProgramacionDia $data,NULL")->execute();
    }
    public function setProgramacionSemanal($data)
    {
        $command = \Yii::$app->db;
        $result =$command->createCommand("EXECUTE p_SetProgramacionSemana $data,NULL")->execute();
    }
    
    public function formAttribsProgramacionSemanal()
    {
        return [
            'IdProgramacion'=>['type'=>TabularForm::INPUT_STATIC],
            'OE_Codigo'=>['type'=>TabularForm::INPUT_STATIC],
            'OE_Nuemero'=>['type'=>TabularForm::INPUT_STATIC],
            'Usuario'=>['type'=>TabularForm::INPUT_STATIC],
            'Estatus'=>['type'=>TabularForm::INPUT_STATIC],
            'Producto'=>['type'=>TabularForm::INPUT_STATIC],
            'Descripcion'=>['type'=>TabularForm::INPUT_STATIC],
            'ProductoCasting'=>['type'=>TabularForm::INPUT_STATIC],
            'Marca'=>['type'=>TabularForm::INPUT_STATIC],
            'Presentacion'=>['type'=>TabularForm::INPUT_STATIC],
            'Aleacion'=>['type'=>TabularForm::INPUT_STATIC],
            'PLB'=>['type'=>TabularForm::INPUT_STATIC],
            'PMB'=>['type'=>TabularForm::INPUT_STATIC],
            'PTB'=>['type'=>TabularForm::INPUT_STATIC],
            'TRB'=>['type'=>TabularForm::INPUT_STATIC],
            'PCC'=>['type'=>TabularForm::INPUT_STATIC],
            'CTB'=>['type'=>TabularForm::INPUT_STATIC],

/*            'FechaEmbarque' => [
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
*/            
        ];
    }
}
