<?php

namespace frontend\controllers;

use Yii;
use frontend\models\programacion\Programacion;
use frontend\models\programacion\VProgramacionesDia;
use frontend\models\programacion\ProgramacionesDia;
use frontend\models\programacion\Pedidos;
use common\models\catalogos\VMaquinas;
use common\models\catalogos\Turnos;
use frontend\models\programacion\ProgramacionesAlma;
use frontend\models\programacion\ProgramacionesAlmaSemana;
use frontend\models\programacion\ProgramacionesAlmaDia;
use common\models\datos\Almas;
use common\models\dux\Productos;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ProgramacionesController implements the CRUD actions for programaciones model.
 */
class ProgramacionController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    public function actionSemanal($semana1 = '',$fecha = '')
    {
        $mes = date('m');
        if($semana1 == ''){
            $semana1 = $mes == 12 && date('W') == 1 ? array(date('Y')+1,date('W')) : array(date('Y'),date('W'));
        }else{
            $semana1 = explode('-W',$semana1);
        }
        
        $semanas['semana1'] = ['año'=>$semana1[0],'semana'=>$semana1[1],'value'=>"$semana1[0]-W".(strlen($semana1[1]) == 1 ? "0" : "").$semana1[1]];
        $semanas['semana2'] = $this->checarSemana($semanas['semana1']);
        $semanas['semana3'] = $this->checarSemana($semanas['semana2']);

        $programacion = new Programacion();
        $dataProvider = $programacion->getprogramacionSemanal($semanas,Yii::$app->session->get('area'));
        
        $pedidos = new Pedidos();
        $dataProvider2 = $pedidos->getSinProgramar($fecha);
        //var_dump($dataProvider2);exit;
        return $this->render('programacion',[
            'title'=>'Programacion Semanal',
            'semanas'=>$semanas,
            'programacion'=>$programacion,
            //'data'=>json_encode($dataProvider->allModels),
        ]);
    }
    
    public function actionDiaria($proceso=1,$semana = '')
    {
        $mes = date('m');
        if($semana == ''){
            $semana2 = $mes == 12 && date('W') == 1 ? array(date('Y')+1,date('W')) : array(date('Y'),date('W'));
        }else{
            $semana2 = explode('-W',$semana);
        }
        
        $semanas['semana1'] = ['año'=>$semana2[0],'semana'=>$semana2[1],'value'=>"$semana2[0]-W$semana2[1]"];
        
        $turnos = new Turnos();
        
        return $this->render('programacionDiaria',[
            'title'=>'Programacion diaria',
            'turnos'=>$turnos,
            'semana'=>$semana,
            'proceso'=>$proceso,
            'turno'=>1,
            'semanas'=>$semanas,
        ]);
    }
    
    public function actionData_semanal()
    {
        $this->layout = 'JSON';
        $semana1 = isset($_POST['semana1']) ? explode('-W',$_POST['semana1']) : array(date('Y'),date('W'));
        $semanas['semana1'] = ['año'=>$semana1[0],'semana'=>$semana1[1],'value'=>"$semana1[0]-W$semana1[1]"];
        $semanas['semana2'] = $this->checarSemana($semanas['semana1']);
        $semanas['semana3'] = $this->checarSemana($semanas['semana2']);
        
        $programacion = new Programacion();
        $dataProvider = $programacion->getProgramacionSemanal($semanas);
      /*  $producto ='';
         
        $ban = 1;
        foreach ($dataProvider->allModels as &$value) {
              
           $value['t'] = 10;
                     
        }
        
        print_r($dataProvider->allModels);
        exit();
       */
        
       //echo "<pre>"; print_r($dataResumen); exit();
        $dataResumen = $this-> DataResumen($dataProvider->allModels);
        if(count($dataProvider)==0){
            return json_encode([
                'total'=>0,
                'rows'=>[],
                'footer'=>[],
            ]);
        }
        
      
        return json_encode([
                'total'=>count($dataProvider->allModels),
                'rows'=>$dataProvider->allModels,
                'footer'=>$dataResumen,
        ]);
    }

    protected function DataResumen($dataArray)
    {
        $mol1 = 0; $mol2 = 0; $mol3 = 0;
        $ton1 = 0; $ton2 = 0; $ton3 = 0;
        $tonP1 = 0; $tonP2 = 0; $tonP3 = 0;
        $MoldesHora1 = 0; $MoldesHora2 = 0; $MoldesHora3 = 0;
        
        $molH1 = 0; $molH2 = 0; $molH3 = 0;
        $tonH1 = 0; $tonH2 = 0; $tonH3 = 0;
        $tonPH1 = 0; $tonPH2 = 0; $tonPH3 = 0;
        $MoldesHoraH1 = 0; $MoldesHoraH2 = 0; $MoldesHoraH3 = 0;
        
        foreach ($dataArray as $key => $value) {
            
            /**************************INI PROGRAMADAS**************************/

            $resPiezas1 = ($value['Programadas1'] * $value['PiezasMolde']);
            $resPiezas2 = ($value['Programadas2'] * $value['PiezasMolde']);
            $resPiezas3 = ($value['Programadas3'] * $value['PiezasMolde']);
            
            $mol1 = $mol1 + $value['Programadas1'];
            $mol2 = $mol2 + $value['Programadas2'];
            $mol3 = $mol3 + $value['Programadas3'];
         
            $ton1 = $ton1 + ($value['Programadas1'] * $value['PesoArania']);
            $ton2 = $ton2 + ($value['Programadas2'] * $value['PesoArania']);
            $ton3 = $ton3 + ($value['Programadas3'] * $value['PesoArania']);
            
            $tonP1 = $tonP1 + ($resPiezas1 * $value['PesoCasting']);
            $tonP2 = $tonP2 + ($resPiezas2 * $value['PesoCasting']);
            $tonP3 = $tonP3 + ($resPiezas3 * $value['PesoCasting']);
            
            /*if($value['MoldesHora']){
                $resultHoras = $value['MoldesHora']/60;
                $MoldesHora1 = $MoldesHora1 + ($value['Programadas1'] * $resultHoras)/60;
                $MoldesHora2 = $MoldesHora2 + ($value['Programadas2'] * $resultHoras)/60;
                $MoldesHora3 = $MoldesHora3 + ($value['Programadas3'] * $resultHoras)/60;
                
            }  else {*/
                $resultHoras = 65/60;
                $MoldesHora1 = $MoldesHora1 + ($value['Programadas1'] * $resultHoras)/60;
                $MoldesHora2 = $MoldesHora2 + ($value['Programadas2'] * $resultHoras)/60;
                $MoldesHora3 = $MoldesHora3 + ($value['Programadas3'] * $resultHoras)/60;
                
           // }
           
            /*****************************END**********************************/
                
            /**************************INI PRODUCIDAS**************************/

            $resPiezasH1 = ($value['Hechas1'] * $value['PiezasMolde']);
            $resPiezasH2 = ($value['Hechas2'] * $value['PiezasMolde']);
            $resPiezasH3 = ($value['Hechas3'] * $value['PiezasMolde']);
            
            $molH1 = $molH1 + $value['Hechas1'];
            $molH2 = $molH2 + $value['Hechas2'];
            $molH3 = $molH3 + $value['Hechas3'];
         
            $tonH1 = $tonH1 + ($value['Hechas1'] * $value['PesoArania']);
            $tonH2 = $tonH2 + ($value['Hechas2'] * $value['PesoArania']);
            $tonH3 = $tonH3 + ($value['Hechas3'] * $value['PesoArania']);
            
            $tonPH1 = $tonPH1 + ($resPiezasH1 * $value['PesoCasting']);
            $tonPH2 = $tonPH2 + ($resPiezasH2 * $value['PesoCasting']);
            $tonPH3 = $tonPH3 + ($resPiezasH3 * $value['PesoCasting']);
            
            /*if($value['MoldesHora']){
                $resultHoras = $value['MoldesHora']/60;
                $MoldesHora1 = $MoldesHora1 + ($value['Hechas1'] * $resultHoras)/60;
                $MoldesHora2 = $MoldesHora2 + ($value['Hechas2'] * $resultHoras)/60;
                $MoldesHora3 = $MoldesHora3 + ($value['Hechas3'] * $resultHoras)/60;
                
            }  else {*/
                $resultHorasH = 65/60;
                $MoldesHoraH1 = $MoldesHoraH1 + ($value['Hechas1'] * $resultHorasH)/60;
                $MoldesHoraH2 = $MoldesHoraH2 + ($value['Hechas2'] * $resultHorasH)/60;
                $MoldesHoraH3 = $MoldesHoraH3 + ($value['Hechas3'] * $resultHorasH)/60;
                
           // }
           
            /*****************************END**********************************/
        }
        
            
             $dataProvider2 = [
            [   
                "Prioridad1"=>"MOL",
                "Programadas1"=>"TON",
                "Hechas1"=>"TON P",
                "Horas1"=>"HRS",
                "Prioridad2"=>"MOL",
                "Programadas2"=>"TON",
                "Hechas2"=>"TON P",
                "Horas2"=>"HRS",
                "Prioridad3"=>"MOL",
                "Programadas3"=>"TON",
                "Hechas3"=>"TON P",
                "Horas3"=>"HRS"
            ],
            [   
                "TotalProgramado"=>"PRG",
                "Prioridad1"=>$mol1,
                "Programadas1"=>$ton1,
                "Hechas1"=>$tonP1,
                "Horas1"=>$MoldesHora1,
                "Prioridad2"=>$mol2,
                "Programadas2"=>$ton2,
                "Hechas2"=>$tonP2,
                "Horas2"=>$MoldesHora2,
                "Prioridad3"=>$mol3,
                "Programadas3"=>$ton3,
                "Hechas3"=>$tonP3,
                "Horas3"=>$MoldesHora3
            ],
            [
                "TotalProgramado"=>"PROD",
                "Prioridad1"=>$molH1,
                "Programadas1"=>$tonH1,
                "Hechas1"=>$tonPH1,
                "Horas1"=>$MoldesHoraH1,
                "Prioridad2"=>$molH2,
                "Programadas2"=>$tonH2,
                "Hechas2"=>$tonPH2,
                "Horas2"=>$MoldesHoraH2,
                "Prioridad3"=>$molH3,
                "Programadas3"=>$tonH3,
                "Hechas3"=>$tonPH3,
                "Horas3"=>$MoldesHoraH3
            ],
            [
                "TotalProgramado"=>"% PROD",
                "Prioridad1"=>"",
                "Programadas1"=>"",
                "Hechas1"=>"",
                "Horas1"=>"",
                "Prioridad2"=>"",
                "Programadas2"=>"",
                "Hechas2"=>"",
                "Horas2"=>"",
                "Prioridad3"=>"",
                "Programadas3"=>"",
                "Hechas3"=>"",
                "Horas3"=>"" 
            ],
        ];
            
            return $dataProvider2;
    }

    public function actionData_maquina($proceso)
    {
        $this->layout = 'JSON';
                
        $maquinas = new VMaquinas();
        $dataProvider = $maquinas->find("IdProceso = ".$proceso)->asArray()->all();
        
        return json_encode($dataProvider);
    }
    
    public function actionData_diaria($proceso,$semana='')
    {
        $turno = isset($_POST['turno']) ? $_POST['turno'] : 1;
        $semana = $semana == '' ? array(date('Y'),date('W')) : explode('-W',$semana);
        
        $this->layout = 'JSON';

        $semanas['semana1'] = ['año'=>$semana[0],'semana'=>$semana[1],'value'=>"$semana[0]-W$semana[1]"];

        $programacion = new Programacion();
        $dataProvider = $programacion->getprogramacionDiaria($semanas,$proceso,$turno);
        
        if(count($dataProvider)==0){
            return json_encode([
                'total'=>0,
                'rows'=>[],
            ]);
        }
        
        return json_encode([
            'total'=>count($dataProvider->allModels),
            'rows'=>$dataProvider->allModels,
        ]);
    }
    
    public function actionData_diaria2($proceso)
    {
        $Dia = isset($_POST['Dia']) ? $_POST['Dia'] : date('Y-m-d');
        $area = Yii::$app->session->get('area');
        $area = $area['IdArea'];
        
        $this->layout = 'JSON';

        $programacion = new VProgramacionesDia();
        $dataProvider = $programacion->find()->where([
            'IdProceso'=> $proceso,
            'Dia' => $Dia,
            'IdPresentacion' => $area,
        ])->asArray()->all();

        return json_encode([
                'total'=>count($dataProvider),
                'rows'=>$dataProvider,
        ]);
    }
    
    public function actionPedidos()
    {
        $this->layout = 'JSON';
        $model = new Pedidos();
        $dataProvider = $model->getSinProgramar();

        if(count($dataProvider)>0){
            return json_encode([
                'total'=>count($dataProvider->allModels),
                'rows'=>$dataProvider->allModels,
            ]);
        }
        
        return json_encode([
            'total'=>0,
            'rows'=>[],
        ]);
    }
    
    public function actionMarcas()
    {
        $this->layout = 'JSON';
        $model = new Pedidos();
        $dataProvider = $model->getMarcas();

        if(count($dataProvider)>0){
            return json_encode($dataProvider->allModels);
        }
        
        return json_encode([
            'total'=>0,
            'rows'=>[],
        ]);
    }
    
    /**
     * Creates a new programaciones model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new programacion();
        //var_dump(Yii::$app->request->post()); exit;
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->IdProgramacion]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing programaciones model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionSave_semanal()
    {
        $model = new Programacion();
        $data = json_decode($_POST['Data']);
        
        foreach($data as $dat){
            if($dat->Prioridad1 != '0' || $dat->Programadas1 != '0'){
                $datosSemana1 = "$dat->IdProgramacion,$dat->Anio1,$dat->Semana1,$dat->Prioridad1,$dat->Programadas1";
                $model->setProgramacionSemanal($datosSemana1);
            }
            if($dat->Prioridad2 != '0' || $dat->Programadas2 != '0'){
                $datosSemana2 = "$dat->IdProgramacion,$dat->Anio2,$dat->Semana2,$dat->Prioridad2,$dat->Programadas2";
                $model->setProgramacionSemanal($datosSemana2);
            }
            if($dat->Prioridad3 != '0' || $dat->Programadas3 != '0'){
                $datosSemana3 = "$dat->IdProgramacion,$dat->Anio3,$dat->Semana3,$dat->Prioridad3,$dat->Programadas3";
                $model->setProgramacionSemanal($datosSemana3);
            }
        }
        return var_dump($data);
        
    }
    
    public function actionSave_diario()
    {
        $model = new Programacion();
        $data = json_decode($_POST['Data']);
        //var_dump($data);exit;
        foreach($data as $dat){
            if($dat->Prioridad1 != '' || $dat->Programadas1 != ''){
                $datosSemana = "$dat->IdProgramacionSemana,'$dat->Dia1',$dat->Prioridad1,$dat->Programadas1,$dat->IdProceso,$dat->IdTurno,$dat->Maquina1";
                $model->setProgramacionDiaria($datosSemana);
            }
            if($dat->Prioridad2 != '' || $dat->Programadas2 != ''){
                $datosSemana = "$dat->IdProgramacionSemana,'$dat->Dia2',$dat->Prioridad2,$dat->Programadas2,$dat->IdProceso,$dat->IdTurno,$dat->Maquina2";
                $model->setProgramacionDiaria($datosSemana);
            }
            if($dat->Prioridad3 != '' || $dat->Programadas3 != ''){
                $datosSemana = "$dat->IdProgramacionSemana,'$dat->Dia3',$dat->Prioridad3,$dat->Programadas3,$dat->IdProceso,$dat->IdTurno,$dat->Maquina3";
                $model->setProgramacionDiaria($datosSemana);
            }
            if($dat->Prioridad4 != '' || $dat->Programadas4 != ''){
                $datosSemana = "$dat->IdProgramacionSemana,'$dat->Dia4',$dat->Prioridad4,$dat->Programadas4,$dat->IdProceso,$dat->IdTurno,$dat->Maquina4";
                $model->setProgramacionDiaria($datosSemana);
            }
            if($dat->Prioridad5 != '' || $dat->Programadas5 != ''){
                $datosSemana = "$dat->IdProgramacionSemana,'$dat->Dia5',$dat->Prioridad5,$dat->Programadas5,$dat->IdProceso,$dat->IdTurno,$dat->Maquina5";
                $model->setProgramacionDiaria($datosSemana);
            }
            if($dat->Prioridad6 != '' || $dat->Programadas6 != ''){
                $datosSemana = "$dat->IdProgramacionSemana,'$dat->Dia6',$dat->Prioridad6,$dat->Programadas6,$dat->IdProceso,$dat->IdTurno,$dat->Maquina6";
                $model->setProgramacionDiaria($datosSemana);
            }
            if($dat->Prioridad7 != '' || $dat->Programadas7 != ''){
                $datosSemana = "$dat->IdProgramacionSemana,'$dat->Dia7',$dat->Prioridad7,$dat->Programadas7,$dat->IdProceso,$dat->IdTurno,$dat->Maquina7";
                $model->setProgramacionDiaria($datosSemana);
            }
        }
        return var_dump($data);
        
    }
    
    public function actionSave_pedidos()
    {
        $model = new Programacion();
        $pedido = new Pedidos();
        
        $area = Yii::$app->session->get('area');
        $data = json_decode($_GET['Data']);

        foreach($data as $dat){
            $pedidoDat = $pedido->findOne($dat->IdPedido);
            $producto = Productos::findOne($pedidoDat->IdProducto);
            
            $model = new Programacion();
            $model->IdPedido= $pedidoDat->IdPedido;
            $model->IdArea = $area['IdArea'];
            $model->IdEmpleado = Yii::$app->user->identity->IdEmpleado;
            $model->IdProgramacionEstatus = 1;
            $model->IdProducto = $pedidoDat->IdProducto;
            $model->Programadas = 0;
            $model->Hechas = 0;
            
            if (!$model->save()) {
                return var_dump($model);
            }
            
            //var_dump($model);
            $casting = $producto->IdProductoCasting == 1 ? $producto->IdProducto : $producto->IdProductoCasting;
            $almas = Almas::find()->where("IdProducto = $casting")->asArray()->all();

            if(count($almas)>0){
                $programacion = Programacion::find()->where("IdPedido = " . $model->IdPedido . "")->asArray()->all();
                $producto = Productos::findOne(Productos::findOne($model->IdProducto)->IdProducto);
                
                foreach($almas as $alma){
                    $almasProgramadas = new ProgramacionesAlma();
                    $almas_prog['ProgramacionesAlma'] = [
                        'IdProgramacion' => $programacion[0]['IdProgramacion'],
                        'IdEmpleado' => Yii::$app->user->identity->IdEmpleado,
                        'IdProgramacionEstatus' => 1,
                        'IdAlmas' => $alma['IdAlma'],
                        'Programadas' => 0,
                        'Hechas' => 0,
                    ];
                    $almasProgramadas->load($almas_prog);
                    $almasProgramadas->save();
                }
            }
            
        }
        return true;
    }

    /**
     * Deletes an existing programaciones model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete_diario()
    {
        if(isset($_POST['IdProgramacionDia']))
            $this->findProgramacionDia($_POST['IdProgramacionDia'])->delete();
        return "true";
    }

    public function checarSemana($semana){
        $ultimaSemana = date('W',strtotime($semana['año'].'-12-31'));
        if($semana['semana'] == $ultimaSemana || $ultimaSemana == '01'){
            $semana['semana'] = 1;
            $semana['año']++;
        }
        else
            $semana['semana']++;
        $semana['value'] = $semana['año']."-W".(strlen($semana['semana']) ==1 ? "0" : "").$semana['semana'] ;

        return $semana;
    }
    
    

        /**
     * Finds the programaciones model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return programaciones the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findProgramacionDia($id)
    {
        if (($model = ProgramacionesDia::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
