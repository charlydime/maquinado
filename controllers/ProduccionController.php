<?php

namespace frontend\controllers;

use Yii;
use frontend\models\produccion\TiemposMuerto;
use frontend\models\produccion\ProduccionesDetalle;
use frontend\models\produccion\ProduccionesDefecto;
use frontend\models\produccion\Producciones;
use frontend\models\programaciones\VProgramacionesDia;
use common\models\catalogos\VDefectos;
use common\models\catalogos\Maquinas;
use common\models\datos\Causas;

class ProduccionController extends \yii\web\Controller
{
    public function actionIndex()
    {
        return $this->render('index',[
            'title' => 'Captura de Produccion',
        ]);
    }
    
    public function actionSave(){
        $datos = json_decode(Yii::$app->request->get('data'));

        foreach($datos as $dat){
            if(isset($dat->IdProduccion))
                $produccion = Producciones::findOne($dat->IdProduccion);
            
            switch(Yii::$app->request->get('grid')){
                case 'detalle':
                    $model = ProduccionesDetalle::findOne($dat->IdProduccionDetalle);
                    if($model == null)
                        $model = new ProduccionesDetalle();

                    $data['IdProduccion'] = $dat->IdProduccion;
                    $data['IdProgramacion'] = $dat->IdProgramacion;
                    $data['IdProductos'] = $dat->IdProductos;
                    $data['Inicio'] = date('Y-m-d',strtotime($produccion->Attributes['Fecha']))." ".($dat->Inicio == '' ? "00:00" : $dat->Inicio);
                    $data['Fin'] = date('Y-m-d',strtotime($produccion->Attributes['Fecha']))." ".($dat->Fin == '' ? "00:00:00" : $dat->Fin);
                    $data['CiclosMolde'] = $dat->CiclosMolde;
                    $data['PiezasMolde'] = $dat->PiezasMolde;
                    $data['Programadas'] = $dat->Programadas;
                    $data['Hechas'] = $dat->Hechas == '' ? 0 : $dat->Hechas;
                    $data['Rechazadas'] = $dat->Rechazadas == '' ? 0 : $dat->Rechazadas;
                    $data2['ProduccionesDetalle'] = $data;

                    $model->load($data2);

                    break;
                case 'rechazo':
                    $model = ProduccionesDefecto::findOne($dat->IdProduccionDefecto);

                    if($model == null)
                        $model = new ProduccionesDefecto();

                    $data['IdProduccionDetalle'] = $dat->IdProduccionDetalle;
                    $data['IdDefecto'] = $dat->IdDefecto;
                    $data['Rechazadas'] = $dat->Rechazadas;

                    $data2['ProduccionesDefecto'] = $data;
                    $model->load($data2);

                    break;
                case 'tiempo_muerto':
                    $model = TiemposMuerto::findOne($dat->IdTiempoMuerto);

                    if($model == null)
                        $model = new TiemposMuerto();

                    $data['IdTiempoMuerto'] = $dat->IdTiempoMuerto;
                    $data['IdProduccion'] = $dat->IdProduccion;
                    $data['IdCausa'] = $dat->IdCausa;
                    $data['Inicio'] = date('Y-m-d',strtotime($produccion->Attributes['Fecha']))." ".($dat->Inicio == '' ? "00:00" : $dat->Inicio);
                    $data['Fin'] = date('Y-m-d',strtotime($produccion->Attributes['Fecha']))." ".($dat->Fin == '' ? "00:00:00" : $dat->Fin);
                    $data['Descripcion'] = $dat->Descripcion;

                    $data2['TiemposMuerto'] = $data;
                    $model->load($data2);

                    break;
            }
            $model->save();
        }
    }
    
    public function actionDelete(){
        $datos = json_decode(Yii::$app->request->get('data'));
        
        switch(Yii::$app->request->get('grid')){
            case 'detalle':
                $model = ProduccionesDetalle::findOne($datos->IdProduccionDetalle)->delete();
                break;
            case 'rechazo':
                $model = ProduccionesDefecto::findOne($datos->IdProduccionDefecto)->delete();
                break;
            case 'tiempo_muerto':
                $model = TiemposMuerto::findOne($datos->IdTiempoMuerto)->delete();
                break;
        }
    }
    
    public function actionCaptura($proceso)
    {
        $this->layout = 'captura';
        $produccion = new Producciones();
        $tiempoMuerto = new TiemposMuerto();
        $produccionDetalle = new ProduccionesDetalle();
        
        $empleado = Yii::$app->user->getIdentity()->getAttributes()['IdEmpleado'];
        $data = Yii::$app->request->post();
        if(count($data) != 0 ){
            if(isset($data['Cerrar'])){
                unset($data['Cerrar']);
                $produccion = $produccion->findOne($data['Producciones']['IdProduccion']);
                $data['Producciones']['IdProduccionEstatus'] = '2';
            }
            if(isset($data['Iniciar'])){
                unset($data['Iniciar']);
                $produccion = new Producciones();
                $maquina = Maquinas::findOne($data['Producciones']['IdMaquina'])->Attributes;
                
                $data['Producciones']['IdProduccionEstatus'] = '1';
                $data['Producciones']['IdCentroTrabajo'] = $maquina['IdCentroTrabajo'];
                $data['Producciones']['IdEmpleado'] = $empleado;
                $data['Producciones']['IdProceso'] = $proceso;
            }
            
            $produccion->load($data);
            $produccion->save();
        }
        
        //cargando modelos para la captura
        $dataProvider = $produccion->find()->where("IdProduccionEstatus = 1 AND IdProceso = $proceso AND IdEmpleado = $empleado")->asArray()->all();
        $produccion = count($dataProvider) > 0 ? $produccion->findOne($dataProvider[0]['IdProduccion']) : $produccion;

        return $this->render('captura', [
            'title' => 'Captura de Produccion',
            'proceso'=> $proceso,
            'tiempoMuerto' => $tiempoMuerto,
            'produccion' => $produccion,
            'detalle'=>$produccionDetalle
        ]);
    }
    
    public function actionDetalle(){
        if(isset($_GET['produccion'])){
            $produccion = $_GET['produccion'];
            
            $model = new ProduccionesDetalle();
            
            $dataProvider = $model->getDetalle($produccion);
        
        if(count($dataProvider)>0){
                return json_encode([
                    'total'=>count($dataProvider->allModels),
                    'rows'=>$dataProvider->allModels,
                ]);
            }
        }
        return json_encode([
            'total'=>0,
            'rows'=>[],
        ]);
    }
    
    public function actionRechazo(){
        if(isset($_POST['detalle'])){
            $model = new ProduccionesDefecto();
            $dataProvider = $model->getDefectos($_POST['detalle']);
        
            if(count($dataProvider)>0){
                return json_encode([
                    'total'=>count($dataProvider->allModels),
                    'rows'=>$dataProvider->allModels,
                ]);
            }
        }
        return json_encode([
            'total'=>0,
            'rows'=>[],
        ]);
        
    }
    
    public function actionData_rechazo(){
        $model = new VDefectos();
        $dataProvider = $model->find()->asArray()->all();

        if(count($dataProvider)>0){
            return json_encode($dataProvider);
        }
    }
    
    public function actionData_tiempos_muertos(){
        $model = new TiemposMuerto();
        $dataProvider = $model->find()->where("IdProduccion = ".$_GET['IdProduccion'])->with("idCausa")->asArray()->all();
        foreach($dataProvider as &$TiemposMuerto){
            $TiemposMuerto['Causa'] = $TiemposMuerto['idCausa']['Descripcion'];
            $TiemposMuerto['Fin'] = date('H:i:s',strtotime($TiemposMuerto['Fin']));
            $TiemposMuerto['Inicio'] = date('H:i:s',strtotime($TiemposMuerto['Inicio']));
        }
        //var_dump($dataProvider);
        if(count($dataProvider)>0){
            return json_encode($dataProvider);
        }
    }
    
    public function actionData_causas(){
        $model = new Causas();
        $dataProvider = $model->find()->with('idCausaTipo')->where("IdProceso = ".$_GET['IdProceso'])->orderBy('IdCausaTipo')->asArray()->all();
        
        foreach($dataProvider as &$causas){
            $causas['Tipo'] = $causas['idCausaTipo']['Descripcion'];
        }
        
        //var_dump($dataProvider);
        if(count($dataProvider)>0){
            return json_encode($dataProvider);
        }
    }
}
