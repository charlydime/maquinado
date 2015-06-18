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
					Fecha = '$fecha' and
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
	
	
// detecta si el operador esta en el tuno nocturno del dia actual
//si es verdadero retorna el dia de ayer
public function detectanoche($op){
		$command = \Yii::$app->db_mysql;
		
        $result =$command->createCommand("
		
		select count(dia) as m ,cast (dateadd( day, -1 ,getdate() ) as date ) as d   from  pdp_maquina_turno_dia
		where turno = 'Nocturno' and
		dia =  cast (dateadd( day, -1 ,getdate() ) as date ) and 
		op = $op
		
		")->queryAll();
		
		
		
		return $result[0]['m'] >  0 ? $result[0]['d'] : false;
	
}

public function ChecaOp($data){
	
	 // [op] => 30
    // [fecha] => 2015-06-17
    // [maquina] => 
    // [pieza] => 005154-00X
	
		$command = \Yii::$app->db_mysql;
		$fecha= $data['fecha'];
		$maquina= $data['maquina'];
		$op= $data['op'];
		$pieza= $data['pieza'];
		
        $result =$command->createCommand("
		
		select count(op) as m  from pdp_cta_dia  
		where 
		dia =  '$fecha' and 
		maquina = '$maquina' and 
		op =  $op and
		pieza = '$pieza'

		
		"
		)->queryAll();
		// ])->getRawSql();
		// echo $result;exit;
	
		return $result[0]['m'] ;
}

	// trae los horarios del turno 
	public function datosTurno($idturno){
		
		$command = \Yii::$app->db_ete;
		
        $result =$command->createCommand("
		
		select idturno,cast(hinicio as time) as hinicio,cast(htermino as time) as htermino,Turno from turnos where idturno = $idturno
		")->queryAll();
		
		
		
		return $result;
		
	}
	
	public function getUltimo(){
		
		$command = \Yii::$app->db_ete;
		
        $result =$command->createCommand("
		
		select idturno,cast(hinicio as time) as hinicio,cast(htermino as time) as htermino,Turno from turnos where idturno = $idturno
		")->queryAll();
		
		
		
		return $result;
	}

	public function saveETE($data){

		// echo "salvar"; 
		 
		 $data = (array) $data;
		  // print_r($data);
		 
		 $command = \Yii::$app->db_ete;
		 $data['fecha'] = str_replace('-','', $data['fecha']);
		 // $data['fecha'] =  $data['fecha'] ;
		 $turno = $this->datosTurno($data['idturno']);
		 //print_r($turno); exit;
		 // if (!$this->existeEte($data['usuario'],$data['fecha'],$data['maquina']))
		 // {
			 $result =$command->createCommand()->insert('ETE',[
									'empleado' => $data['usuario'],
									'fecha' => $data['fecha'], 
									'idmaquina' => $data['maquina'],
									'hini' => $turno[0]['hinicio'],
									'hfin' => $turno[0]['htermino'],
									'idturno' => $turno[0]['idturno']

							])->execute();
							// ])->getRawSql();
							// echo $result;exit;
			
			$ultimo = 	$command->getLastInsertID();
			
		
		 // } else{
			 // indica que el operador ya existe en la maquina mandando 0
		 // $id = %this->traeUltimo($data['usuario'],$data['fecha'],$data['maquina']);
		 // }
		
		//echo " ULTIMO ID : $ultimo"; exit;
	 return  $ultimo;
	}
	
	//funcion para insertar nuevo ete
	public function getoppza($parte,$op,$id){
		 $command = \Yii::$app->db_mysql;
		 
		
		
        $result =$command->createCommand("
		
		select * from pdp_maquina_pieza as mp
		LEFT JOIN ete.dbo.ETE as e on  e.consecutivo  = $id
		left join pdp_maquina  as m on m.id = e.idmaquina
		where
		mp.pieza = '$parte'
		and mp.op = $op
		and  mp.maquina = m.maquina


		")->queryAll();
		
		
		
		return $result;
	}

	
	//funcion para insertar nuevo ete
	public function saveCap($data){

		
		 $data =  $data[0];
		 $data = (array) $data;
		  // print_r($data);
		 $command = \Yii::$app->db_ete;
		 
		 $op = $this->getoppza($data['parte'],$data['op'],$data['ID']);
		 // print_r($op);exit;
		 
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
									'consecutivo' =>  $data['ID'],
									'tiempoOperacion' => $op[0]['Minutos']
			
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
												'id' =>  $data['idcap']
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
	public function GetMaquina($fecha,$op){
		
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
		//actual restringido -----------
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
		 
		 //MASS restriccion
		 // select 
// DISTINCT m.Maquina+'-'+m.Descripcion as Descripcion, m.Maquina as clave , m.id
// ,pdp_ct.op
		 // from pdp_maquina as m

		 // LEFT JOIN (
		 // select * from pdp_maquina_turno_dia 
		 // union 
		 // select * from pdp_maquina_turno_diabr 

		 // ) as pdp_ct on pdp_ct.maquina = m.Maquina
		 // where 
// pdp_ct.dia =  '2015-06-16' and
// pdp_ct.op = 
		 $sql = "

		 select 
		DISTINCT m.Maquina+'-'+m.Descripcion as Descripcion, m.Maquina as clave , m.id
		,pdp_ct.op
		 from pdp_maquina as m

		 LEFT JOIN (
		 select dia,maquina,turno,minutos,
			CASE 
				WHEN op > 10000 then op-10000
				ELSE op
			END as op
		from pdp_maquina_turno_dia 
		 union 
		 select dia,maquina,turno,minutos,
			CASE 
				WHEN op > 10000 then op-10000
				ELSE op
			END as op
		from pdp_maquina_turno_diabr

		 ) as pdp_ct on pdp_ct.maquina = m.Maquina
		 where 
		pdp_ct.dia =  '$fecha' and
		pdp_ct.op = $op
		
		
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
		//TODO: restriccion aqui
	public function GetParte($fecha,$op){
		
		//--restringido
		// select DISTINCT pieza from pdp_cta_dia where dia = '$fecha'
			// MASSS
		  // select * from pdp_cta_dia as cta
				// left JOIN pdp_maquina_turno_dia as turno on cta.dia = turno.dia and cta.maquina = turno.maquina 
			// where 
				// cta.dia =  '$fecha' and
				// turno.op =   $op
		
		
		 $cmd = \Yii::$app->db_mysql;
		  $sql = "
					select * from pdp_cta_dia as cta
				left JOIN  
				(
					select dia,maquina,turno,minutos,
						CASE 
							WHEN op > 10000 then op-10000
							ELSE op
						END as op
					from pdp_maquina_turno_dia 
				
				)	as turno on cta.dia = turno.dia and cta.maquina = turno.maquina 
			where 
				cta.dia =  '$fecha' and
				turno.op =   $op

			UNION

			select * from pdp_ctb_dia as cta
				left JOIN  
				(
					select dia,maquina,turno,minutos,
						CASE 
							WHEN op > 10000 then op-10000
							ELSE op
						END as op
					from pdp_maquina_turno_diabr 
				
				)	as turno on cta.dia = turno.dia and cta.maquina = turno.maquina 
			where 
				cta.dia =  '$fecha' and
				turno.op =   $op
			
				
		
		 ";
		  $result =$cmd->createCommand($sql)
							->queryAll();
		 
		return $result;
		
	}
	
	
	
   
        
    
}