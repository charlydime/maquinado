<?php

namespace frontend\Models\ete;
use Yii;
use yii\base\Model;

Class Calculo extends Model {
	
	
 public	 $fechaini = 0;
 public	 $fechafin = 0;
 public	 $maquina = 0;
 
 public	 $ete = 0;
	 public	 $disponibilidad = 0;
		 public	 $tiempoDisponible = 0;
		 public	 $tiempoReal = 0;
		 public	 $tiempoMuertoI = 0;
		 public	 $tiempoMuertoII = 0;
			
	 public	 $eficiencia = 0;
		 public	 $produccionReal = 0;
		 public	 $produccionEsperada = 0;

	 public	 $calidad = 0;
		 public	 $rechazosP = 0;
		 public	 $totalesP = 0;
		 
	public $empleados = '';
		 

 
    //funcion que calcula ETE
public function ete($fecha){
	
	
	
} 
//disponibilidad
public function calculaDsponibilidad(){}
public function calculaTiempoProgramadoMaquina(){}
public function getAsistencias(){
	
	$cmdRelox = \Yii::$app->db_relox;
	//coneccion relox
	$this->getOperadoresMaquinado();
	
	$sql = "
	
		select
		entrada.consec as id,
		entrada.nomina, 
		entrada.fecha, entrada.hora as entrada,
		salida.hora as salida ,
		 entrada.descrip, 
		CASE 
		WHEN salida.hora<entrada.hora THEN  timediff(entrada.hora,salida.hora)
		ELSE  timediff(salida.hora,entrada.hora )
		END  as hrs,
		CASE 
		WHEN  salida.hora<entrada.hora THEN TIME_TO_SEC( timediff(entrada.hora,salida.hora) ) / 60
		ELSE TIME_TO_SEC( timediff(salida.hora,entrada.hora) ) /60 
		END as min
		from   
		(
		select * from asistencia as a 
		left join jornada as j on j.id = a.idjornada
		left join  turnos as t on j.Turnos_idturno = t.clave 
		WHERE
		a.fecha  Between '$this->fechaini' and '$this->fechafin' and
		 a.incidencia = 1 and
		a.nomina in (
		$this->empleados
		)
		) as entrada

		LEFT JOIN (

		select * from asistencia as a 
		left join jornada as j on j.id = a.idjornada
		left join  turnos as t on j.Turnos_idturno = t.clave 
		WHERE
		a.fecha  Between '$this->fechaini' and '$this->fechafin' and
		 a.incidencia = 99 and
		a.nomina in (
		$this->empleados         
		)
		) as salida on entrada.fecha = salida.fecha and  entrada.nomina = salida.nomina
	
	";
	
	$result =$cmdRelox->createCommand($sql)->queryAll();
	
	
	$row = []; 
	foreach($result as $r){
		$r = array_values($r);
		array_push ($row,$r) ;
		
	}
	// print_r($row);exit;
	
	$row = array_chunk($row,900);
	
	
	$cmdMaq = \Yii::$app->db_ete;
	$cmdMaq->createCommand()->truncateTable( 'asistencia' )->execute();
	
	$i = 0;
	foreach($row as $r){
	 $cmdM[$i] = \Yii::$app->db_ete;
	 $cmdM[$i]->createCommand()
			->batchInsert('asistencia',['id','nomina','fecha','entrada','salida','descrip','hrs','min'],$r)
	->execute();
	// ->getRawSql();
		// echo $res;
		$i=$i+1;
	}
	
	//return $result;
	
	
	
}

public function calculaTiempoDisponible(){}
public function calculaTiempoMuerto($tipo){//tipo TMI tipo TMII
}
public function getOperadoresMaquinado(){
	//coneccion dux interface
	
	$command = \Yii::$app->db_mysql;
	
		$sql = "
		
			SELECT Empleado.CODIGOANTERIOR
						FROM Empleado
						WHERE (Empleado.ESTATUS<>'Baja') AND (Empleado.PUESTO IN ('MAA 01','MAA 02','MAA 03','MAA 04','MAA 05','MAA 06','MAB 01','MAB 02','MAB 03','PA 01','TH 03'))
						ORDER BY Empleado.CODIGOANTERIOR
		";
		
		$result =$command->createCommand($sql)->queryAll(); 
		
		foreach($result as &$r){
			 $this->empleados .=  $r["CODIGOANTERIOR"].",";	
		}
		//remueve coma final 
		$this->empleados = substr ($this->empleados,0,strlen($this->empleados)-1); 
		
	
}

//-----------------------------------

//eficiencia
public function calculaEficiencia(){}
public function calculaProduccionReal(){}
public function calculaProduccionEsperada(){}


//calidad
public function calculaCalidad(){}
public function calculaRechazoP(){}
public function calculaTotalesP(){}
public function calculaRechazoP_dux(){}
public function calculaTotalesP_dux(){}


   

        
    
}