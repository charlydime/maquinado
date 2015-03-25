<?php

namespace frontend\controllers;

use Yii;
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
class AlmasController extends Controller
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

        $programacion = new ProgramacionesAlma();
        $dataProvider = $programacion->getprogramacionSemanal($semanas,Yii::$app->session->get('area'));
        
        return $this->render('programacion',[
            'title'=>'Programacion diariaProgramacion diaria Almas',
            'semanas'=>$semanas,
            //'programacion'=>$programacion,
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
        
        $programacion = new ProgramacionesAlma();
        $dataProvider = $programacion->getProgramacionSemanal($semanas);
        
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
        $almasProgramadas = new ProgramacionesAlma();
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
                return "true";
            }
            
            //var_dump($model);
            $casting = $producto->IdProductoCasting == 1 ? $producto->IdProducto : $producto->IdProductoCasting;
            $almas = Almas::find()->where("IdProducto = $casting")->asArray()->all();
            if(count($almas)>0){
                $programacion = Programacion::find()->where("IdPedido = " . $model->IdPedido . "")->asArray()->all();
                $producto = Productos::findOne(Productos::findOne($model->IdProducto)->IdProducto);
                
                foreach($almas as $alma){
                    $almas_prog['ProgramacionesAlma'] = [
                        'IdProgramacion' => $programacion[0]['IdProgramacion'],
                        'IdEmpleado' => Yii::$app->user->identity->IdEmpleado,
                        'IdProgramacionEstatus' => 1,
                        'IdAlmas' => $alma['IdAlma'],
                        'Programadas' => 0,
                        'Hechas' => 0,
                    ];
                }

                $almasProgramadas->load($almas_prog);
                $almasProgramadas->save();
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
