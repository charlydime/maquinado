<?php

namespace frontend\Models\ete;
use Yii;
use yii\base\Model;

Class Captura extends Model {
	
	//lista cap
	public function lstCap($id){
		 $cmd = \Yii::$app->db_ete;
		 $sql = "
		 select 
		 [id] as idcap,
			LEFT( convert(TIME, [Hora Inicio]) ,5 )as inicio ,
			LEFT( convert(TIME, [Hora final]) ,5 )as fin ,
			Producto as parte,
			[Num Operacion] as op,
			[Piezas Maquinadas] as maq,
			[Rechazo Fund]as RFun,
			[Rechazo Maq] as RMaq,
			obs as [desc]
			
		 from [Detalle de ETE]
		 where consecutivo = $id
		 ";
		 $result =$cmd->createCommand($sql)
							->queryAll();
		 
		return $result;
	}
	
	//lista tm
	public function lstTM($id){
		 $cmd = \Yii::$app->db_ete;
		  $sql = "
		 select 
			 
			idtiempomuerto  as idtm,
			LEFT( convert(TIME, [Hora Inicio]) ,5 )as inicio ,
			LEFT( convert(TIME, [Hora fin]) ,5 )as fin ,
			tiempomuerto as tm,
			[orden mtto] as omtto,
			[orden setup] as osetup,
			herramienta,
			observaciones as [desc]

			
		 from [Tiempos Muertos]
		 where idconsecutivo = $id
		 ";
		  $result =$cmd->createCommand($sql)
							->queryAll();
		 
		return $result;
		
	}
	
	// obtiene id ete SIN USO 
	public function getId($operador,$fecha){
			
	 $cmd = \Yii::$app->db_ete;
	 //$cmd_ete = \Yii::$app->db_mysql;
	 $sql = "
		 select consecutivo 
		 from ete
		 where Empleado = $operador and Fecha = $fecha
		 ";
		  $result =$cmd->createCommand($sql)
							->queryAll();
		 
		return $result;
		
	}
	
		// verifica si existe ete
	public function existeEte($operador,$fecha,$maquina){
			
				$command = \Yii::$app->db_ete;
		
		$result =$command
					->createCommand("
					
					Select  count(consecutivo) as m 
					from ete
					where 
					Empleado = $operador and 
					Fecha = $fecha and
					idmaquina = $maquina
					
					")->queryAll();
					
		
		return $result[0]['m'] >  0 ? true : false;
		
	}

    
	// verifica si existe cap
	public function existeCap($parte,$op,$id){
			
		$command = \Yii::$app->db_ete;
		
		$result =$command
					->createCommand("
					
					Select  count(consecutivo) as m 
					from [Detalle de ete]
					where 
					producto = '$parte' and 
					[Num Operacion] = $op and 
					consecutivo = $id
					
					
					"
					)->queryAll();
					// )->getRawSql();
							// echo $result;exit;
		
		return $result[0]['m'] >  0 ? true : false;
	}
	
	

	public function saveETE($data){

		// echo "salvar"; 
		 
		 $data = (array) $data;
		 // print_r($data);
		 
		 $command = \Yii::$app->db_ete;
		 if (!$this->existeEte($data['usuario'],$data['fecha'],$data['maquina']))
		 {
			 $result =$command->createCommand()->insert('ETE',[
									'empleado' => $data['usuario'],
									'fecha' => $data['fecha'], 
									'idmaquina' => 1,
									'idturno' => $data['fecha'], //jalar de empleados

							])->execute();
							// ])->getRawSql();
							//echo $result;exit;
			
			$ultimo = 	$command->getLastInsertID();
			
		 }
		
		//echo " ULTIMO ID : $ultimo"; exit;
	 return  $ultimo;
	}
	
	//funcion para insertar nuevo ete
	public function saveCap($data){

		
		 $data =  $data[0];
		 $data = (array) $data;
		 // print_r($data);
		 $command = \Yii::$app->db_ete;
		 if ( !$this->existeCap($data['parte'],$data['op'],$data['ID']) )
		 {  
			
			 $result =$command->createCommand()->insert('Detalle de ete',[
									'Hora Inicio' => $data['inicio'],
									'Hora final' => $data['fin'], 
									'producto' => $data['parte'], 
									'num operacion' =>  $data['op'],
									'Piezas Maquinadas' =>  $data['maq'],
									'Rechazo Fund' =>  $data['RFun'],
									'Rechazo Maq' =>  $data['RMaq'],
									'obs' =>  $data['desc'],
									'consecutivo' =>  $data['ID']
			
							])->execute();
							// ])->getRawSql();
							// echo $result;exit;
			 
		 }else{
			 $result =$command->createCommand()->update('Detalle de ete',[
									'Hora Inicio' => $data['inicio'],
									'Hora final' => $data['fin'], 
									'num operacion' =>  $data['op'],
									'Piezas Maquinadas' =>  $data['maq'],
									'Rechazo Fund' =>  $data['RFun'],
									'Rechazo Maq' =>  $data['RMaq'],
									'obs' =>  $data['desc'],
									'producto' => $data['parte'], 
									
									], 	[
									
									'id' =>  $data['idcap']
									]
						)->execute();
							// ])->getRawSql();
							// echo $result;exit;
			 
		 }
	
	}
	
	// verifica si existe tm
	public function existeTM($tm,$id){
			$command = \Yii::$app->db_ete;
		
		$result =$command
					->createCommand("
					
					Select  count(idconsecutivo) as m 
					from [Tiempos Muertos]
					where 
					tiempomuerto = '$tm' and 
					idconsecutivo = $id 
					
					"
					)->queryAll();
					// )->getRawSql();
							// echo $result;exit;
		
		return $result[0]['m'] >  0 ? true : false;
		
	}

	//funcion para insertar nuevo ete
	public function saveTM($data){

		 $data =  $data[0];
		 $data = (array) $data;
		 // print_r($data);
		 $command = \Yii::$app->db_ete;
		 if ( !$this->existeTM($data['tm'],$data['ID']) )
		 {  
			
			 $result =$command->createCommand()->insert('Tiempos Muertos',[
									'Hora Inicio' => $data['inicio'],
									'Hora fin' => $data['fin'], 
									'tiempoMuerto' =>  $data['tm'],
									'Orden Mtto' =>  $data['omtto'],
									'Orden setup' =>  $data['osetup'],
									'herramienta' =>  $data['herram'],
									'observaciones' =>  $data['desc'],
									'idconsecutivo' =>  $data['ID'],
			
							])->execute();
							// ])->getRawSql();
							// echo $result;exit;
			 
		 }else{
			 $result =$command->createCommand()->update('Tiempos Muertos',[
									'Hora Inicio' => $data['inicio'],
									'Hora fin' => $data['fin'], 
									'tiempoMuerto' =>  $data['tm'],
									'Orden Mtto' =>  $data['omtto'],
									'Orden setup' =>  $data['osetup'],
									'herramienta' =>  $data['herram'],
									'observaciones' =>  $data['desc'],
									
									
									], 	[
									
									'idtiempomuerto' =>  $data['idtm']
									]
						)->execute();
							// )->getRawSql();
							// echo $result;exit;
			 
		 }
	}

	//borra cap
	public function borraCap($data){
	
		$command = \Yii::$app->db_ete;
		print_r($data);echo "borrando";
		$data = (array) $data;

		$result =$command->createCommand()->delete('Detalle de ete',[
												'id' =>  $data['idtm']
											])->execute();
											// ])->getRawSql();
											// print_r($result);
	
	}
	
	//borra TM
	public function borraTM($data){
		$command = \Yii::$app->db_ete;
		print_r($data);echo "borrando";
		$data = (array) $data;

		$result =$command->createCommand()->delete('Tiempos Muertos',[
												'idtiempomuerto' =>  $data['idtm']
											])->execute();
											// ])->getRawSql();
											// print_r($result);
	
	}
	
	
	
	//busca la maquina combobox
	public function GetMaquina(){
		
		 $cmd = \Yii::$app->db_mysql;
		 $sql = "
		 select Maquina+'-'+Descripcion as Descripcion, Maquina as clave , id
		 from pdp_maquina
		 where activa = 1 and len(maquina) <= 7 
		 order by Descripcion
		
		 ";
		  $result =$cmd->createCommand($sql)
							->queryAll();
		 
		return $result;
		
	}
	
	//busca la maquina combobox
	public function GetTipoTM(){
		
		 $cmd = \Yii::$app->db_ete;
		 $sql = "
		 select  idtipotiempmuerto as tm  , [Tiempo Muerto] as obs
		 from [tipo tiemp muerto]
		
		
		 ";
		  $result =$cmd->createCommand($sql)
							->queryAll();
		 
		return $result;
		
	}
	
	//busca  operaciones  combobox
	public function GetOp(){
		
		 $cmd = \Yii::$app->db_mysql;
		  $sql = "
		 select op from pdp_ops
		
		 ";
		  $result =$cmd->createCommand($sql)
							->queryAll();
		 
		return $result;
		
	}
	
		//busca  operaciones  combobox
	public function GetParte(){
		
		 $cmd = \Yii::$app->db_mysql;
		  $sql = "
		 select pieza from pdp_maquina_pieza
			Union
		 select pieza from pdp_maquina_piezabr
		
		 ";
		  $result =$cmd->createCommand($sql)
							->queryAll();
		 
		return $result;
		
	}
	
	
	
   
        
    
}