<?php

namespace frontend\Models\ete;
use Yii;
use yii\base\Model;

Class Celdaprg extends Model {
    

   public function lstcelda($celda,$sem){
	   
	    $cmd = \Yii::$app->db_mysql;
		  $sql = "
		 select [codigo Maquina] as maquina, maq.Maquina as clave, Descripcion	
		from pdp_celda
		LEFT JOIN  pdp_maquina as maq  on maq.id = pdp_celda.[Codigo Maquina]
		 where idcelda = $celda
		 ";
		  $result =$cmd->createCommand($sql)
							->queryAll();
		 
		return $result;
		
		
		// $celdas = $this->obtiene_celda($celda);
		
		// print_r($celdas);
	   // return $celdas;
   }
   
   public function saveCelda($data){
	   
	   $cmd = \Yii::$app->db_mysql;
	   $data = (array) $data;
	    $descripcion = $data['descripcion'];
	   $maquinas = $data['maquinas'];
	   $idcel = $data['id'];
	   	   	   
		// concatena claves
		$clave = $this->creaClave($maquinas); 
	   
	   if ($idcel > 0){
	   // borra todas las maquinas par aponerlas de nuevo
	   $result =$cmd->createCommand()->delete('pdp_celda',[
								'idcelda' => $idcel
								])->execute();
							// ])->getRawSql();
							// print_r($result);
							$id = $idcel;
	   }else{ // crea la celda 
	   //obtiene el id de la celda nueva
	   $id = $this->creaCeldaMaquina($descripcion,$clave);
	   }
	   
		foreach($maquinas as $m){
			$m = (array) $m;
			$mq = $m['maquina'];
			if ($mq != ''){
				$result =$cmd->createCommand()->insert('pdp_celda',[
										'Codigo Maquina' => $mq,
										'idcelda' => $id
										])->execute();
								// ])->getRawSql();
								// echo $result;
				$result2 =  $cmd->createCommand()->update('pdp_maquina',[
									
									'activa' => 0
									
									], 	[
									
									'id' => $mq
									]
						)->execute();
							// ])->getRawSql();
							// echo $result;exit; 
			}
		}
		
		// if ( $id > 0  )
			// $res = json_encode ( array( 'success' => $id  ) );
		// else 
			// $res = json_encode ( array( 'error' => "NO id" ) );
		

	   return $id ;
		}
   
   
   public function creaCeldaMaquina($descripcion,$clave){
	   $cmd = \Yii::$app->db_mysql;
	   
	   $result =$cmd->createCommand()->insert('pdp_maquina',[
									'activa' => 1,
									'descripcion' => $descripcion,
									'maquina' => $clave
									
									])->execute();
			// ])->getRawSql();
		// echo $result;
		return $cmd->getLastInsertID();	
	   
	   
   }
   
   public function creaClave($maqs){
	   
	   $cmd = \Yii::$app->db_mysql;
	   $maqs = (array) $maqs;
	   
	    $clave = '';
	   foreach($maqs as $m){
		    $m = (array) $m;
			 $mq = $m['maquina'];
			
		    $sql = "
		 select maquina as m 
		 from pdp_maquina
		 where id = $mq
		 ";
		  
		  
		  if ($mq != '') { //print_r($sql); 
		  $result = $cmd->createCommand($sql)
							->queryAll();
		   
		  $clave .= $result[0]['m'].'-';
		  }
	   }
	   
	   $clave = substr($clave,0,strlen($clave)-1);
	   	   	   
	  return $clave;
	   
   }
        
    public function getCelId($maquina){
		
	
		
		 $cmd = \Yii::$app->db_mysql;
		$id = 0;
		 $sql = "
			select top 1 id from pdp_maquina
			where maquina = '$maquina'
			";
		 
		
		if ($maquina != '') { 
		
		  $result = $cmd->createCommand($sql)
							->queryAll();
		  $id = $result[0]['id'];
		  }
		
		return $id;
		
	}
	
	public function getCelName($id){
		
	
		
		 $cmd = \Yii::$app->db_mysql;
		
		$name = '';
		 $sql = "
			select top 1  Descripcion+ '-' +maquina  as  name 
			from pdp_maquina
			where id  = $id
			";
		 
		
		if ($id != '') { 
		
		  $result = $cmd->createCommand($sql)
							->queryAll();
		  $name = $result[0]['name'];
		  }
		
		return $name;
		
	}

public function existCeldaPrg(){
	echo "existe";
}

	
public function saveCeldaPrg(){
	$this->existCeldaPrg();
	echo "guarda";
}


//busca la maquina combobox
	public function GetMaquina(){
		
		 $cmd = \Yii::$app->db_mysql;

		 // restringido----------------------------
		 	 // select DISTINCT m.Maquina+'-'+m.Descripcion as Descripcion, m.Maquina as clave , m.id
		// from pdp_maquina as m

		// LEFT JOIN (
		// select * from pdp_cta_dia 
		// union 
		// select * from pdp_ctb_dia 

		// ) as pdp_ct on pdp_ct.maquina = m.Maquina
		// where pdp_ct.dia =  '$fecha'
		
		//sin rectriccion------------------------
				  // select Maquina+'-'+Descripcion as Descripcion, Maquina as clave , id
		 // from pdp_maquina
		 // where activa = 1 
		 // order by  Descripcion
		 $sql = "

select DISTINCT m.Maquina+'-'+m.Descripcion as Descripcion, m.Maquina as clave , m.id
		from pdp_maquina as m
		where len(maquina) <= 7 and activa = 1
		
		
		 ";
		  $result =$cmd->createCommand($sql)
							->queryAll();
		 
		return $result;
		
	}



//escribe la tabla prg_celda cuando salva la cantidad programada
public function savePrgCelda($data,$id_pdp_cta=0){
	$ok = 0 ; 
	if ($this->es_celda($data['maquina'])){ $ok=1	;}
	

	if ($ok ==1){
		$idCelda = $this->getCelId($data['maquina']);	
		$celdas = $this->obtiene_celda($idCelda);
		$this->graba_maq_prg_celda($celdas,$id_pdp_cta,$data);
	}
	
}

public function es_celda($celda){
	
	if ( strlen($celda) > 7 ) return true;
	else return false; 
	
}


public function obtiene_celda($idCelda){

$command = \Yii::$app->db_mysql;

$sql = "
	select [codigo Maquina] as maquina, maq.Maquina as clave, Descripcion, maq.id as idmaq
		from pdp_celda
		LEFT JOIN  pdp_maquina as maq  on maq.id = pdp_celda.[Codigo Maquina]
where idcelda = $idCelda
	";
	
	$result =$command->createCommand($sql)
							->queryAll();
	return $result;
}

public function existeprg_celda($maquina,$semana){
	
	$command = \Yii::$app->db_mysql;
		
		$sql = "
					
					Select  count(id_maquina) as m 
					from pdp_prgceldas
					where id_maquina= $maquina and semana = $semana
					
					";
		
		$result =$command->createCommand($sql)
		->queryAll();
		// ->getRawSql();
		// print_r($result);
					
		
		return $result[0]['m'] >  0 ? true : false;
	
	
}

public function graba_maq_prg_celda($celdas,$id_pdp_cta,$data){
	
	$command = \Yii::$app->db_mysql;
	echo "Celdas:----\n"; print_r($celdas);
	foreach($celdas as $c){
		echo "C:----\n"; print_r($c);
			if (!$this->existeprg_celda($c['idmaq'],$data['semana'])){
				$result =$command->createCommand()
									->Insert('pdp_prgceldas',
									['id_pdp_cta' => $id_pdp_cta,
									 'id_maquina' => $c['idmaq'],
									 'semana' => $data['semana'],
									 'activa' => 1
									]
				
									
							)->execute();
						// )->getRawSql();
						// print_r($result);
			}else{
				$result =$command->createCommand()->update('pdp_prgceldas',[
										'activa' => $data['maquina'],
										'razon' => $data['prioridad'],
										], 	[
										'id_maquina' => $c['idmaq'],
										'semana' => $data['semana']
										]
									)->execute();
								// )->getRawSql();
								// print_r($result);
			}
	}
	
}




	
}


