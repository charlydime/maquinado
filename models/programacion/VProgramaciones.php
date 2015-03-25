<?php

namespace frontend\models\programacion;

use Yii;
use yii\data\ActiveDataProvider;
use kartik\builder\TabularForm;
use kartik\grid\GridView;

/**
 * This is the model class for table "v_programaciones".
 *
 * @property integer $IdProgramacion
 * @property integer $OE_Codigo
 * @property integer $OE_Nuemero
 * @property string $Usuario
 * @property string $Estatus
 * @property string $Producto
 * @property string $Descripcion
 * @property string $ProductoCasting
 * @property string $Marca
 * @property string $Presentacion
 * @property string $Aleacion
 * @property string $PLB
 * @property string $PMB
 * @property string $PTB
 * @property string $TRB
 * @property string $PCC
 * @property string $CTB
 * @property integer $Anio1
 * @property integer $Semana1
 * @property integer $Prioridad1
 * @property integer $Programadas1
 * @property integer $Hechas1
 * @property integer $Anio2
 * @property integer $Semana2
 * @property integer $Prioridad2
 * @property integer $Programadas2
 * @property integer $Hechas2
 * @property integer $Anio3
 * @property integer $Semana3
 * @property integer $Prioridad3
 * @property integer $Programadas3
 * @property integer $Hechas3
 */
class VProgramaciones extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'v_programaciones';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['IdProgramacion', 'OE_Codigo', 'OE_Nuemero', 'Usuario', 'Estatus', 'Producto', 'Descripcion', 'ProductoCasting', 'Marca', 'Presentacion', 'Aleacion', 'Anio1', 'Semana1', 'Prioridad1', 'Programadas1', 'Hechas1', 'Anio2', 'Semana2', 'Prioridad2', 'Programadas2', 'Hechas2', 'Anio3', 'Semana3', 'Prioridad3', 'Programadas3', 'Hechas3'], 'required'],
            [['IdProgramacion', 'OE_Codigo', 'OE_Nuemero', 'Anio1', 'Semana1', 'Prioridad1', 'Programadas1', 'Hechas1', 'Anio2', 'Semana2', 'Prioridad2', 'Programadas2', 'Hechas2', 'Anio3', 'Semana3', 'Prioridad3', 'Programadas3', 'Hechas3'], 'integer'],
            [['Usuario', 'Estatus', 'Producto', 'Descripcion', 'ProductoCasting', 'Marca', 'Presentacion', 'Aleacion'], 'string'],
            [['PLB', 'PMB', 'PTB', 'TRB', 'PCC', 'CTB'], 'number']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'IdProgramacion' => 'Id Programacion',
            'OE_Codigo' => 'Oe  Codigo',
            'OE_Nuemero' => 'Oe  Nuemero',
            'Usuario' => 'Usuario',
            'Estatus' => 'Estatus',
            'Producto' => 'Producto',
            'Descripcion' => 'Descripcion',
            'ProductoCasting' => 'Producto Casting',
            'Marca' => 'Marca',
            'Presentacion' => 'Presentacion',
            'Aleacion' => 'Aleacion',
            'PLB' => 'Plb',
            'PMB' => 'Pmb',
            'PTB' => 'Ptb',
            'TRB' => 'Trb',
            'PCC' => 'Pcc',
            'CTB' => 'Ctb',
            'Anio1' => 'Anio1',
            'Semana1' => 'Semana1',
            'Prioridad1' => 'Prioridad1',
            'Programadas1' => 'Programadas1',
            'Hechas1' => 'Hechas1',
            'Anio2' => 'Anio2',
            'Semana2' => 'Semana2',
            'Prioridad2' => 'Prioridad2',
            'Programadas2' => 'Programadas2',
            'Hechas2' => 'Hechas2',
            'Anio3' => 'Anio3',
            'Semana3' => 'Semana3',
            'Prioridad3' => 'Prioridad3',
            'Programadas3' => 'Programadas3',
            'Hechas3' => 'Hechas3',
        ];
    }
    
    public function search($params,$data)
    {
        $query = vprogramaciones::find()
                ->leftJoin('v_Semana1','v_programaciones.IdProgramacion = v_Semana1.IdProgramacion AND v_Semana1.Anio = '.$data[0]['año'].' AND v_Semana1.Semana = '.$data[0]['semana'])
                ->leftJoin('v_Semana2','v_programaciones.IdProgramacion = v_Semana2.IdProgramacion AND v_Semana2.Anio = '.$data[1]['año'].' AND v_Semana2.Semana = '.$data[1]['semana'])
                ->leftJoin('v_Semana3','v_programaciones.IdProgramacion = v_Semana3.IdProgramacion AND v_Semana3.Anio = '.$data[2]['año'].' AND v_Semana3.Semana = '.$data[2]['semana']);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        return $dataProvider;
    }
    
    public function getProgramacionSemanal($data)
    {
        //obtengo datos desde un f_GetProgramaciones mediante un SELECT
        $params = implode(",",array($data[0]['año'], $data[0]['semana'], $data[1]['año'], $data[1]['semana'], $data[2]['año'], $data[2]['semana'],));
        $command = \Yii::$app->db;
        echo "SELECT * FROM f_GetProgramaciones($params) Where Estatus = 'Abierto'";exit;
        $result =$command->createCommand("SELECT * FROM f_GetProgramaciones($params) Where Estatus = 'Abierto'")->queryAll();
        $query = new Query;
        $query = $query->
                select("
                    dbo.Programaciones.IdProgramacion, 
                    dbo.Pedidos.Codigo AS OE_Codigo, 
                    dbo.Pedidos.Numero AS OE_Nuemero, 
                    dbo.Usuarios.Usuario, 
                    dbo.ProgramacionesEstatus.Descripcion AS Estatus, 
                    dbo.v_Productos.Identificacion AS Producto, 
                    dbo.v_Productos.Descripcion, 
                    dbo.v_Productos.ProductoCasting, 
                    dbo.v_Productos.Marca, 
                    dbo.v_Productos.Presentacion, 
                    dbo.v_Productos.Aleacion, 
                    dbo.v_ExistenciasBronces.PLB, 
                    dbo.v_ExistenciasBronces.PMB, 
                    dbo.v_ExistenciasBronces.PTB, 
                    dbo.v_ExistenciasBronces.TRB, 
                    dbo.v_ExistenciasBronces.PCC, 
                    dbo.v_ExistenciasBronces.CTB, 
                    IdProgramacionSemana1, 
                    Prioridad1, 
                    Programadas1, 
                    Hechas1, 
                    IdProgramacionSemana2, 
                    Prioridad2, 
                    Programadas2, 
                    Hechas2, 
                    IdProgramacionSemana3, 
                    Prioridad3, 
                    Programadas3, 
                    Hechas3
                ")
                ->from('dbo.Programaciones')
                ->innerJoin('dbo.Pedidos','dbo.Programaciones.IdPedido = dbo.Pedidos.IdPedido')
                ->innerJoin('dbo.Usuarios','dbo.Programaciones.IdUsuario = dbo.Usuarios.IdUsuarios')
                ->innerJoin('dbo.ProgramacionesEstatus','dbo.Programaciones.IdProgramacionEstatus = dbo.ProgramacionesEstatus.IdProgramacionEstatus')
                ->innerJoin('dbo.v_Productos','dbo.Programaciones.IdProducto = dbo.v_Productos.IdProducto')
                ->innerJoin('dbo.v_ExistenciasBronces','dbo.Programaciones.IdProducto = dbo.v_ExistenciasBronces.IdProducto')
                ->leftJoin('v_Semana1','dbo.Programaciones.IdProgramacion = v_Semana1.IdProgramacion AND v_Semana1.Anio = 2014 AND v_Semana1.Semana = 44')
                ->leftJoin('v_Semana2','dbo.Programaciones.IdProgramacion = v_Semana2.IdProgramacion AND v_Semana2.Anio = 2014 AND v_Semana2.Semana = 45')
                ->leftJoin('v_Semana3','dbo.Programaciones.IdProgramacion = v_Semana3.IdProgramacion AND v_Semana3.Anio = 2014 AND v_Semana3.Semana = 46');

        return new ActiveDataProvider([
            'query' => $query,
            'pagination'=>false,
        ]);
        
        /*return new SqlDataProvider([
            'allModels' => $result,
            'key'=>['IdProgramacionSemana1','IdProgramacionSemana2','IdProgramacionSemana3'],
            'id'=>'IdProgramacion',
            'sort'=>array(
                'attributes'=>array_keys($result[0]),
            ),
            'pagination'=>false,
        ]);*/
        
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
