<?php

namespace frontend\controllers;


use frontend\models\ete\captura;
use frontend\models\ete\calcculo;
use frontend\models\ete\celda;
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
	
   //inicio para pantalla de captura del ETE 
   public function actionCaptura(){
  
	  $f  = date('Y-m-d');
	  $u  = "186";

	return $this->render('cap', [ 'fecha' => $f , 'usuario' => $u  ]);
		
   }
   
   //crea nuevo ETE (encabezado)
   public function actionNuevoete(){
	  $model = new Captura;
	  $f  = date('Y-m-d');
	  $u  = "186"; // jalar el usuario logueado
	  
	  $d[] = null;
	  $d['usuario'] = $u; 
	  $d['fecha']= $f;
	  $d['maquina'] = 1;
	  
	  $id = $model->saveETE($d);
	  
	  // return json_encode($id, 0);
	  return $id;
	   
   }
   
	//abre maquina dependiendo programacion
	public function actionLoadmaquina(){
		
		$model = new Captura;
		
		// $op = $_POST['operador'];
		// $f  = $_POST['fecha'];
		
		$maqs = $model->GetMaquina();
		
		 return json_encode($maqs, 0);
	}
	
	//commbobox de parte
	public function actionLoadparte(){
		
		$model = new Captura;
			
		$part = $model->GetParte();
		
		 return json_encode($part, 0);
	}
	
	//combobox de operacion
	public function actionLoadop(){
		
		$model = new Captura;
			
		$op = $model->GetOp();
		
		 return json_encode($op, 0);
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
	
	
}