<?php

namespace frontend\Models\Maquinado;
use Yii;
use yii\base\Model;

Class MaquinadoInserto extends Model {


	public function lstinserto(){
			
				 $command = \Yii::$app->db_mysql;
		 $sql="
			select 
				Area,
				Parte,
				Herramienta,
				Inserto,
				insxherr,
				filos,
				ID
			 from pdp_inserto
			 order by area,parte
		 ";
		 $result =$command->createCommand($sql)
							->queryAll();
		 
		return $result;

	}
	
	public function insertoins() {
		 $command = \Yii::$app->db_mysql;
		 $sql="
			select DISTINCT PRODUCTO , ALMACEN from almprod 
			where almacen in ('IND','INP')
			ORDER BY PRODUCTO 
		 ";
		 $result =$command->createCommand($sql)
							->queryAll();
		 
		return $result;
	
	}
	
	public function insertoherr() {
		 $command = \Yii::$app->db_mysql;
		 $sql="
			select DISTINCT PRODUCTO , ALMACEN from almprod 
			where almacen in ('IND','INP')
			ORDER BY PRODUCTO 
		 ";
		 $result =$command->createCommand($sql)
							->queryAll();
		 
		return $result;
	
	}
	
	public function insertoparte() {
		 $command = \Yii::$app->db_mysql;
		 $sql="
			select 
				DISTINCT PRODUCTO  			from almprod
			ORDER BY PRODUCTO
		 ";
		 $result =$command->createCommand($sql)
							->queryAll();
		 
		return $result;
	
	}
	
	public function insertoarea() {
		 $command = \Yii::$app->db_mysql;
		 $sql="
			select 
				nombre as area
			from pdp_area 
		 ";
		 $result =$command->createCommand($sql)
							->queryAll();
		 
		return $result;
	
	}
	
	public function exist($pieza,$inserto,$herramienta) {
			
				$command = \Yii::$app->db_mysql;
		
			
		
		$sql = 
		"
					
					Select  count(Parte) as m 
					from pdp_inserto 
					where 
					Parte ='$pieza'  
					 and Inserto = '$inserto' 
		";
		
		
		$result =$command
					->createCommand($sql)
					->queryAll();
					// ->getRawSql();
			         // print_r($result);exit;
					
		
		return $result[0]['m'] >  0 ? true : false;
			
		}
	
	public function insertosave($data){
		
		$command = \Yii::$app->db_mysql;
		 echo "salvar"; 
		 $data =  $data[0];
		 $data = (array) $data;
		 print_r($data);
		 
		 if ( !isset($data['Herramienta']) ) $data['Herramienta'] = '';
		 if ( !isset($data['Inserto']) ) $data['Inserto'] = '';
		 
			 
		//if (!$this->exist($data['Parte'],$data['Inserto'] ,$data['Herramienta']) || !isset($data['ID'])  ){
		if ( !isset($data['ID'])  ){
						
			$result =$command->createCommand()->insert('pdp_inserto',[
									'Area' => $data['Area'], 
									'Parte' => $data['Parte'],
									'Herramienta' => $data['Herramienta'],
									'Inserto' => $data['Inserto'], 
									'insxherr' => $data['insxherr'], 
									'filos' => $data['filos']
									
				])->execute();
			// ])->getRawSql();
			// print_r($result);
			
		}else{
			 $result =$command->createCommand()->update('pdp_inserto',[
									'Area' => $data['Area'],
									'Parte' => $data['Parte'], 
									'Herramienta' => $data['Herramienta'],
									'Inserto' => $data['Inserto'],
									'insxherr' => $data['insxherr'],
									'filos' => $data['filos']
										], 	[
										'ID' => $data['ID']
										]
									)->execute();
								// )->getRawSql();
								// print_r($result);
			
									
		  }
		
	}
	
	
	public function insertodel($data){
		
			$command = \Yii::$app->db_mysql;
		print_r($data);echo "borrando";
		 $data = (array) $data;
		
		
			
		$result =$command->createCommand()->delete('pdp_inserto',[
													'ID' => $data['ID']
											])->execute();
											// ])->getRawSql();
											// print_r($result);
		
	}

	
	
	





} // fin clase


