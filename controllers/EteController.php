<?php

namespace frontend\controllers;


use frontend\models\ete\captura;
use frontend\models\ete\calculo;
use frontend\models\ete\celda;
use frontend\models\ete\reportes;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

class EteController extends Controller
{
	public function actionLstcap(){
		
		$model = new Captura;
		
		 $id = $_REQUEST['id'];
		// $f  = $_POST['fecha'];
		
		$ete = $model->lstCap($id);
		
		 return json_encode($ete, 0);
	}
	
	
	public function actionReporte()
	{
		$model = new reportes();
		
		$usr = Yii::$app->user->identity; 
		$u   = $usr->IdEmpleado;

		if ($model->load(Yii::$app->request->post())) {
			if ($model->validate()) {
				$model->save();
				
				Yii::$app->mailer->compose()
				 ->setFrom('noreply@fimex.com.mx')
				 ->setTo('crojo@fimex.com.mx')
				 ->setSubject('Reporte de Falla Maquinado')
				 ->setTextBody('Nomina:' $model->nomina . '\n'$model->descripcion)
				 ->send();
				
				// form inputs are valid, do something here
				return $this->render('reporte', ['model' => $model ,'msj' => 'Enviado, Gracias' ]);;
			}
		}
		$model->nomina = $u;
		return $this->render('reporte', ['model' => $model ,'msj' => '' ]);
	}
	
   //inicio para pantalla de captura del ETE 
   public function actionCaptura(){
		$model = new Captura;
		
		
		$usr = Yii::$app->user->identity; 
		  $u  =$usr->IdEmpleado;
		  $dia = $model->detectanoche($u);
		 if ( $dia <> 0){
			 $f  = $dia;
		 }else {
			 $f  = date('Y-m-d');
		 }

	return $this->render('cap', [ 'fecha' => $f , 'usuario' => $u  ]);
		
   }
   
   //crea nuevo ETE (encabezado)
   public function actionNuevoete(){
	  $model = new Captura;
	  
	 
	  
	  $d[] = null;
	  $d['usuario'] = $_REQUEST['operador']; 
	  $d['fecha']= $_REQUEST['fecha'];
	  $d['maquina'] = $_REQUEST['maquina'];
	  $d['idturno'] = $_REQUEST['idturno'];
	
	  
	  $id = $model->saveETE($d);
	  
	  // return json_encode($id, 0);
	  return $id;
	   
   }
   
	//abre maquina dependiendo programacion
	public function actionLoadmaquina($fecha,$op){
		
		$model = new Captura;
		
		$maqs = $model->GetMaquina($fecha,$op);
		
		 return json_encode($maqs, 0);
	}
	
		//abre maquina dependiendo programacion
	public function actionLoadmaquina2(){
		
		$model = new Celda;
	
		$maqs = $model->GetMaquina();
		
		 return json_encode($maqs, 0);
	}
	
	//commbobox de parte
	public function actionLoadparte($fecha,$op,$maq){
		
		$model = new Captura;
			
		$part = $model->GetParte($fecha,$op,$maq);
		
		 return json_encode($part, 0);
	}
	
	//combobox de operacion
	public function actionLoadop($maq,$parte,$fecha){
		
		$model = new Captura;
			
		$op = $model->GetOp($maq,$parte,$fecha);
		
		 return json_encode($op, 0);
	}
	
	public function actionChecaop(){
		$model = new Captura;
		
		$data = array(
		'op' => $_POST['op'],
		'fecha' => $_POST['fecha'],
		'maquina' => $_POST['maquina'],
		'pieza' => $_POST['pieza']
		);
		$op = $model->ChecaOp($data); 
		
		//print_r($_POST);
		echo $op;
		
		
	}
	
	//salva a detalle ete 
	public function actionSavecap(){
		
		$data = json_decode($_POST['Data']);

		$model = new Captura;
		$model->saveCap($data);
	
		
	}
	
	//borra detalle de ete
	public function actionBorracap(){
		
		$model = new Captura;
			$data = json_decode($_POST['Data']);
		 $model->borraCap($data);
		
		
		
	}
	//lista tm 
	public function actionLsttm(){
		
		$model = new Captura;
		
		 $id = $_REQUEST['id'];
		// $f  = $_POST['fecha'];
		
		$tm = $model->lstTM($id);
		
		 return json_encode($tm, 0);
	}
	
	//carga combobox tm
	public function actionLoadtm(){
		
		$model = new Captura;
		
		// $op = $_POST['operador'];
		// $f  = $_POST['fecha'];
		
		$maqs = $model->GetTipoTM();
		
		 return json_encode($maqs, 0);
	}
	
	//salva a detalle TM 
	public function actionSavetm(){
		
		$data = json_decode($_POST['Data']);

		$model = new Captura;
		$model->saveTM($data);
	
		
	}
	
	//borra detalle de TM
	public function actionBorratm(){
		
		$model = new Captura;
		$data = json_decode($_POST['Data']);
		$model->borraTM($data);

		
	}
	
	// celdas
	public function actionCelda(){
		
		return $this->render('celda', [  ]);
	}
	
	//lista celdas
	public function actionLstcelda(){
		
		$model = new Celda;
		
		 $id = $_REQUEST['id'];
		// $f  = $_POST['fecha'];
		
		$celda = $model->lstcelda($id);
		
		return json_encode($celda, 0);
		
	}
	
	//salva celda
	public function actionSalvacelda(){
		$data = json_decode($_POST['Data']);
		

		$model = new Celda;
		$id =$model->saveCelda($data);
		
		return $id;
		
	}

	
	
	
	//reporte ETE new
	public function actionEte(){
		
		if ( isset ($_REQUEST["ini"]) ){
			$ini = $_REQUEST["ini"] ;
		}else{
			$ini  = date('Y-m-d',strtotime('-1 day'));
		}
		
		if ( isset ($_REQUEST["fin"]) ){
			$fin = $_REQUEST["fin"] ;
		}else{
			$fin = date('Y-m-d',strtotime('-1 day'));
		}
		
		if ( isset ($_REQUEST["area"]) ){
			$area = $_REQUEST["area"] ;
		}else{
			$area = "AC";
		}
		
		return $this->render('calculo', [ 'ini' => $ini , 'fin' => $fin  , 'area' => $area]);
		
	
		
	}
	
	//reporte ETE old
	public function actionEtech(){
		
		if ( isset ($_REQUEST["ini"]) ){
			$ini = $_REQUEST["ini"] ;
		}else{
			$ini =  date('Y-m-d',strtotime('-1 day'));
		}
		
		if ( isset ($_REQUEST["fin"]) ){
			$fin = $_REQUEST["fin"] ;
		}else{
			$fin =  date('Y-m-d',strtotime('-1 day'));
		}
		
		if ( isset ($_REQUEST["area"]) ){
			$area = $_REQUEST["area"] ;
		}else{
			$area = "AC";
		}
		
		return $this->render('calculo2', [ 'ini' => $ini , 'fin' => $fin  , 'area' => $area]);
		
	
		
	}
	
	//reporte de capturado bronce /aceros
	public function actionReportecaptura(){
		
		if ( isset ($_REQUEST["ini"]) ){
			$ini = $_REQUEST["ini"] ;
		}else{
			$ini = date('Y-m-d',strtotime('-1 day'));
		}
		
		if ( isset ($_REQUEST["fin"]) ){
			$fin = $_REQUEST["fin"] ;
		}else{
			$fin = date('Y-m-d',strtotime('-1 day'));
		}
		
		if ( isset ($_REQUEST["area"]) ){
			$area = $_REQUEST["area"] ;
		}else{
			$area = "AC";
		}
		
		return $this->render('reportecaptura', [ 'ini' => $ini , 'fin' => $fin  , 'area' => $area]);
		
	
		
	}
	//reporte de capturado tm bronce /aceros
	public function actionReportecapturatm(){
		
		if ( isset ($_REQUEST["ini"]) ){
			$ini = $_REQUEST["ini"] ;
		}else{
			$ini = date('Y-m-d',strtotime('-1 day'));
		}
		
		if ( isset ($_REQUEST["fin"]) ){
			$fin = $_REQUEST["fin"] ;
		}else{
			$fin = date('Y-m-d',strtotime('-1 day'));
		}
		
		if ( isset ($_REQUEST["area"]) ){
			$area = $_REQUEST["area"] ;
		}else{
			$area = "AC";
		}
		
		return $this->render('reportecapturatm', [ 'ini' => $ini , 'fin' => $fin  , 'area' => $area]);
		
	
		
	}
	//reporte de lo programado en la semana en acero 
	public function actionReporteprogramado(){
		
		if ( isset ($_REQUEST["ini"]) ){
			$ini = $_REQUEST["ini"] ;
		}else{
			$ini = date('Y-m-d');
		}
		
		if ( isset ($_REQUEST["fin"]) ){
			$fin = $_REQUEST["fin"] ;
		}else{
			$fin = date('Y-m-d');
		}
		
		if ( isset ($_REQUEST["grupo"]) ){
			$grupo = $_REQUEST["grupo"] ;
		}else{
			$grupo = "nomina_1";
		}
		
		return $this->render('reporteprogramado', [ 'ini' => $ini , 'fin' => $fin  , 'grupo' => $grupo]);
		
	}
	//reporte de lo programado en la semana en bronce 
	public function actionReporteprogramado2(){
		
		if ( isset ($_REQUEST["ini"]) ){
			$ini = $_REQUEST["ini"] ;
		}else{
			$ini = date('Y-m-d');
		}
		
		if ( isset ($_REQUEST["fin"]) ){
			$fin = $_REQUEST["fin"] ;
		}else{
			$fin = date('Y-m-d');
		}
		
		if ( isset ($_REQUEST["grupo"]) ){
			$grupo = $_REQUEST["grupo"] ;
		}else{
			$grupo = "nomina_1";
		}
		
		return $this->render('reporteprogramado2', [ 'ini' => $ini , 'fin' => $fin  , 'grupo' => $grupo]);
		
	}
	
	//reporte ETO acreos / bronce
		public function actionEto(){
		
		if ( isset ($_REQUEST["fecha"]) ){
			$fecha = $_REQUEST["fecha"] ;
		}else{
			$fecha = date('Ymd');
		}
		
		if ( isset ($_REQUEST["fecha2"]) ){
			$fecha2 = $_REQUEST["fecha2"] ;
		}else{
			$fecha2 = date('Ymd');
		}
		
		if ( isset ($_REQUEST["area"]) ){
			$area = $_REQUEST["area"] ;
		}else{
			$area = "MAA";
		}
		
		return $this->render('eto', [ 'fecha' => $fecha , 'fecha2' => $fecha2  , 'area' => $area]);
		
	
		
	}
	
	//reporte ETO mensual acreos / bronce
		public function actionEtom(){
		
		if ( isset ($_REQUEST["fecha"]) ){
			$fecha = $_REQUEST["fecha"] ;
		}else{
			  $month = date('m');
			  $year = date('Y');
			  $fecha = date('Ymd', mktime(0,0,0, $month, 1, $year));
		}
		
		if ( isset ($_REQUEST["fecha2"]) ){
			$fecha2 = $_REQUEST["fecha2"] ;
		}else{
			 $month = date('m');
			 $year = date('Y');
             $day = date("d", mktime(0,0,0, $month+1, 0, $year));
			$fecha2 = date('Ymd', mktime(0,0,0, $month, $day, $year));
		}
		
		if ( isset ($_REQUEST["area"]) ){
			$area = $_REQUEST["area"] ;
		}else{
			$area = "MAA";
		}
		
		return $this->render('etom', [ 'fecha' => $fecha , 'fecha2' => $fecha2  , 'area' => $area]);
		
	
		
	}
	
	//reporte ETO acreos / bronce
		public function actionResumenturnodiarioac(){
			
			$model = new Calculo;
		
		if ( isset ($_REQUEST["ini"]) ){
			$fecha = $_REQUEST["ini"] ;
			// semana a fecha

		}else{
			$fecha = date('Ymd');
			// $sem = date('W');
			// $aio = date('Y');
			// $semana=$aio.'-W'.$sem; 
		}
		
		$fecha = $model->getLunes($fecha);
		$semana= $model->getSemana($fecha);
		
		return $this->render('resumenturnodiario', [ 'fecha' => $fecha,'semana' => $semana ]);
		
	
		
	}
	
	// obtiene asistenacias de relox
	public function actionGetasistencias(){
		//$data = json_decode($_POST['Data']);
		$model = new Calculo;
		
		if ( isset ($_REQUEST['fechaini']) and isset ($_REQUEST['fechafin']) ){
			$model->fechaini = $_REQUEST['fechaini'];
			$model->fechafin = $_REQUEST['fechafin'];
		}else{
			
		$model->fechaini = '2015-01-01';
		$model->fechafin = date('Y-m-d');
			
		}

		// $id =$model->getOperadoresMaquinado();
		$id =$model->getAsistencias();
		
		return $id;
		
	}
	
	
	
}