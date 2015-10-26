<?php

namespace frontend\Models\ete;
use Yii;
use yii\base\Model;

Class Calculo extends Model {
	
	
 public	 $fechaini = 0;
 public	 $fechafin = 0;

 
		 
	public $empleados = '';
		 

 


public function getAsistencias2(){
	
	$cmdRelox = \Yii::$app->db_relox;
	//coneccion relox
	$this->getOperadoresMaquinado();
	
	$sql = "
	
		select
		entrada.consec as id,
		entrada.nomina, 
		entrada.fecha, 
			
		 entrada.d as descrip 
		
		from   
		(
		select * from asistencia as a 
		left join jornada as j on j.id = a.idjornada
		left join  turnos as t on j.Turnos_idturno = t.clave 
		LEFT JOIN ( select  clave as idcat_ins ,descrip  as d from cat_inciden) as ins  on a.incidencia = ins.idcat_ins
		WHERE
		a.fecha  Between '$this->fechaini' and '$this->fechafin' and
		 a.incidencia in (
			6,7,9,18,2,7,15,16,17,18,19,20,21,22,43
			) and
		a.nomina in (
		$this->empleados
		)
		) as entrada

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
	$cmdMaq->createCommand()->truncateTable( 'inasistencia' )->execute();
	
	$i = 0;
	foreach($row as $r){
	 $cmdM[$i] = \Yii::$app->db_ete;
	 $res = $cmdM[$i]->createCommand()
			->batchInsert('inasistencia',['id','nomina','fecha','descrip'],$r)
	->execute();
	// ->getRawSql();
		 echo $res;
		$i=$i+1;
	}
	
	//return $result;
	
	
	
}

public function getAsistencias(){
	
	$cmdRelox = \Yii::$app->db_relox;
	//coneccion relox
	$this->getOperadoresMaquinado();
	
	$sql = "
	
		select
		DISTINCTROW
		entrada.consec as id,
		entrada.nomina, 
		entrada.fecha, entrada.hora as entrada,
		salida.hora as salida ,
		 entrada.descrip, 
		CASE 
		WHEN salida.hora<entrada.hora THEN  timediff(entrada.hora,salida.hora)
		ELSE  timediff(salida.hora,entrada.hora )
		END  as hrs,
		
		if(
				CASE 
					WHEN salida.hora<entrada.hora THEN 1440-TIME_TO_SEC( timediff(entrada.hora,salida.hora) ) / 60
					ELSE TIME_TO_SEC( timediff(salida.hora,entrada.hora) ) /60 
				END 
				>
				CASE 
					WHEN entrada.salida<entrada.entrada THEN 1440-TIME_TO_SEC( timediff(entrada.entrada,entrada.salida) ) / 60
					ELSE TIME_TO_SEC( timediff(entrada.salida,entrada.entrada) ) /60 
				END 

				,
				CASE 
					WHEN entrada.salida<entrada.entrada THEN 1440-TIME_TO_SEC( timediff(entrada.entrada,entrada.salida) ) / 60
					ELSE TIME_TO_SEC( timediff(entrada.salida,entrada.entrada) ) /60 
				END
				,
				CASE 
					WHEN salida.hora<entrada.hora THEN 1440-TIME_TO_SEC( timediff(entrada.hora,salida.hora) ) / 60
					ELSE TIME_TO_SEC( timediff(salida.hora,entrada.hora) ) /60 
				END 
		) as min
		
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



public function getLunes($fecha){
	$command = \Yii::$app->db_mysql;
	
		$sql = "
		
			SELECT  format( 
					cast( DATEADD(wk,DATEDIFF(wk,0,cast ('$fecha' as date) ),0)  as date ) ,
					'yyyyMMdd',
					'en-US'
					) as lunes
		";
	$result =$command->createCommand($sql)->queryAll();

return $result[0]['lunes']	;
	
}
public function getSemana($fecha){
	$command = \Yii::$app->db_mysql;
	
		$sql = "
		
			SELECT   datepart(wk,'$fecha') as semana
		";
	$result =$command->createCommand($sql)->queryAll();

return $result[0]['semana']	;
	
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


   

        
    
}