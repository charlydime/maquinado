<?php

namespace frontend\Models\Maquinado;
use Yii;
use yii\base\Model;

Class MaquinadoMaqOp extends Model {


	public function matriz(){
			
				 $command = \Yii::$app->db_mysql;
		 $sql="
			SELECT
        maquina_operador.operador,
        empleado.NOMBRECOMPLETO,
        maquina_operador.maquina,
        pdp_maquina.Descripcion
    FROM
        maquina_operador
        INNER JOIN empleado ON maquina_operador.operador = empleado.CODIGOANTERIOR
        INNER JOIN pdp_maquina ON maquina_operador.maquina = pdp_maquina.Maquina
    WHERE
        empleado.ESTATUS <> 'Baja'
    ORDER BY maquina_operador.maquina;
		 ";
		 $result =$command->createCommand($sql)
							->queryAll();
		 
		return $result;

	}

	//traelista de operadores
	public function getOperadores() {

		 $command = \Yii::$app->db_mysql;
		 $sql="
			SELECT Empleado.CODIGOANTERIOR, Empleado.NOMBRECOMPLETO
			FROM Empleado
			WHERE (Empleado.ESTATUS<>'Baja') AND (Empleado.PUESTO IN('MAA 01','MAA 02'))
			ORDER BY Empleado.CODIGOANTERIOR
		 ";
		 $result =$command->createCommand($sql)
						  ->queryAll();
		 
		return $result;
		
	}
	
	
	// trae lista de maquinas
	public function getMaquinas() {

		 $command = \Yii::$app->db_mysql;
		 $sql="
			SELECT *
			FROM pdp_maquina
			 
		 ";
		 $result =$command->createCommand($sql)
							->queryAll();
		 
		return $result;
		
	}
	
	//da de alta  maquina operador
	public function SetMaqOp($data){
					
			$result =$command->createCommand()->insert('pdp_cta',[
														'maquina' =>  $data['maquina'], 
														'operador' => $data['operador']
									
											])->execute();
		
	}
	
	//borra maquina operador
	public function DelMaqOp($data){
	print_r($data);exit;
			$result =$command->createCommand()->delete('pdp_maquina_turnos',[
													'maquina'  =>  $data['maquina'], 
													'operador' =>  $data['operador'] 
											])->execute();
	
	}
	
	
	
	





}


