<?php

namespace frontend\Models\ete;
use Yii;
use yii\base\Model;

Class Celda extends Model {
    

   public function lstcelda($id){
	   
	    $cmd = \Yii::$app->db_mysql;
		  $sql = "
		 select [codigo Maquina] as maquina, maq.Maquina as clave, Descripcion	
		from pdp_celda
		LEFT JOIN  pdp_maquina as maq  on maq.id = pdp_celda.[Codigo Maquina]
		 where idcelda = $id
		 ";
		  $result =$cmd->createCommand($sql)
							->queryAll();
		 
		return $result;
		
	   
   }
   
   public function saveCelda($data){
	   
	   $cmd = \Yii::$app->db_mysql;
	   $data = (array) $data;
	    $descripcion = $data['descripcion'];
	   $maquinas = $data['maquinas'];
	   	   	   
		// cocatena claves
		$clave = $this->creaClave($maquinas); 
		
	   //id de la celda
	   $id = $this->creaCeldaMaquina($descripcion,$clave);
	   
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
	   return $id;
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
        
    
}