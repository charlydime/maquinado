<?php

namespace frontend\models\programacion;

use Yii;
use yii\data\ArrayDataProvider;

/**
 * This is the model class for table "ProgramacionesAlma".
 *
 * @property integer $IdProgramacionAlma
 * @property integer $IdProgramacion
 * @property integer $IdEmpleado
 * @property integer $IdProgramacionEstatus
 * @property integer $IdAlmas
 * @property integer $Programadas
 * @property integer $Hechas
 *
 * @property Empleados $idEmpleado
 * @property Almas $idAlmas
 * @property Programaciones $idProgramacion
 * @property ProgramacionesEstatus $idProgramacionEstatus
 * @property ProgramacionesAlmaSemana[] $programacionesAlmaSemanas
 * @property AlmasProduccionDetalle[] $almasProduccionDetalles
 */

class ProgramacionesAlma extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ProgramacionesAlma';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['IdProgramacion', 'IdEmpleado', 'IdProgramacionEstatus', 'IdAlmas', 'Programadas', 'Hechas'], 'required'],
            [['IdProgramacion', 'IdEmpleado', 'IdProgramacionEstatus', 'IdAlmas', 'Programadas', 'Hechas'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'IdProgramacionAlma' => 'Id Programacion Alma',
            'IdProgramacion' => 'Id Programacion',
            'IdEmpleado' => 'Id Empleado',
            'IdProgramacionEstatus' => 'Id Programacion Estatus',
            'IdAlmas' => 'Id Almas',
            'Programadas' => 'Programadas',
            'Hechas' => 'Hechas',
        ];
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
    public function getIdAlmas()
    {
        return $this->hasOne(Almas::className(), ['IdAlma' => 'IdAlmas']);
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
    public function getIdProgramacionEstatus()
    {
        return $this->hasOne(ProgramacionesEstatus::className(), ['IdProgramacionEstatus' => 'IdProgramacionEstatus']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProgramacionesAlmaSemanas()
    {
        return $this->hasMany(ProgramacionesAlmaSemana::className(), ['IdProgramacionAlma' => 'IdProgramacionAlma']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAlmasProduccionDetalles()
    {
        return $this->hasMany(AlmasProduccionDetalle::className(), ['IdProgramacionAlma' => 'IdProgramacionAlma']);
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

        $result =$command->createCommand("SELECT * FROM f_GetProgramacionesAlmas($area,$params) Where Estatus = 'Abierto'")->queryAll();
        
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
    
}
