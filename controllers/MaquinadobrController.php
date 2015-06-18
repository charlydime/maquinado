<?php

namespace frontend\controllers;

// TODO : insercion en prgceldas.
use frontend\models\maquinadobr\maquinadoped;
use frontend\models\maquinadobr\maquinadoMaqOp;
use frontend\models\maquinadobr\maquinadocta;
use frontend\models\maquinadobr\maquinadocta2;
use frontend\models\maquinadobr\maquinadocta4;
use frontend\models\maquinadobr\maquinadoPzaMaq;
use frontend\models\ete\celdaprg;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

class MaquinadobrController extends Controller
{
   public function actionIndex(){
       echo "INDEX controller";
   
      
   }
   
    public function actionPed(){ //obsdoleto
   
        $model = new MaquinadoPED;
        
        $Paroen = $model->GetPorMaquinar('2014-01-01');
        $maquinapieza = $model->GetMaquinaPieza();
                
              $semana = date('Y-m-d');
        
        return $this->render('ped', ['semana' => $semana,  ]);
   
    }
    
    
    public function actionPedd($fecha ){
        
        $model = new MaquinadoPED;
        
        $Paroen = $model->GetPorMaquinar($fecha);
        
        return json_encode($Paroen, 0);
        
    }
    
    public function actionCta(){
        
        
              return $this->render('cta', [ ]);
        
    }
    
    public function actionCtad(){
        $model = new maquinadoCTA;
        $Paroen = $model->GetMaquinaPieza();
        return json_encode($Paroen, 0);
        }
		
	public function actionCta2(){
   
	          if ( isset( $_REQUEST['fecha'] )){
	          $semana = $_REQUEST['fecha'];
			  }else {
				$sem = date('W');
			  $aio = date('Y');
			  $semana=$aio.'-W'.$sem;  
			  }
        return $this->render('cta2', ['semana' => $semana,  ]);
   
    }
	
	public function actionCta2d(){

        $model = new MaquinadoCta2;
        
		   $fecha=$_REQUEST['fecha'];
		
        
		$cat = $model->GetInfo($fecha);
                    
        return json_encode($cat, 0);
        
   
    }
	
	public function actionCta3d($fecha){

        $model = new MaquinadoCta2;
        if ( isset( $_POST['semana']) and $_POST['semana'] = ''){
		   $fecha=$_POST['semana'];
		} ;
        
		$cat = $model->GetInfo_Maquina($fecha);
                    
        return json_encode($cat, 0);
        
   
    }
	
	public function actionCta4d($fecha){

        $model = new MaquinadoCta2;
        if ( isset( $_POST['semana']) and $_POST['semana'] = ''){
		   $fecha=$_POST['semana'];
		} ;
        
		$cat = $model->GetInfo_Operador($fecha);
                    
        return json_encode($cat, 0);
        
   
    }
	
		public function actionCta4cd($fecha){

        $model = new MaquinadoCta2;
        if ( isset( $_POST['semana']) and $_POST['semana'] = ''){
		   $fecha=$_POST['semana'];
		} ;
        
		$cat = $model->GetInfo_pza_op($fecha);
                    
        return json_encode($cat, 0);
        
   
    }
	
	
	
	public function actionCtap2(){
		$model = new MaquinadoCta2;
        $datos_a_grabar[]= null;
        
			
		$data = json_decode($_POST['Data']);
		
		$datos_a_grabar['maquina']= $data->{'maquina'}; 
		$datos_a_grabar['Matutino']= $data->{'Matutino'}; 
		$datos_a_grabar['Vespertino']= $data->{'Vespertino'}; 
		$datos_a_grabar['Nocturno']= $data->{'Nocturno'}; 
		$datos_a_grabar['Mixto']= $data->{'Mixto'}; 
		$datos_a_grabar['minutos_m']= $data->{'minutos_m'}; 
		$datos_a_grabar['semana']= $data->{'semana'}; 
		
		
		$model->p2save($datos_a_grabar);
	}
	
	public function actionCtap1(){
	
		$model = new MaquinadoCta2;
        $datos_a_grabar[]= null;
        
			
			$datos = json_decode($_POST['Data']);
			 // $data = json_decode('

			 // {"producto":"100217654(BSK-13076)","e1":null,"e2":null,"PLA":"30","CTA":"10","PMA":null,"PTA":"0",
			 // "maquina1":"CMH 002",
			 // "s1":"8","maquina2":null,
			 // "s2":"9","maquina3":null,
			 // "s3":"10","maquina4":null,
			 // "s4":"11",
			 // "prioridad":"","sem_actual":"2015-W05"}

			 // ');
			// echo "cta1------------------------";print_r ($datos);
			 //programar busqueda cuantas operaciones tiene la pieza
			 
			foreach ($datos as $data){ 
			echo "for------------------------";print_r ($data);
					if ($data->{'maquina1'} == '') exit;
			 
					//$maquina=explode('-',$data->{'maquina1'});
		
					//armo arreglo de datos a grabar
					$semana = explode('-',$data->{'sem_actual'});
					
					$datos_a_grabar['producto']= $data->{'producto'}; 
					// $datos_a_grabar['minutos']= $model->p1tiempos($data->{'producto'},$data->{'maquina1'},$data->{'opx'});
					$datos_a_grabar['minutos']= $data->{'Minutos'};
					$datos_a_grabar['maquina']= $data->{'maquina1'};
					
					$datos_a_grabar['aio']= 	$semana[0] ;
					$datos_a_grabar['semEnmtrega']= $data->{'sem_actual'};
					$datos_a_grabar['prioridad']= $data->{'prioridad'};
					$datos_a_grabar['opx']= $data->{'opx'};
					$datos_a_grabar['programado']= substr($semana[1],1);
					
					
					// print_r($data);
					
						//grabo recibiendo de diario
						if($data->{'diario'} != '' && $data->{'diario'} != 'n') {
							 $datos_a_grabar['cantidad'] =$data->{'diario'};
							 $datos_a_grabar['semana']= substr($semana[1],1)-0;
							 $model->p1save($datos_a_grabar);
							echo "dia datos_a_grabar------------"; print_r($datos_a_grabar);
							
						}else{
							//grabo hold  lo actualiza con su valor capturado
							$model->hold($data); 
							
						}	
					//grabo  solo si alguna de las semanas  tiene capturado algo de piezas 
				
						if($data->{'s1'} != '' && $data->{'s1'} != 'n') {
							$datos_a_grabar['cantidad'] =$data->{'s1'};
							$datos_a_grabar['semana']= substr($semana[1],1)-0;
							$model->p1save($datos_a_grabar);
							 // print_r($datos_a_grabar);
							
						}	
					
						if($data->{'s2'} != '' && $data->{'s2'} != 'n') {
							$datos_a_grabar['cantidad'] =$data->{'s2'};
							$datos_a_grabar['semana']= substr($semana[1],1)+1;
							$model->p1save($datos_a_grabar);
							 // print_r($datos_a_grabar);
							
						}	
						
						if($data->{'s3'} != '' && $data->{'s3'} != 'n') {
							$datos_a_grabar['cantidad'] =$data->{'s3'};
							$datos_a_grabar['semana']= substr($semana[1],1)+2;
							$model->p1save($datos_a_grabar);
							 // print_r($datos_a_grabar);
							
						}	
						
						if($data->{'s4'} != '' && $data->{'s4'} != 'n') {
							$datos_a_grabar['cantidad'] =$data->{'s4'};
							$datos_a_grabar['semana']= substr($semana[1],1)+3;
							$model->p1save($datos_a_grabar);
							 // print_r($datos_a_grabar);
							
						}	
			}			
						
		
   }
   
  public function actionCtap1hold(){
		$model = new MaquinadoCta2;
		$data = json_decode($_POST['Data']);
		$model->hold($data);
		}
   
   public function actionCta2p1maquina($pieza,$op){
	
		 $model = new MaquinadoCta2;
		 $maqpieza = $model->maquinapieza($pieza,$op);
		return json_encode($maqpieza, 0);
   }
   
   
    public function actionCta3p2operador($maquina){
	
		 $model = new MaquinadoCta2;
		 $maqop = $model->maquinaoperador($maquina);
		
		return json_encode($maqop, 0);
   }
   
   
   public function actionMaqop(){
	
		 return $this->render('maqop', [  ]);
   
   }
   
    public function actionMaqopd(){
	
			$model = new MaquinadoMaqOp;
			$maqop = $model->matriz();
			return json_encode($maqop, 0);
   
   }
   
   public function actionMaq(){
	
		$model = new MaquinadoMaqOp;
			$maqop = $model->getMaquinas();
			return json_encode($maqop, 0);
   
   }
   
   public function actionOp(){
	
		$model = new MaquinadoMaqOp;
			$maqop = $model->GetOperadores();
			return json_encode($maqop, 0);
   
   }
      
	public function actionMosave(){
	
					$data = json_decode($_POST['Data']);
	
					$model = new MaquinadoMaqOp;
					$maqop = $model->SetMaqOp($data);
   
   }
   
   public function actionCtaprog(){
	          
	          $sem = date('W');
			  $aio = date('Y');
			  $semana=$aio.'-W'.$sem;
        
		 if ( isset( $_POST['semana']) and $_POST['semana'] == ''){
		   $semana=$_POST['semana'];
		} ;
		
        return $this->render('ctaprog', ['semana' => $semana,  ]);
   
    }
	
	 public function actionCtaprogd(){
		
		     $sem = date('W');
			  $aio = date('Y');
			  $semana=$aio.'-W'.$sem;
        
		 if ( isset( $_POST['semana']) and $_POST['semana'] == ''){
		   $semana=$_POST['semana'];
		} ;
		
		$model = new MaquinadoCTA2;
			$prog = $model->GetInfo_programacion($semana);
			return json_encode($prog, 0);
   
    }
	//cta 4
	public function actionCta4(){
   
	          
	          $sem = date('W');
			  $aio = date('Y');
			  $semana=$aio.'-W'.$sem;
        
        return $this->render('cta4', ['semana' => $semana,  ]);
   
    }
   //diario (1 semana)
   	 public function actionCta4data(){
		
		     $sem = date('W');
			 $aio = date('Y');
			 $semana=$aio.'-W'.$sem;
        
		 if ( isset( $_POST['semana']) and $_POST['semana'] == ''){
		   $semana=$_POST['semana'];
		} ;
		
		$model = new MaquinadoCTA4;
			$prog = $model->GetInfo($semana);
			return json_encode($prog, 0);
   
    }
	
	public function actionCta4salva(){
		
		
		$model = new MaquinadoCta4;
       
        
		$data = json_decode($_POST['Data']);
		$sem = json_decode($_POST['semana']);
		
				
		$model->save_dia($data,$sem);
		
		
	}
	//diario 
	
	
	
	public function actionCta5(){
   
	  //$fecha=date('Y-m-d');
		$fecha = '2015-03-02';
      return $this->render('cta5', ['fecha' => $fecha  ]);
   
    }
	
	
	public function actionCta5d(){
		
			$fecha = $_POST['fecha'];
		
			$model = new MaquinadoCTA4;
			$prog = $model->GetInfo_op($fecha);
			return json_encode($prog, 0);
		
		
	}
	
	public function actionOpsave(){
		
		$model = new MaquinadoCta4;
        
        
		$data = json_decode($_POST['Data']);
		$dia = $_POST['dia'];
		
				
		$model->save_opturno_p1($data,$dia);
		
	}
	
	public function actionLstop(){
		
			$fecha = $_POST['fecha'];
		
			$model = new MaquinadoCTA4;
			$prog = $model->GetInfo_diaop($fecha);
			
			return json_encode($prog, 0);
		
		
	}
	
	//-------------------MaquinaPza----------------------
	
	public function actionLstpzamaq(){
		
			$model = new MaquinadoPzaMaq;
			
			$r = $model->lstpzamaq();
			
			return json_encode($r,0);

	}
	public function actionPzamaq(){
		
	 return $this->render('pzamaq', []);
	}
	
	public function actionPmpza(){
		
		$model = new MaquinadoPzaMaq;
			
		$r = $model->pmpza();
			
		return json_encode($r,0);

		
	}
	
	public function actionPmmaq(){
		
		$model = new MaquinadoPzaMaq;
			
		$r = $model->pmmaq();
		
		return json_encode($r,0);

		
	}
	
	public function actionPmsig(){
		
		$model = new MaquinadoPzaMaq;
			
		$r = $model->pmsig();
		
		return json_encode($r,0);

	}
	
	public function actionPmop(){
		
		$model = new MaquinadoPzaMaq;
			
		$r = $model->pmop();
		
		return json_encode($r,0);

	}
	
	public function actionPmsave(){
		
		$model = new MaquinadoPzaMaq;
                
		$data = json_decode($_POST['Data']);
						
		$model->pmsave($data);
		
		
	}
	
	public function actionPmdel(){
		
		$model = new MaquinadoPzaMaq;
                
		$data = json_decode($_POST['Data']);
						
		$model->pmdel($data);
		
		
	}
	
	//---------------celdaprg
	
	// celdas
	public function actionCelda(){
		
		return $this->render('celda', [  ]);
	}
	
	
	//lista celdas FORM
	public function actionLstcelda(){
		
		$id = 0;
		$model = new Celdaprg;
		if (isset($_REQUEST['id']))
			$id = $_REQUEST['id'];
		if (isset($_REQUEST['sem']))
			$sem = $_REQUEST['sem'];
	
		// $f  = $_POST['fecha'];
		
		$celda = $model->lstcelda($id,$sem);
		
		return json_encode($celda, 0);
		
	}
	
	//salva celda FORM
	public function actionSalvacelda(){
		
		$data = json_decode($_POST['Data']);

		$model = new Celdaprg;
		$id =$model->saveCelda($data);
		
		return $id;
		
	}
	
	//obtiene id de celda FORM
	public function actionGetcelid(){
		//$data = json_decode(']);
		
		// print_r($_GET['maquina']);exit;
		
		
		$model = new Celdaprg;
		$id =$model->getCelId( $_GET['maquina']  );
		
		return $id;
		
	}
	
	//obtiene nombre de celda FORM
	public function actionGetcelname(){
		//$data = json_decode(']);
		
		// print_r($_GET['maquina']);exit;
		
		
		$model = new Celdaprg;
		$id =$model->getCelName( $_GET['idcelda']  );
		
		return $id;
		
	}
	
	
	
	
	
}