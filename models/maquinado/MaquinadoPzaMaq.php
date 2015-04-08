<?php

namespace frontend\Models\Maquinado;
use Yii;
use yii\base\Model;

Class MaquinadoPzaMaq extends Model {


	public function lstpzamaq(){
			
				 $command = \Yii::$app->db_mysql;
		 $sql="
			select 
			id,
				pieza, 
				maquina, 
				minutos,
				minutos1maquinado,
				OP,
				celda, 
				siguiente
			from pdp_maquina_pieza 
		 ";
		 $result =$command->createCommand($sql)
							->queryAll();
		 
		return $result;

	}
	
	public function pmmaq() {
		 $command = \Yii::$app->db_mysql;
		 $sql="
			select 
				maquina,
				descripcion
			from pdp_maquina 
		 ";
		 $result =$command->createCommand($sql)
							->queryAll();
		 
		return $result;
	
	}
	
	public function pmpza() {
		 $command = \Yii::$app->db_mysql;
		 $sql="
			select 
				identificacion as pieza,
				CONCAT( identificacion,'-',descripcion ) as descripcion
			from maq_piezas
			where 	TP = 'AC'		
		 ";
		 $result =$command->createCommand($sql)
							->queryAll();
		 
		return $result;
	
	}
	
	public function pmop() {
		 $command = \Yii::$app->db_mysql;
		 $sql="
				select op
				from pdp_ops
		 ";
		 $result =$command->createCommand($sql)
							->queryAll();
		 
		return $result;
	
	}
	
	public function pmsig() {
		 $command = \Yii::$app->db_mysql;
		 $sql="
			select 
			id
			from pdp_maquina_pieza
			order by id
		 ";
		 $result =$command->createCommand($sql)
							->queryAll();
		 
		return $result;
	
	}
	
	public function exist($pieza,$maquina,$op) {
			
				$command = \Yii::$app->db_mysql;
		
		$result =$command
					->createCommand("
					
					Select  count(pieza) as m 
					from pdp_maquina_pieza 
					where 
					pieza ='$pieza'  
					and maquina = '$maquina'
					and op = '$op'
					
					")->queryAll();
					
		
		return $result[0]['m'] >  0 ? true : false;
			
		}
	
	public function pmsave($data){
		
		$command = \Yii::$app->db_mysql;
		 echo "salvar"; 
		 $data =  $data[0];
		 $data = (array) $data;
		 print_r($data);
		 
		if (!$this->exist($data['pieza'],$data['maquina'],$data['OP'] ) ){
						
			$result =$command->createCommand()->insert('pdp_maquina_pieza',[
									'pieza' => $data['pieza'], 
									'maquina' => $data['maquina'],
									'minutos' => $data['minutos'],
									'OP' => $data['OP'], 
									'minutos1maquinado' => $data['minutos1maquinado'], 
									'celda' => $data['celda'],
									'siguiente' => $data['siguiente']
									
				])->execute();
			// ])->getRawSql();
			// print_r($result);
			
		}else{
			 $result =$command->createCommand()->update('pdp_maquina_pieza',[
									'minutos' => $data['minutos'],
									'minutos1maquinado' => $data['minutos1maquinado'], 
									'celda' => $data['celda'],
									'siguiente' => $data['siguiente']
										], 	[
										'maquina' => $data['maquina'],
										'op' => $data['OP'], 
										'pieza' => $data['pieza']
										]
									)->execute();
								// )->getRawSql();
								// print_r($result);
			
									
		  }
		
	}
	
	
	public function pmdel($data){
		
			$command = \Yii::$app->db_mysql;
		print_r($data);echo "borrando";
		 $data = (array) $data;
		
		
			
		$result =$command->createCommand()->delete('pdp_maquina_pieza',[
													'maquina'  =>  $data['maquina'], 
													'pieza' =>  $data['pieza'] ,
													'OP' =>  $data['OP'] 
											])->execute();
											// ])->getRawSql();
											// print_r($result);
		
	}

	
	
	





} // fin clase


