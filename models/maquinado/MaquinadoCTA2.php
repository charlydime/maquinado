<?php

namespace frontend\Models\Maquinado;

use Yii;
use yii\base\Model;
use frontend\models\ete\celda;

Class MaquinadoCTA2 extends Model {
	
	public function getWhere($maq,$prod){
		$where= "";
		
		if(isset($maq) and $maq != '' ) 
			$where .= " and sem.maquina like '$maq%' ";
		if(isset($prod) and $prod != '' ) 
			$where .= " and prod.producto like '$prod%' ";
		
		return $where;
	}

    public function GetInfo($semana,$page,$row,$maq_search,$prod_search) {
          $tmp = explode('-',$semana);
		  $tmp_s = substr($tmp[1],1);
		  $aio = date("Y");
		$se1 =  $tmp_s +0;
		$se2 =  $tmp_s +1;
		$se3 =  $tmp_s +2;
		$se4 =  $tmp_s +3;
		$year = date ("Y");
		$where = $this->getWhere($maq_search,$prod_search);
		 // echo "where $where";exit;
		$page = $page * $row - $row;
        $command = \Yii::$app->db_mysql;
        $result =$command->createCommand("
 				
 
  select 		
				
  
				prod.producto,
				prod_dux.CAMPOUSUARIO5 as casting,
				prod_dux.DESCRIPCION as descripcion,
				
 				
				pdp_maquina_pieza.op as opx,
				
				sem.[$se1] as s1,
				sem.[$se2] as s2,
				sem.[$se3] as s3,
				sem.[$se4] as s4,
				sem.maquina as maquina1,
				
				ETE_S1.hechas as hechas1,
				ETE_S1.rechazadas as rechazadas1,
				
				ETE_S2.hechas as hechas2,
				ETE_S2.rechazadas as rechazadas2,
				
				ETE_S3.hechas as hechas3,
				ETE_S3.rechazadas as rechazadas3,
				
				ETE_S3.hechas as hechas4,
				ETE_S3.rechazadas as rechazadas4,
				
				isnull(almpla.existencia,0) as PLA1,
				isnull(almpla2.existencia,0) as PLA2,
				isnull(almpla.existencia,0)+isnull(almpla2.existencia,0) as PLA,
				isnull(almpma.existencia,0)+isnull(almpma2.existencia,0) as PMA,
				isnull(almcta.existencia,0)+isnull(almcta2.existencia,0) as CTA,
				isnull(almcta.existencia,0) as CTA1,
				isnull(almcta2.existencia,0) as CTA2,
				
				almpta.existencia as PTA,
							
				datepart(week ,dux1.fechaemb ) as sem1entrega,
				datepart(week ,dux2.fechaemb ) as sem12entrega,
				dux1.cantidad as sem1,
				dux2.cantidad as sem2,
				dux3.cantidad as sem3,
				dux4.cantidad as sem4,
				
				0  as prioridad,

				maq_piezas.Hold
				
				
				from 
				(				select  distinct DuxSinc.dbo.ALMPROD.PRODUCTO 
								from DuxSinc.dbo.ALMPROD 
								 LEFT JOIN	producto as p on p.IDENTIFICACION = DuxSinc.dbo.ALMPROD.PRODUCTO 
								where DuxSinc.dbo.ALMPROD.ALMACEN in ('CTA','CTA2','PLA','PLA2','PMA','PMA2') and DuxSinc.dbo.ALMPROD.EXISTENCIA <> 0 and p.PRESENTACION =  'ACE'
								
								Union 
								
								select DISTINCT pdp_cta.Pieza 
								from pdp_cta
								where pdp_cta.Semana between $se1  and $se2   
								and hecho = 0
				) as prod
				
				
				LEFT JOIN maq_piezas on producto = maq_piezas.IDENTIFICACION
				LEFT JOIN  producto as prod_dux on ltrim(RTRIM ( prod_dux.IDENTIFICACION ))= ltrim(RTRIM (prod.producto))
				 Left JOIN (
						SELECT pieza,op
						FROM 	pdp_maquina_pieza
						where op <> 0
						GROUP BY pieza ,OP
						
				) AS	pdp_maquina_pieza  on pdp_maquina_pieza.Pieza = prod.PRODUCTO 
				
			
				
				
				LEFT JOIN(
						SELECT 
						 DuxSinc.dbo.ALMPROD.producto,min(PAROEN.doctoadicionalfecha) as fechaemb, max(CANTIDAD) as cantidad
						FROM DuxSinc.dbo.ALMPROD
						LEFT JOIN PAROEN on DuxSinc.dbo.ALMPROD.producto = PAROEN.PRODUCTO
						WHERE
						datepart( week,PAROEN.doctoadicionalfecha)  =  $se1 and  datepart( year,PAROEN.doctoadicionalfecha) = $year
						-- and DuxSinc.dbo.ALMPROD.ALMACEN = 'CTA'
						GROUP BY DuxSinc.dbo.ALMPROD.producto
						
				) as dux1 on prod.PRODUCTO = dux1.producto 

				LEFT JOIN(
						SELECT 
						 DuxSinc.dbo.ALMPROD.producto,min(PAROEN.doctoadicionalfecha) as fechaemb, max(CANTIDAD) as cantidad
						FROM DuxSinc.dbo.ALMPROD
						LEFT JOIN PAROEN on DuxSinc.dbo.ALMPROD.producto = PAROEN.PRODUCTO
						WHERE
						datepart( week,PAROEN.doctoadicionalfecha) = $se2 and  datepart( year,PAROEN.doctoadicionalfecha) = $year
						-- and DuxSinc.dbo.ALMPROD.ALMACEN = 'CTA'
						GROUP BY DuxSinc.dbo.ALMPROD.producto
						
				) as dux2 on prod.PRODUCTO = dux2.producto 
				
				LEFT JOIN(
						SELECT 
						 DuxSinc.dbo.ALMPROD.producto,min(PAROEN.doctoadicionalfecha) as fechaemb, max(CANTIDAD) as cantidad
						FROM DuxSinc.dbo.ALMPROD
						LEFT JOIN PAROEN on DuxSinc.dbo.ALMPROD.producto = PAROEN.PRODUCTO
						WHERE
						datepart( week,PAROEN.doctoadicionalfecha) = $se3 and  datepart( year,PAROEN.doctoadicionalfecha) = $year
						-- and DuxSinc.dbo.ALMPROD.ALMACEN = 'CTA'
						GROUP BY DuxSinc.dbo.ALMPROD.producto
						
				) as dux3 on prod.PRODUCTO = dux3.producto 
				
				LEFT JOIN(
						SELECT 
						 DuxSinc.dbo.ALMPROD.producto,min(PAROEN.doctoadicionalfecha) as fechaemb, max(CANTIDAD) as cantidad
						FROM DuxSinc.dbo.ALMPROD
						LEFT JOIN PAROEN on DuxSinc.dbo.ALMPROD.producto = PAROEN.PRODUCTO
						WHERE
						datepart( week,PAROEN.doctoadicionalfecha) = $se4 and  datepart( year,PAROEN.doctoadicionalfecha) = $year
						-- and DuxSinc.dbo.ALMPROD.ALMACEN = 'CTA'
						GROUP BY DuxSinc.dbo.ALMPROD.producto
						
				) as dux4 on prod.PRODUCTO = dux4.producto 

				

				LEFT JOIN(
					SELECT   
						sum(DuxSinc.dbo.ALMPROD.EXISTENCIA) AS EXISTENCIA, DuxSinc.dbo.ALMPROD.producto
					FROM DuxSinc.dbo.ALMPROD
					WHERE 
					DuxSinc.dbo.ALMPROD.ALMACEN =   'CTA'
					GROUP BY DuxSinc.dbo.ALMPROD.producto
				) as almcta on prod.PRODUCTO = almcta.producto
				
				LEFT JOIN(
					SELECT   
						sum(DuxSinc.dbo.ALMPROD.EXISTENCIA) AS EXISTENCIA, DuxSinc.dbo.ALMPROD.producto
					FROM DuxSinc.dbo.ALMPROD
					WHERE 
					DuxSinc.dbo.ALMPROD.ALMACEN =   'CTA2'
					GROUP BY DuxSinc.dbo.ALMPROD.producto
				) as almcta2 on prod.PRODUCTO = almcta2.producto

				LEFT JOIN(
					SELECT   
						sum(DuxSinc.dbo.ALMPROD.EXISTENCIA) AS EXISTENCIA , DuxSinc.dbo.ALMPROD.producto
					FROM DuxSinc.dbo.ALMPROD
					WHERE 
					DuxSinc.dbo.ALMPROD.ALMACEN =   'PTA'
					GROUP BY DuxSinc.dbo.ALMPROD.producto
				) as almpta on prod.PRODUCTO = almpta.producto

				LEFT JOIN(
					SELECT   
						sum(DuxSinc.dbo.ALMPROD.EXISTENCIA) AS EXISTENCIA , DuxSinc.dbo.ALMPROD.producto
					FROM DuxSinc.dbo.ALMPROD
					WHERE 
					DuxSinc.dbo.ALMPROD.ALMACEN =   'PLA'
					GROUP BY DuxSinc.dbo.ALMPROD.producto
				) as almpla on prod.PRODUCTO = almpla.producto

				LEFT JOIN(
					SELECT   
						sum(DuxSinc.dbo.ALMPROD.EXISTENCIA) AS EXISTENCIA, DuxSinc.dbo.ALMPROD.producto
					FROM DuxSinc.dbo.ALMPROD
					WHERE 
					DuxSinc.dbo.ALMPROD.ALMACEN =   'PLA2'
					GROUP BY DuxSinc.dbo.ALMPROD.producto
				) as almpla2 on prod.PRODUCTO = almpla2.producto
	
				LEFT JOIN(
					SELECT   
						sum(DuxSinc.dbo.ALMPROD.EXISTENCIA) AS EXISTENCIA, DuxSinc.dbo.ALMPROD.producto
					FROM DuxSinc.dbo.ALMPROD
					WHERE 
					DuxSinc.dbo.ALMPROD.ALMACEN =   'PMA'
					GROUP BY DuxSinc.dbo.ALMPROD.producto
				) as almpma on prod.PRODUCTO = almpma.producto

				LEFT JOIN(
					SELECT   
						sum(DuxSinc.dbo.ALMPROD.EXISTENCIA) AS EXISTENCIA, DuxSinc.dbo.ALMPROD.producto
					FROM DuxSinc.dbo.ALMPROD
					WHERE 
					DuxSinc.dbo.ALMPROD.ALMACEN =   'PMA2'
					GROUP BY DuxSinc.dbo.ALMPROD.producto
				) as almpma2 on prod.PRODUCTO = almpma2.producto

				
				


				LEFT JOIN (
						SELECT * from (
						select 
						Pieza, op, Maquina ,
						Semana,
						Cantidad 
						from pdp_cta 
						) as p
						PIVOT
						(
						 sum(cantidad)
							FOR semana in ([$se1],[$se2],[$se3],[$se4])
						) as piv
						where  [$se1] is not null or [$se2] is not null or [$se3] is not null or [$se4] is not null
				) as sem  on sem.pieza = prod.PRODUCTO  and  sem.op = pdp_maquina_pieza.op 		
					  
				LEFT JOIN(
				
					select 

					Producto,
					[Num Operacion]   as OP, 
					sum ([Piezas Maquinadas] )as hechas, 
					sum ( isnull( [Rechazo Fund] , 0) +  isnull( [Rechazo Maq] , 0 ) ) as rechazadas ,
					maquina as clave,
					DATEPART(WEEK, fecha) as semana ,
					DATEPART(year,fecha) as aio
					 from  ete2.dbo.[Detalle de ETE] as DE 
					left join ete2.dbo.ETE as e  on de.Consecutivo = e.Consecutivo
					LEFT JOIN pdp_maquina as m on e.idmaquina = m.id 
					

				
					GROUP BY
					Producto,
					DATEPART(year,fecha),
					DATEPART(WEEK, fecha),
					[Num Operacion],
					maquina
				
				
				) AS ETE_S1 on 
					ETE_S1.producto = prod.PRODUCTO and 
					ETE_S1.semana = 	$se1 and
					ETE_S1.aio  =   $aio and
					ETE_S1.OP =	sem.op
					and ETE_S1.clave = sem.maquina

				LEFT JOIN(
				
					select 

					Producto,
					[Num Operacion]   as OP, 
					sum ([Piezas Maquinadas] )as hechas, 
					sum ( isnull( [Rechazo Fund] , 0) +  isnull( [Rechazo Maq] , 0 ) ) as rechazadas ,
					maquina as clave,
					DATEPART(WEEK, fecha) as semana ,
					DATEPART(year,fecha) as aio
					 from  ete2.dbo.[Detalle de ETE] as DE 
					left join ete2.dbo.ETE as e  on de.Consecutivo = e.Consecutivo
					LEFT JOIN pdp_maquina as m on e.idmaquina = m.id 
					

				
					GROUP BY
					Producto,
					DATEPART(year,fecha),
					DATEPART(WEEK, fecha),
					[Num Operacion],
					maquina
				
				
				) AS ETE_S2 on 
					ETE_S2.producto = prod.PRODUCTO and 
					ETE_S2.semana = 	$se2 and
					ETE_S2.aio  =   $aio and
					ETE_S2.OP =	sem.op
					and ETE_S2.clave = sem.maquina
				
				LEFT JOIN(
				
					select 

					Producto,
					[Num Operacion]   as OP, 
					sum ([Piezas Maquinadas] )as hechas, 
					sum ( isnull( [Rechazo Fund] , 0) +  isnull( [Rechazo Maq] , 0 ) ) as rechazadas ,
					maquina as clave,
					DATEPART(WEEK, fecha) as semana ,
					DATEPART(year,fecha) as aio
					 from  ete2.dbo.[Detalle de ETE] as DE 
					left join ete2.dbo.ETE as e  on de.Consecutivo = e.Consecutivo
					LEFT JOIN pdp_maquina as m on e.idmaquina = m.id 
					

				
					GROUP BY
					Producto,
					DATEPART(year,fecha),
					DATEPART(WEEK, fecha),
					[Num Operacion],
					maquina
				
				
				) AS ETE_S3 on 
					ETE_S3.producto = prod.PRODUCTO and 
					ETE_S3.semana = 	$se3 and
					ETE_S3.aio  =   $aio and
					ETE_S3.OP =	sem.op
					and ETE_S3.clave = sem.maquina
				
				LEFT JOIN(
				
					 

					select 

					Producto,
					[Num Operacion]   as OP, 
					sum ([Piezas Maquinadas] )as hechas, 
					sum ( isnull( [Rechazo Fund] , 0) +  isnull( [Rechazo Maq] , 0 ) ) as rechazadas ,
					maquina as clave,
					DATEPART(WEEK, fecha) as semana ,
					DATEPART(year,fecha) as aio
					 from  ete2.dbo.[Detalle de ETE] as DE 
					left join ete2.dbo.ETE as e  on de.Consecutivo = e.Consecutivo
					LEFT JOIN pdp_maquina as m on e.idmaquina = m.id 
					

				
					GROUP BY
					Producto,
					DATEPART(year,fecha),
					DATEPART(WEEK, fecha),
					[Num Operacion],
					maquina
				
				
				) AS ETE_S4 on 
					ETE_S4.producto = prod.PRODUCTO and 
					ETE_S4.semana = 	$se4 and
					ETE_S4.aio  =   $aio and
					ETE_S4.OP =	sem.op 
					and ETE_S4.clave = sem.maquina
					
				where prod.PRODUCTO  in (select pieza from pdp_maquinado_bl)
				$where
				
				ORDER BY 

				Hold,
				prioridad desc,
				producto,						
				
				pdp_maquina_pieza.op asc,
			
				
				dux1.fechaemb,
 				dux2.fechaemb
				     
				offset $page rows fetch next $row rows only 
 
				
		")->queryAll();
        
        if(count($result)!=0){
			 $min = 0;
			
			 $ts1=0;
			 $ts2=0;
			 $ts3=0;
			 $ts4=0;
			 $cta=0;
			 $cta1=0;
			 $cta2=0;
			 $rows = 0;
			 $ok = 0;
			 $cast = '';
				foreach($result as &$r){
					//echo " producto : ".$r['producto']." op : ".$r['opx'];
					
					if ($ok == 0 || $cast <> $r['casting']){
						 // $alm =  $this->getInvCasting( $r['casting'] );
							$ok = 1;

							
					}		
					
					$cast = $r['casting'];  
					
					if ($r['maquina1'] == null)	
						$r['maquina1']= $this->maquina($r['producto'],$r['opx']);
						
						$r['maq_ocup']= $this->maquina_ocupada($r['maquina1']);
					
					$min =  $this->p1tiempos($r['producto'], $r['maquina1'],$r['opx']);
						//echo '('.$min.')     p'.$r['producto'].'   m'.$r['maquina1'].'  s'.$r["s1"].' ' .$r["s2"].' '.$r["s3"].' '.$r["s4"];			  
					
					$r['Minutos'] =  $this->p1tiempos($r['producto'], $r['maquina1'],$r['opx']);
					$r['Minutos'] = number_format ($r['Minutos']);
					$r['op'] =  $this->p1ops($r['producto'], $r['maquina1']);
					
					$r['s1_min'] = $min * $r["s1"];
					$r['s2_min'] = $min * $r["s2"];
					$r['s3_min'] = $min * $r["s3"];
					$r['s4_min'] = $min * $r["s4"];
					
								
					$r['tot_pza'] =  $r["s1"]+$r["s2"]+$r["s3"]+$r["s4"];
					$r['tot_min'] =  $r["s1_min"]+$r["s2_min"]+$r["s3_min"]+$r["s4_min"] ;
					
					
				if ($r['opx'] == 10 ||  $r['maquina1'] == null){
				}else {
				 $r['PLA'] = '';	
				 $r['PLA1'] = '';	
				 $r['PLA2'] = '';	
				 $r['CTA'] = '';	
				 $r['CTA1'] = '';	
				 $r['CTA2'] = '';	
				 $r['PMA'] = '';	
				 $r['PTA'] = '';	

				}
					
					if ($min != 0 || $min != null || $min !=  '' || $r['maquina1'] != 0 || $r['maquina1'] != null || $r['maquina1'] !=  '' ){
						$ts1 +=  $r["s1"] * $min;
						$ts2 +=  $r["s2"] * $min;
						$ts3 +=  $r["s3"] * $min;
						$ts4 +=  $r["s4"] * $min;
						$cta +=  $r["CTA"] * $min;
						$cta1 +=  $r["CTA1"] * $min;
						$cta2 +=  $r["CTA2"] * $min;
					}
					
				$r['CTA'] = (real)$r['CTA'] ;
				$r['CTA1'] = (real)$r['CTA1'] ;
				$r['CTA2'] = (real)$r['CTA2'] ;
				$r['PLA'] = (real)$r['PLA'] ;
				$r['PLA1'] = (real)$r['PLA1'] ;
				$r['PLA2'] = (real)$r['PLA2'] ;
				$r['PMA'] = (real)$r['PMA'] ;
				$r['PTA'] = (real)$r['PTA'] ;
				$r['sem1'] = (real)$r['sem1'] ;
				$r['sem2'] = (real)$r['sem2'] ;
				$r['sem3'] = (real)$r['sem3'] ;
				$r['sem4'] = (real)$r['sem4'] ;
				if ($r['s4_min'] ==  0) $r['s4_min'] = ''; 
				if ($r['s3_min'] ==  0) $r['s3_min'] = ''; 
				if ($r['s2_min'] ==  0) $r['s2_min'] = ''; 
				if ($r['s1_min'] ==  0) $r['s1_min'] = ''; 
				if ($r['Minutos'] ==  0) $r['Minutos'] = ''; 
				if ($r['sem1'] ==  0) $r['sem1'] = ''; 
				if ($r['sem2'] ==  0) $r['sem2'] = ''; 
				if ($r['sem3'] ==  0) $r['sem3'] = ''; 
				if ($r['sem4'] ==  0) $r['sem4'] = ''; 
				if ($r['tot_pza'] ==  0) $r['tot_pza'] = ''; 
				if ($r['tot_min'] ==  0) $r['tot_min'] = ''; 
				if ($r['CTA'] ==  0) $r['CTA'] = ''; 
				if ($r['CTA1'] ==  0) $r['CTA1'] = ''; 
				if ($r['CTA2'] ==  0) $r['CTA2'] = ''; 
				if ($r['PLA'] ==  0) $r['PLA'] = ''; 
				if ($r['PLA1'] ==  0) $r['PLA1'] = ''; 
				if ($r['PLA2'] ==  0) $r['PLA2'] = ''; 
				if ($r['PMA'] ==  0) $r['PMA'] = ''; 
				if ($r['PTA'] ==  0) $r['PTA'] = ''; 
				if ($r['sem1'] ==  0) $r['sem1'] = ''; 
				if ($r['sem2'] ==  0) $r['sem2'] = ''; 
				if ($r['sem3'] ==  0) $r['sem3'] = ''; 
				if ($r['sem4'] ==  0) $r['sem4'] = ''; 
				 $rows++; 
				}
				$resumen = $this->GetInfo_resumen($semana);
				$resumencta = $this->GetInfo_resumenCTA($semana);
				$ts1=$resumen['s1'];
				$ts2=$resumen['s2'];
				$ts3=$resumen['s3'];
				$ts4=$resumen['s4'];
				$cta1=$resumencta['cta_min'];
				$cta2=$resumencta['cta2_min'];
				
				
				$totales[0]['s1_min'] = $ts1 == 0 ? '' : number_format($ts1) ;
				$totales[0]['s2_min'] = $ts2 == 0 ? '' : number_format($ts2) ;
				$totales[0]['s3_min'] = $ts3 == 0 ? '' : number_format($ts3) ;
				$totales[0]['s4_min'] = $ts4 == 0 ? '' : number_format($ts4) ;
				$totales[0]['CTA'] = $cta == 0 ? '' : number_format($cta);
				$totales[0]['CTA1'] = $cta1 == 0 ? '' : number_format($cta1);
				$totales[0]['CTA2'] = $cta2 == 0 ? '' : number_format($cta2);
			
				$totales[0]['maquina1'] = 'Minutos';
				
				$totales[1]['s1_min'] = $ts1 == 0 ? '' : number_format($ts1/60) ;
				$totales[1]['s2_min'] = $ts2 == 0 ? '' : number_format($ts2/60) ;
				$totales[1]['s3_min'] = $ts3 == 0 ? '' : number_format($ts3/60) ;
				$totales[1]['s4_min'] = $ts4 == 0 ? '' : number_format($ts4/60) ;
				$totales[1]['CTA'] = $cta == 0 ? '' : number_format($cta/60) ;
				$totales[1]['CTA1'] = $cta1 == 0 ? '' : number_format($cta1/60) ;
				$totales[1]['CTA2'] = $cta2 == 0 ? '' : number_format($cta2/60) ;
		
				$totales[1]['maquina1'] = 'Horas';
				
				// $totales[2]['s1_min'] = $ts1 == 0 ? '' : number_format(($ts1/60)/8,1) ;
				// $totales[2]['s2_min'] = $ts2 == 0 ? '' : number_format(($ts2/60)/8,1) ;
				// $totales[2]['s3_min'] = $ts3 == 0 ? '' : number_format(($ts3/60)/8,1) ;
				// $totales[2]['s4_min'] = $ts4 == 0 ? '' : number_format(($ts4/60)/8,1) ;
				// $totales[2]['CTA'] = $cta == 0 ? $cta : number_format(($cta/60)/8,1) ;
	
				// $totales[2]['maquina1'] = 'Turno 8H';
				
				$totales[2]['s1_min'] = $ts1 == 0 ? '' : number_format(($ts1/60)/9,1) ;
				$totales[2]['s2_min'] = $ts2 == 0 ? '' : number_format(($ts2/60)/9,1) ;
				$totales[2]['s3_min'] = $ts3 == 0 ? '' : number_format(($ts3/60)/9,1) ;
				$totales[2]['s4_min'] = $ts4 == 0 ? '' : number_format(($ts4/60)/9,1) ;
				$totales[2]['CTA'] = $cta == 0 ? '' : number_format(($cta/60)/9,1) ;
				$totales[2]['CTA1'] = $cta1 == 0 ? '' : number_format(($cta1/60)/9,1) ;
				$totales[2]['CTA2'] = $cta2 == 0 ? '' : number_format(($cta2/60)/9,1) ;
			
				$totales[2]['maquina1'] = 'Turno 9H';
	
			
		$datos['rows'] = $result;
		$datos['footer'] = $totales;
		// $datos['total'] = $rows;
		$datos['total'] =  $this->GetInfo_total($semana);
		
          return $datos;   
        }   
        
        return null;
    }
	
	public function GetInfo_resumenCTA($semana) {
          $tmp = explode('-',$semana);
		  $tmp_s = substr($tmp[1],1);
		  $aio = date("Y");
		$se1 =  $tmp_s +0;
		$se2 =  $tmp_s +1;
		$se3 =  $tmp_s +2;
		$se4 =  $tmp_s +3;
		$year = date ("Y");
		
        $command = \Yii::$app->db_mysql;
        $result =$command->createCommand("

			 select
				sum ( (isnull(almcta.existencia,0)+isnull(almcta2.existencia,0) ) * dbo.f_getminAC(prod.PRODUCTO) ) as cta_min,
				sum ( isnull(almcta.existencia,0)  * dbo.f_getminAC(prod.PRODUCTO)  )as cta1_min,
				sum ( isnull(almcta2.existencia,0) * dbo.f_getminAC(prod.PRODUCTO) ) as cta2_min
				
							from 
				(				select  distinct DuxSinc.dbo.ALMPROD.PRODUCTO 
								from DuxSinc.dbo.ALMPROD 
								 LEFT JOIN	producto as p on p.IDENTIFICACION = DuxSinc.dbo.ALMPROD.PRODUCTO 
								where DuxSinc.dbo.ALMPROD.ALMACEN in ('CTA','CTA2','PLA','PLA2','PMA','PMA2') and DuxSinc.dbo.ALMPROD.EXISTENCIA <> 0 and p.PRESENTACION =  'ACE'
								
								Union 
								
								select DISTINCT pdp_cta.Pieza 
								from pdp_cta
								where pdp_cta.Semana between 30  and 33   
								and hecho = 0
				) as prod
				
				
				
				
				LEFT JOIN maq_piezas on producto = maq_piezas.IDENTIFICACION
				LEFT JOIN  producto as prod_dux on prod_dux.IDENTIFICACION = maq_piezas.IDENTIFICACION
				 Left JOIN (
						SELECT pieza,op
						FROM 	pdp_maquina_piezabr
						where op <> 0
						GROUP BY pieza ,OP
						
				) AS	pdp_maquina_pieza  on pdp_maquina_pieza.Pieza = prod.PRODUCTO 
				
				LEFT JOIN (
						SELECT * from (
						select 
						Pieza, op, Maquina ,
						Semana,
						Cantidad 
						from pdp_cta
						) as p
						PIVOT
						(
						 sum(cantidad)
							FOR semana in ([$se1],[$se2],[$se3],[$se4])
						) as piv
						where [$se1] is not null or [$se2] is not null or [$se3] is not null or [$se4] is not null
				) as sem  on sem.pieza = prod.PRODUCTO  and  sem.op = pdp_maquina_pieza.op 		
				
				
				LEFT JOIN(
					SELECT   
						sum(DuxSinc.dbo.ALMPROD.EXISTENCIA) AS EXISTENCIA, DuxSinc.dbo.ALMPROD.producto
					FROM DuxSinc.dbo.ALMPROD
					WHERE 
					DuxSinc.dbo.ALMPROD.ALMACEN =   'CTA'
					GROUP BY DuxSinc.dbo.ALMPROD.producto
				) as almcta on prod.PRODUCTO = almcta.producto
				
				LEFT JOIN(
					SELECT   
						sum(DuxSinc.dbo.ALMPROD.EXISTENCIA) AS EXISTENCIA, DuxSinc.dbo.ALMPROD.producto
					FROM DuxSinc.dbo.ALMPROD
					WHERE 
					DuxSinc.dbo.ALMPROD.ALMACEN =   'CTA2'
					GROUP BY DuxSinc.dbo.ALMPROD.producto
				) as almcta2 on prod.PRODUCTO = almcta2.producto
				
				where  
				  prod.PRODUCTO   in (select pieza from pdp_maquinado_bl)
				-- and prod.PRODUCTO = prod_dux.CAMPOUSUARIO5
				
				
 
				
		")->queryAll();
		
		return $result[0];
		
		}
	
	  public function GetInfo_total($semana) {
          $tmp = explode('-',$semana);
		  $tmp_s = substr($tmp[1],1);
		  $aio = date("Y");
		$se1 =  $tmp_s +0;
		$se2 =  $tmp_s +1;
		$se3 =  $tmp_s +2;
		$se4 =  $tmp_s +3;
		$year = date ("Y");
		
		
        $command = \Yii::$app->db_mysql;
        $result =$command->createCommand("
 				
 
  select 		
				
  
				count(prod.producto) as cantidad
				
				
				from 
				(				select  distinct DuxSinc.dbo.ALMPROD.PRODUCTO 
								from DuxSinc.dbo.ALMPROD 
								 LEFT JOIN	producto as p on p.IDENTIFICACION = DuxSinc.dbo.ALMPROD.PRODUCTO 
								where DuxSinc.dbo.ALMPROD.ALMACEN in ('CTA','CTA2','PLA','PLA2','PMA','PMA2') and DuxSinc.dbo.ALMPROD.EXISTENCIA <> 0 and p.PRESENTACION =  'ACE'
								
								Union 
								
								select DISTINCT pdp_cta.Pieza 
								from pdp_cta
								where pdp_cta.Semana between $se1  and $se2   
								and hecho = 0
				) as prod
				
				
				LEFT JOIN maq_piezas on producto = maq_piezas.IDENTIFICACION
				LEFT JOIN  producto as prod_dux on ltrim(RTRIM ( prod_dux.IDENTIFICACION ))= ltrim(RTRIM (prod.producto))
				 Left JOIN (
						SELECT pieza,op
						FROM 	pdp_maquina_pieza
						where op <> 0
						GROUP BY pieza ,OP
						
				) AS	pdp_maquina_pieza  on pdp_maquina_pieza.Pieza = prod.PRODUCTO 
				
			
				
				
				LEFT JOIN(
						SELECT 
						 DuxSinc.dbo.ALMPROD.producto,min(PAROEN.doctoadicionalfecha) as fechaemb, max(CANTIDAD) as cantidad
						FROM DuxSinc.dbo.ALMPROD
						LEFT JOIN PAROEN on DuxSinc.dbo.ALMPROD.producto = PAROEN.PRODUCTO
						WHERE
						datepart( week,PAROEN.doctoadicionalfecha)  =  $se1 and  datepart( year,PAROEN.doctoadicionalfecha) = $year
						-- and DuxSinc.dbo.ALMPROD.ALMACEN = 'CTA'
						GROUP BY DuxSinc.dbo.ALMPROD.producto
						
				) as dux1 on prod.PRODUCTO = dux1.producto 

				LEFT JOIN(
						SELECT 
						 DuxSinc.dbo.ALMPROD.producto,min(PAROEN.doctoadicionalfecha) as fechaemb, max(CANTIDAD) as cantidad
						FROM DuxSinc.dbo.ALMPROD
						LEFT JOIN PAROEN on DuxSinc.dbo.ALMPROD.producto = PAROEN.PRODUCTO
						WHERE
						datepart( week,PAROEN.doctoadicionalfecha) = $se2 and  datepart( year,PAROEN.doctoadicionalfecha) = $year
						-- and DuxSinc.dbo.ALMPROD.ALMACEN = 'CTA'
						GROUP BY DuxSinc.dbo.ALMPROD.producto
						
				) as dux2 on prod.PRODUCTO = dux2.producto 
				
				LEFT JOIN(
						SELECT 
						 DuxSinc.dbo.ALMPROD.producto,min(PAROEN.doctoadicionalfecha) as fechaemb, max(CANTIDAD) as cantidad
						FROM DuxSinc.dbo.ALMPROD
						LEFT JOIN PAROEN on DuxSinc.dbo.ALMPROD.producto = PAROEN.PRODUCTO
						WHERE
						datepart( week,PAROEN.doctoadicionalfecha) = $se3 and  datepart( year,PAROEN.doctoadicionalfecha) = $year
						-- and DuxSinc.dbo.ALMPROD.ALMACEN = 'CTA'
						GROUP BY DuxSinc.dbo.ALMPROD.producto
						
				) as dux3 on prod.PRODUCTO = dux3.producto 
				
				LEFT JOIN(
						SELECT 
						 DuxSinc.dbo.ALMPROD.producto,min(PAROEN.doctoadicionalfecha) as fechaemb, max(CANTIDAD) as cantidad
						FROM DuxSinc.dbo.ALMPROD
						LEFT JOIN PAROEN on DuxSinc.dbo.ALMPROD.producto = PAROEN.PRODUCTO
						WHERE
						datepart( week,PAROEN.doctoadicionalfecha) = $se4 and  datepart( year,PAROEN.doctoadicionalfecha) = $year
						-- and DuxSinc.dbo.ALMPROD.ALMACEN = 'CTA'
						GROUP BY DuxSinc.dbo.ALMPROD.producto
						
				) as dux4 on prod.PRODUCTO = dux4.producto 

				

				LEFT JOIN(
					SELECT   
						sum(DuxSinc.dbo.ALMPROD.EXISTENCIA) AS EXISTENCIA, DuxSinc.dbo.ALMPROD.producto
					FROM DuxSinc.dbo.ALMPROD
					WHERE 
					DuxSinc.dbo.ALMPROD.ALMACEN =   'CTA'
					GROUP BY DuxSinc.dbo.ALMPROD.producto
				) as almcta on prod.PRODUCTO = almcta.producto
				
				LEFT JOIN(
					SELECT   
						sum(DuxSinc.dbo.ALMPROD.EXISTENCIA) AS EXISTENCIA, DuxSinc.dbo.ALMPROD.producto
					FROM DuxSinc.dbo.ALMPROD
					WHERE 
					DuxSinc.dbo.ALMPROD.ALMACEN =   'CTA2'
					GROUP BY DuxSinc.dbo.ALMPROD.producto
				) as almcta2 on prod.PRODUCTO = almcta2.producto

				LEFT JOIN(
					SELECT   
						sum(DuxSinc.dbo.ALMPROD.EXISTENCIA) AS EXISTENCIA , DuxSinc.dbo.ALMPROD.producto
					FROM DuxSinc.dbo.ALMPROD
					WHERE 
					DuxSinc.dbo.ALMPROD.ALMACEN =   'PTA'
					GROUP BY DuxSinc.dbo.ALMPROD.producto
				) as almpta on prod.PRODUCTO = almpta.producto

				LEFT JOIN(
					SELECT   
						sum(DuxSinc.dbo.ALMPROD.EXISTENCIA) AS EXISTENCIA , DuxSinc.dbo.ALMPROD.producto
					FROM DuxSinc.dbo.ALMPROD
					WHERE 
					DuxSinc.dbo.ALMPROD.ALMACEN =   'PLA'
					GROUP BY DuxSinc.dbo.ALMPROD.producto
				) as almpla on prod.PRODUCTO = almpla.producto

				LEFT JOIN(
					SELECT   
						sum(DuxSinc.dbo.ALMPROD.EXISTENCIA) AS EXISTENCIA, DuxSinc.dbo.ALMPROD.producto
					FROM DuxSinc.dbo.ALMPROD
					WHERE 
					DuxSinc.dbo.ALMPROD.ALMACEN =   'PLA2'
					GROUP BY DuxSinc.dbo.ALMPROD.producto
				) as almpla2 on prod.PRODUCTO = almpla2.producto
	
				LEFT JOIN(
					SELECT   
						sum(DuxSinc.dbo.ALMPROD.EXISTENCIA) AS EXISTENCIA, DuxSinc.dbo.ALMPROD.producto
					FROM DuxSinc.dbo.ALMPROD
					WHERE 
					DuxSinc.dbo.ALMPROD.ALMACEN =   'PMA'
					GROUP BY DuxSinc.dbo.ALMPROD.producto
				) as almpma on prod.PRODUCTO = almpma.producto

				LEFT JOIN(
					SELECT   
						sum(DuxSinc.dbo.ALMPROD.EXISTENCIA) AS EXISTENCIA, DuxSinc.dbo.ALMPROD.producto
					FROM DuxSinc.dbo.ALMPROD
					WHERE 
					DuxSinc.dbo.ALMPROD.ALMACEN =   'PMA2'
					GROUP BY DuxSinc.dbo.ALMPROD.producto
				) as almpma2 on prod.PRODUCTO = almpma2.producto

				
				


				LEFT JOIN (
						SELECT * from (
						select 
						Pieza, op, Maquina ,
						Semana,
						Cantidad 
						from pdp_cta 
						) as p
						PIVOT
						(
						 sum(cantidad)
							FOR semana in ([$se1],[$se2],[$se3],[$se4])
						) as piv
						where  [$se1] is not null or [$se2] is not null or [$se3] is not null or [$se4] is not null
				) as sem  on sem.pieza = prod.PRODUCTO  and  sem.op = pdp_maquina_pieza.op 		
					  
				LEFT JOIN(
				
					select 

					Producto,
					[Num Operacion]   as OP, 
					sum ([Piezas Maquinadas] )as hechas, 
					sum ( isnull( [Rechazo Fund] , 0) +  isnull( [Rechazo Maq] , 0 ) ) as rechazadas ,
					maquina as clave,
					DATEPART(WEEK, fecha) as semana ,
					DATEPART(year,fecha) as aio
					 from  ete2.dbo.[Detalle de ETE] as DE 
					left join ete2.dbo.ETE as e  on de.Consecutivo = e.Consecutivo
					LEFT JOIN pdp_maquina as m on e.idmaquina = m.id 
					

				
					GROUP BY
					Producto,
					DATEPART(year,fecha),
					DATEPART(WEEK, fecha),
					[Num Operacion],
					maquina
				
				
				) AS ETE_S1 on 
					ETE_S1.producto = prod.PRODUCTO and 
					ETE_S1.semana = 	$se1 and
					ETE_S1.aio  =   $aio and
					ETE_S1.OP =	sem.op
					and ETE_S1.clave = sem.maquina

				LEFT JOIN(
				
					select 

					Producto,
					[Num Operacion]   as OP, 
					sum ([Piezas Maquinadas] )as hechas, 
					sum ( isnull( [Rechazo Fund] , 0) +  isnull( [Rechazo Maq] , 0 ) ) as rechazadas ,
					maquina as clave,
					DATEPART(WEEK, fecha) as semana ,
					DATEPART(year,fecha) as aio
					 from  ete2.dbo.[Detalle de ETE] as DE 
					left join ete2.dbo.ETE as e  on de.Consecutivo = e.Consecutivo
					LEFT JOIN pdp_maquina as m on e.idmaquina = m.id 
					

				
					GROUP BY
					Producto,
					DATEPART(year,fecha),
					DATEPART(WEEK, fecha),
					[Num Operacion],
					maquina
				
				
				) AS ETE_S2 on 
					ETE_S2.producto = prod.PRODUCTO and 
					ETE_S2.semana = 	$se2 and
					ETE_S2.aio  =   $aio and
					ETE_S2.OP =	sem.op
					and ETE_S2.clave = sem.maquina
				
				LEFT JOIN(
				
					select 

					Producto,
					[Num Operacion]   as OP, 
					sum ([Piezas Maquinadas] )as hechas, 
					sum ( isnull( [Rechazo Fund] , 0) +  isnull( [Rechazo Maq] , 0 ) ) as rechazadas ,
					maquina as clave,
					DATEPART(WEEK, fecha) as semana ,
					DATEPART(year,fecha) as aio
					 from  ete2.dbo.[Detalle de ETE] as DE 
					left join ete2.dbo.ETE as e  on de.Consecutivo = e.Consecutivo
					LEFT JOIN pdp_maquina as m on e.idmaquina = m.id 
					

				
					GROUP BY
					Producto,
					DATEPART(year,fecha),
					DATEPART(WEEK, fecha),
					[Num Operacion],
					maquina
				
				
				) AS ETE_S3 on 
					ETE_S3.producto = prod.PRODUCTO and 
					ETE_S3.semana = 	$se3 and
					ETE_S3.aio  =   $aio and
					ETE_S3.OP =	sem.op
					and ETE_S3.clave = sem.maquina
				
				LEFT JOIN(
				
					 

					select 

					Producto,
					[Num Operacion]   as OP, 
					sum ([Piezas Maquinadas] )as hechas, 
					sum ( isnull( [Rechazo Fund] , 0) +  isnull( [Rechazo Maq] , 0 ) ) as rechazadas ,
					maquina as clave,
					DATEPART(WEEK, fecha) as semana ,
					DATEPART(year,fecha) as aio
					 from  ete2.dbo.[Detalle de ETE] as DE 
					left join ete2.dbo.ETE as e  on de.Consecutivo = e.Consecutivo
					LEFT JOIN pdp_maquina as m on e.idmaquina = m.id 
					

				
					GROUP BY
					Producto,
					DATEPART(year,fecha),
					DATEPART(WEEK, fecha),
					[Num Operacion],
					maquina
				
				
				) AS ETE_S4 on 
					ETE_S4.producto = prod.PRODUCTO and 
					ETE_S4.semana = 	$se4 and
					ETE_S4.aio  =   $aio and
					ETE_S4.OP =	sem.op 
					and ETE_S4.clave = sem.maquina
					
				where prod.PRODUCTO  in (select pieza from pdp_maquinado_bl)
				
				
				     
				
 
				
		")->queryAll();
		
		return $result[0]['cantidad'];
		
	  }
	
	  public function GetInfo_resumen($semana) {
          $tmp = explode('-',$semana);
		  $tmp_s = substr($tmp[1],1);
		  $aio = date("Y");
		$se1 =  $tmp_s +0;
		$se2 =  $tmp_s +1;
		$se3 =  $tmp_s +2;
		$se4 =  $tmp_s +3;
		$year = date ("Y");
		
		
        $command = \Yii::$app->db_mysql;
        $result =$command->createCommand("
 		
  select 		
				
  			sum(sem.[$se1]*mp.minutos) as s1,
			sum(sem.[$se2]*mp.minutos) as s2,
			sum(sem.[$se3]*mp.minutos) as s3,
			sum(sem.[$se4]*mp.minutos) as s4
			
				
				from 
				(				select  distinct DuxSinc.dbo.ALMPROD.PRODUCTO 
								from DuxSinc.dbo.ALMPROD 
								 LEFT JOIN	producto as p on p.IDENTIFICACION = DuxSinc.dbo.ALMPROD.PRODUCTO 
								where DuxSinc.dbo.ALMPROD.ALMACEN in ('CTA','CTA2','PLA','PLA2','PMA','PMA2') and DuxSinc.dbo.ALMPROD.EXISTENCIA <> 0 and p.PRESENTACION =  'ACE'
								
								Union 
								
								select DISTINCT pdp_cta.Pieza 
								from pdp_cta
								where pdp_cta.Semana between $se1  and $se2   
								and hecho = 0
				) as prod
				
				
				LEFT JOIN maq_piezas on producto = maq_piezas.IDENTIFICACION
				LEFT JOIN  producto as prod_dux on ltrim(RTRIM ( prod_dux.IDENTIFICACION ))= ltrim(RTRIM (prod.producto))
				 Left JOIN (
						SELECT pieza,op
						FROM 	pdp_maquina_pieza
						where op <> 0
						GROUP BY pieza ,OP
						
				) AS	pdp_maquina_pieza  on pdp_maquina_pieza.Pieza = prod.PRODUCTO 
				
			
				
				
				LEFT JOIN(
						SELECT 
						 DuxSinc.dbo.ALMPROD.producto,min(PAROEN.doctoadicionalfecha) as fechaemb, max(CANTIDAD) as cantidad
						FROM DuxSinc.dbo.ALMPROD
						LEFT JOIN PAROEN on DuxSinc.dbo.ALMPROD.producto = PAROEN.PRODUCTO
						WHERE
						datepart( week,PAROEN.doctoadicionalfecha)  =  $se1 and  datepart( year,PAROEN.doctoadicionalfecha) = $year
						-- and DuxSinc.dbo.ALMPROD.ALMACEN = 'CTA'
						GROUP BY DuxSinc.dbo.ALMPROD.producto
						
				) as dux1 on prod.PRODUCTO = dux1.producto 

				LEFT JOIN(
						SELECT 
						 DuxSinc.dbo.ALMPROD.producto,min(PAROEN.doctoadicionalfecha) as fechaemb, max(CANTIDAD) as cantidad
						FROM DuxSinc.dbo.ALMPROD
						LEFT JOIN PAROEN on DuxSinc.dbo.ALMPROD.producto = PAROEN.PRODUCTO
						WHERE
						datepart( week,PAROEN.doctoadicionalfecha) = $se2 and  datepart( year,PAROEN.doctoadicionalfecha) = $year
						-- and DuxSinc.dbo.ALMPROD.ALMACEN = 'CTA'
						GROUP BY DuxSinc.dbo.ALMPROD.producto
						
				) as dux2 on prod.PRODUCTO = dux2.producto 
				
				LEFT JOIN(
						SELECT 
						 DuxSinc.dbo.ALMPROD.producto,min(PAROEN.doctoadicionalfecha) as fechaemb, max(CANTIDAD) as cantidad
						FROM DuxSinc.dbo.ALMPROD
						LEFT JOIN PAROEN on DuxSinc.dbo.ALMPROD.producto = PAROEN.PRODUCTO
						WHERE
						datepart( week,PAROEN.doctoadicionalfecha) = $se3 and  datepart( year,PAROEN.doctoadicionalfecha) = $year
						-- and DuxSinc.dbo.ALMPROD.ALMACEN = 'CTA'
						GROUP BY DuxSinc.dbo.ALMPROD.producto
						
				) as dux3 on prod.PRODUCTO = dux3.producto 
				
				LEFT JOIN(
						SELECT 
						 DuxSinc.dbo.ALMPROD.producto,min(PAROEN.doctoadicionalfecha) as fechaemb, max(CANTIDAD) as cantidad
						FROM DuxSinc.dbo.ALMPROD
						LEFT JOIN PAROEN on DuxSinc.dbo.ALMPROD.producto = PAROEN.PRODUCTO
						WHERE
						datepart( week,PAROEN.doctoadicionalfecha) = $se4 and  datepart( year,PAROEN.doctoadicionalfecha) = $year
						-- and DuxSinc.dbo.ALMPROD.ALMACEN = 'CTA'
						GROUP BY DuxSinc.dbo.ALMPROD.producto
						
				) as dux4 on prod.PRODUCTO = dux4.producto 

				

				LEFT JOIN(
					SELECT   
						sum(DuxSinc.dbo.ALMPROD.EXISTENCIA) AS EXISTENCIA, DuxSinc.dbo.ALMPROD.producto
					FROM DuxSinc.dbo.ALMPROD
					WHERE 
					DuxSinc.dbo.ALMPROD.ALMACEN =   'CTA'
					GROUP BY DuxSinc.dbo.ALMPROD.producto
				) as almcta on prod.PRODUCTO = almcta.producto
				
				LEFT JOIN(
					SELECT   
						sum(DuxSinc.dbo.ALMPROD.EXISTENCIA) AS EXISTENCIA, DuxSinc.dbo.ALMPROD.producto
					FROM DuxSinc.dbo.ALMPROD
					WHERE 
					DuxSinc.dbo.ALMPROD.ALMACEN =   'CTA2'
					GROUP BY DuxSinc.dbo.ALMPROD.producto
				) as almcta2 on prod.PRODUCTO = almcta2.producto

				LEFT JOIN(
					SELECT   
						sum(DuxSinc.dbo.ALMPROD.EXISTENCIA) AS EXISTENCIA , DuxSinc.dbo.ALMPROD.producto
					FROM DuxSinc.dbo.ALMPROD
					WHERE 
					DuxSinc.dbo.ALMPROD.ALMACEN =   'PTA'
					GROUP BY DuxSinc.dbo.ALMPROD.producto
				) as almpta on prod.PRODUCTO = almpta.producto

				LEFT JOIN(
					SELECT   
						sum(DuxSinc.dbo.ALMPROD.EXISTENCIA) AS EXISTENCIA , DuxSinc.dbo.ALMPROD.producto
					FROM DuxSinc.dbo.ALMPROD
					WHERE 
					DuxSinc.dbo.ALMPROD.ALMACEN =   'PLA'
					GROUP BY DuxSinc.dbo.ALMPROD.producto
				) as almpla on prod.PRODUCTO = almpla.producto

				LEFT JOIN(
					SELECT   
						sum(DuxSinc.dbo.ALMPROD.EXISTENCIA) AS EXISTENCIA, DuxSinc.dbo.ALMPROD.producto
					FROM DuxSinc.dbo.ALMPROD
					WHERE 
					DuxSinc.dbo.ALMPROD.ALMACEN =   'PLA2'
					GROUP BY DuxSinc.dbo.ALMPROD.producto
				) as almpla2 on prod.PRODUCTO = almpla2.producto
	
				LEFT JOIN(
					SELECT   
						sum(DuxSinc.dbo.ALMPROD.EXISTENCIA) AS EXISTENCIA, DuxSinc.dbo.ALMPROD.producto
					FROM DuxSinc.dbo.ALMPROD
					WHERE 
					DuxSinc.dbo.ALMPROD.ALMACEN =   'PMA'
					GROUP BY DuxSinc.dbo.ALMPROD.producto
				) as almpma on prod.PRODUCTO = almpma.producto

				LEFT JOIN(
					SELECT   
						sum(DuxSinc.dbo.ALMPROD.EXISTENCIA) AS EXISTENCIA, DuxSinc.dbo.ALMPROD.producto
					FROM DuxSinc.dbo.ALMPROD
					WHERE 
					DuxSinc.dbo.ALMPROD.ALMACEN =   'PMA2'
					GROUP BY DuxSinc.dbo.ALMPROD.producto
				) as almpma2 on prod.PRODUCTO = almpma2.producto

				
				


				LEFT JOIN (
						SELECT * from (
						select 
						Pieza, op, Maquina ,
						Semana,
						Cantidad 
						from pdp_cta 
						) as p
						PIVOT
						(
						 sum(cantidad)
							FOR semana in ([$se1],[$se2],[$se3],[$se4])
						) as piv
						where  [$se1] is not null or [$se2] is not null or [$se3] is not null or [$se4] is not null
				) as sem  on sem.pieza = prod.PRODUCTO  and  sem.op = pdp_maquina_pieza.op 		
				
				LEFT JOIN(
				select maquina,op,minutos,pieza from pdp_maquina_pieza
				) as mp  on mp.pieza = sem.pieza and mp.op = sem.op and mp.maquina=sem.maquina
				
				LEFT JOIN(
				
					select 

					Producto,
					[Num Operacion]   as OP, 
					sum ([Piezas Maquinadas] )as hechas, 
					sum ( isnull( [Rechazo Fund] , 0) +  isnull( [Rechazo Maq] , 0 ) ) as rechazadas ,
					maquina as clave,
					DATEPART(WEEK, fecha) as semana ,
					DATEPART(year,fecha) as aio
					 from  ete2.dbo.[Detalle de ETE] as DE 
					left join ete2.dbo.ETE as e  on de.Consecutivo = e.Consecutivo
					LEFT JOIN pdp_maquina as m on e.idmaquina = m.id 
					

				
					GROUP BY
					Producto,
					DATEPART(year,fecha),
					DATEPART(WEEK, fecha),
					[Num Operacion],
					maquina
				
				
				) AS ETE_S1 on 
					ETE_S1.producto = prod.PRODUCTO and 
					ETE_S1.semana = 	$se1 and
					ETE_S1.aio  =   $aio and
					ETE_S1.OP =	sem.op
					and ETE_S1.clave = sem.maquina

				LEFT JOIN(
				
					select 

					Producto,
					[Num Operacion]   as OP, 
					sum ([Piezas Maquinadas] )as hechas, 
					sum ( isnull( [Rechazo Fund] , 0) +  isnull( [Rechazo Maq] , 0 ) ) as rechazadas ,
					maquina as clave,
					DATEPART(WEEK, fecha) as semana ,
					DATEPART(year,fecha) as aio
					 from  ete2.dbo.[Detalle de ETE] as DE 
					left join ete2.dbo.ETE as e  on de.Consecutivo = e.Consecutivo
					LEFT JOIN pdp_maquina as m on e.idmaquina = m.id 
					

				
					GROUP BY
					Producto,
					DATEPART(year,fecha),
					DATEPART(WEEK, fecha),
					[Num Operacion],
					maquina
				
				
				) AS ETE_S2 on 
					ETE_S2.producto = prod.PRODUCTO and 
					ETE_S2.semana = 	$se2 and
					ETE_S2.aio  =   $aio and
					ETE_S2.OP =	sem.op
					and ETE_S2.clave = sem.maquina
				
				LEFT JOIN(
				
					select 

					Producto,
					[Num Operacion]   as OP, 
					sum ([Piezas Maquinadas] )as hechas, 
					sum ( isnull( [Rechazo Fund] , 0) +  isnull( [Rechazo Maq] , 0 ) ) as rechazadas ,
					maquina as clave,
					DATEPART(WEEK, fecha) as semana ,
					DATEPART(year,fecha) as aio
					 from  ete2.dbo.[Detalle de ETE] as DE 
					left join ete2.dbo.ETE as e  on de.Consecutivo = e.Consecutivo
					LEFT JOIN pdp_maquina as m on e.idmaquina = m.id 
					

				
					GROUP BY
					Producto,
					DATEPART(year,fecha),
					DATEPART(WEEK, fecha),
					[Num Operacion],
					maquina
				
				
				) AS ETE_S3 on 
					ETE_S3.producto = prod.PRODUCTO and 
					ETE_S3.semana = 	$se3 and
					ETE_S3.aio  =   $aio and
					ETE_S3.OP =	sem.op
					and ETE_S3.clave = sem.maquina
				
				LEFT JOIN(
				
					 

					select 

					Producto,
					[Num Operacion]   as OP, 
					sum ([Piezas Maquinadas] )as hechas, 
					sum ( isnull( [Rechazo Fund] , 0) +  isnull( [Rechazo Maq] , 0 ) ) as rechazadas ,
					maquina as clave,
					DATEPART(WEEK, fecha) as semana ,
					DATEPART(year,fecha) as aio
					 from  ete2.dbo.[Detalle de ETE] as DE 
					left join ete2.dbo.ETE as e  on de.Consecutivo = e.Consecutivo
					LEFT JOIN pdp_maquina as m on e.idmaquina = m.id 
					

				
					GROUP BY
					Producto,
					DATEPART(year,fecha),
					DATEPART(WEEK, fecha),
					[Num Operacion],
					maquina
				
				
				) AS ETE_S4 on 
					ETE_S4.producto = prod.PRODUCTO and 
					ETE_S4.semana = 	$se4 and
					ETE_S4.aio  =   $aio and
					ETE_S4.OP =	sem.op 
					and ETE_S4.clave = sem.maquina
					
				where prod.PRODUCTO  in (select pieza from pdp_maquinado_bl)
				

				
		")->queryAll();
		
		return $result[0];
		
	  }
	
	public function getInvCasting($parte){
	$sql = "
			select
			 prod.IDENTIFICACION as PRODUCTO , 
			 prod.CAMPOUSUARIO5 as casting,

			isnull(almplb.existencia,0)+isnull(almplb2.existencia,0) as PL,
			isnull(almpmb.existencia,0)+isnull(almpmb2.existencia,0) as PM,
			isnull(almctb.existencia,0)+isnull(almctb2.existencia,0) as CT

			from  producto as  prod

				LEFT JOIN(
					SELECT   
						sum(DuxSinc.dbo.ALMPROD.EXISTENCIA) AS EXISTENCIA, DuxSinc.dbo.ALMPROD.producto
					FROM DuxSinc.dbo.ALMPROD
					WHERE 
					DuxSinc.dbo.ALMPROD.ALMACEN =   'CTB'
					GROUP BY DuxSinc.dbo.ALMPROD.producto
				) as almctb on prod.CAMPOUSUARIO5 = almctb.producto
				
				LEFT JOIN(
					SELECT   
						sum(DuxSinc.dbo.ALMPROD.EXISTENCIA) AS EXISTENCIA, DuxSinc.dbo.ALMPROD.producto
					FROM DuxSinc.dbo.ALMPROD
					WHERE 
					DuxSinc.dbo.ALMPROD.ALMACEN =   'CTB2'
					GROUP BY DuxSinc.dbo.ALMPROD.producto
				) as almctb2 on prod.CAMPOUSUARIO5 = almctb2.producto

				LEFT JOIN(
					SELECT   
						sum(DuxSinc.dbo.ALMPROD.EXISTENCIA) AS EXISTENCIA , DuxSinc.dbo.ALMPROD.producto
					FROM DuxSinc.dbo.ALMPROD
					WHERE 
					DuxSinc.dbo.ALMPROD.ALMACEN =   'PTB'
					GROUP BY DuxSinc.dbo.ALMPROD.producto
				) as almptb on prod.CAMPOUSUARIO5 = almptb.producto

				LEFT JOIN(
					SELECT   
						sum(DuxSinc.dbo.ALMPROD.EXISTENCIA) AS EXISTENCIA , DuxSinc.dbo.ALMPROD.producto
					FROM DuxSinc.dbo.ALMPROD
					WHERE 
					DuxSinc.dbo.ALMPROD.ALMACEN =   'PLB'
					GROUP BY DuxSinc.dbo.ALMPROD.producto
				) as almplb on prod.CAMPOUSUARIO5 = almplb.producto

				LEFT JOIN(
					SELECT   
						sum(DuxSinc.dbo.ALMPROD.EXISTENCIA) AS EXISTENCIA, DuxSinc.dbo.ALMPROD.producto
					FROM DuxSinc.dbo.ALMPROD
					WHERE 
					DuxSinc.dbo.ALMPROD.ALMACEN =   'PLB2'
					GROUP BY DuxSinc.dbo.ALMPROD.producto
				) as almplb2 on prod.CAMPOUSUARIO5 = almplb2.producto
	
				LEFT JOIN(
					SELECT   
						sum(DuxSinc.dbo.ALMPROD.EXISTENCIA) AS EXISTENCIA, DuxSinc.dbo.ALMPROD.producto
					FROM DuxSinc.dbo.ALMPROD
					WHERE 
					DuxSinc.dbo.ALMPROD.ALMACEN =   'PMB'
					GROUP BY DuxSinc.dbo.ALMPROD.producto
				) as almpmb on prod.CAMPOUSUARIO5 = almpmb.producto

				LEFT JOIN(
					SELECT   
						sum(DuxSinc.dbo.ALMPROD.EXISTENCIA) AS EXISTENCIA, DuxSinc.dbo.ALMPROD.producto
					FROM DuxSinc.dbo.ALMPROD
					WHERE 
					DuxSinc.dbo.ALMPROD.ALMACEN =   'PMB2'
					GROUP BY DuxSinc.dbo.ALMPROD.producto
				) as almpmb2 on prod.CAMPOUSUARIO5 = almpmb2.producto

			WHERE prod.IDENTIFICACION = '$parte' and prod.CAMPOUSUARIO5 = '$parte'
	";
	$command = \Yii::$app->db_mysql;
        $result =$command
					->createCommand($sql)
					->queryAll();
					
	return $result ? $result[0] : null ;	
	}
	
	public function GetInfo_Maquina($semana){
	
	 $tmp = explode('-',$semana);
		  $s = substr($tmp[1],1);
		  
	
	$sql = "
Select    
pdp_maquina_turnos.maquina,

pdp_maquina_turnos.Matutino,
pdp_maquina_turnos.Vespertino,
pdp_maquina_turnos.Nocturno,
pdp_maquina_turnos.Mixto,

pdp_maquina_turnos.Minutos as minutos_m,
(pdp_maquina_turnos.Minutos)/60  as horas_m,
((pdp_maquina_turnos.Minutos)/60)/8  as t8_m,
((pdp_maquina_turnos.Minutos)/60)/9  as t9_m,

t.turno as t8_o,
t.turno*8  as horas_o,
((t.turno)*8)*60  as minutos_o

from pdp_maquina_turnos 
LEFT JOIN 
		(	Select    
			isnull( isnull(pdp_maquina_turnos.Matutino,0)/isnull(pdp_maquina_turnos.Matutino,1) ,0) +
			isnull( isnull(pdp_maquina_turnos.Vespertino,0)/isnull(pdp_maquina_turnos.Vespertino ,1),0) +
			isnull( isnull(pdp_maquina_turnos.Mixto,0)/isnull(pdp_maquina_turnos.Mixto ,1),0) +
			isnull( isnull(pdp_maquina_turnos.Nocturno,0)/isnull(pdp_maquina_turnos.Nocturno,1) ,0)
			as turno,
			pdp_maquina_turnos.maquina

			from pdp_maquina_turnos
			where
			semana = $s
		) as T  on t.maquina =  pdp_maquina_turnos.maquina
where
semana = $s 
";

		$command = \Yii::$app->db_mysql;
        $result =$command
					->createCommand($sql)
					->queryAll();
		
		
		 $minutos_m = 0;
		 $horas_m = 0;
		 $t8_m = 0;
		 $rows = 0;
		foreach($result as &$r ){
			$mm = $r['minutos_m'];// problema ocn number format y suma
			$r['minutos_m']=number_format($r['minutos_m'],0);
			$r['horas_m']=number_format($r['horas_m'],0);
			$r['t8_m']=number_format($r['t8_m'],1);
			$r['t9_m']=number_format($r['t9_m'],1);
			$r['t8_o']=number_format($r['t8_o'],1);
			$r['horas_o']=number_format($r['horas_o'],1);
			$r['minutos_o']=number_format($r['minutos_o'],1);
			
			$minutos_m += $mm; 
			$horas_m +=$r['horas_m'] ;
			$t8_m+=$r['t8_m'] ;
									
			$rows++;
		}
		
		
		$totales[0]['minutos_m'] = $minutos_m == 0 ? '' : number_format($minutos_m,1);
		$totales[0]['horas_m'] = $horas_m == 0 ? '': number_format($horas_m,1);
		$totales[0]['t8_m'] = $t8_m ? '' : number_format($t8_m,1);
		$totales[0]['maquina'] = 'Totales';
		
		$datos['rows'] = $result;
		$datos['footer'] = $totales;
		$datos['total'] = $rows;
		
		
		
		return $datos;
 

} 

public function GetInfo_programacion($semana){
	
	 $tmp = explode('-',$semana);
		  $s = substr($tmp[1],1);
		  
	//falta condicionar con semana en cta para query
	
	
	$sql = "
		
	
		select 
			cta.maquina,
			cta.pieza, 
			cta.cantidad, 
			cta.prioridad,
			pdp.hechas as buenas ,
			pdp.rechazo as malas,
			cta.minutos,
			cta.minutos/60 as horas,
			(cta.minutos/60)/8 as t8,
			(cta.minutos/60)/9 as t9
		from mysqlloc...pdp_cta as cta   
		left join pdp on pdp.producto = cta.pieza
	
	";
	
	$command = \Yii::$app->db_ete;
        $result =$command
					->createCommand($sql)
					->queryAll();
	
	
	 $minutos_m = 0;
		 $horas_m = 0;
		 $t8_m = 0;
		 $rows = 0;
		 $cantidad = 0;
		foreach($result as $r ){
			$minutos_m += $r['minutos']; 
			$horas_m +=$r['horas'] ;
			$cantidad += $r['cantidad'];
			$t8_m+=$r['t8'] ;
			$rows++;
		}
		
		$totales[0]['minutos'] =  number_format($minutos_m);
		$totales[0]['horas'] =  number_format($horas_m);
		$totales[0]['t8'] = $t8_m;
		$totales[0]['cantidad'] = $cantidad;
		$totales[0]['maquina'] = 'Totales';
		
		$datos['rows'] = $result;
		$datos['footer'] = $totales;
		$datos['total'] = $rows;
		

		return $datos;
	
}

public function GetInfo_Operador($semana){

	 $tmp = explode('-',$semana);
		  $s = substr($tmp[1],1);
		  
	
	$sql = "
	
		select turnos.*,empleado.NOMBRECOMPLETO as nombre
		from (
			select maquina,minutos_o as minutos,minutos_o/60 as horas,(minutos_o/60)/8 as t8,
			'Mat' as titulo,Matutino as turno from pdp_maquina_turnos
				where semana = $s  AND Matutino is not null
		UNION
			select maquina,minutos_o as minutos,minutos_o/60 as horas,(minutos_o/60)/8 as t8,
			'Ves' as titulo,Vespertino as turno from pdp_maquina_turnos
				where semana = $s AND Vespertino is not null
		UNION
			select maquina,minutos_o as minutos, minutos_o/60 as horas,(minutos_o/60)/8 as t8,
			'Noc' as titulo ,Nocturno as turno from pdp_maquina_turnos
				where semana = $s AND Nocturno is not null
		UNION
			select maquina,minutos_o as minutos, minutos_o/60 as horas,(minutos_o/60)/8 as t8,
			'Mix' as titulo ,Mixto as turno from pdp_maquina_turnos
				where semana = $s AND Mixto is not null
		) as turnos
		JOIN empleado on turnos.turno = empleado.CODIGOANTERIOR or turnos.turno-10000 = empleado.CODIGOANTERIOR
	
	";
	
	$command = \Yii::$app->db_mysql;

	$result =$command
					->createCommand($sql)
					->queryAll();
	

	return $result;
}


public function  GetInfo_pza_op($semana){

	 $tmp = explode('-',$semana);
		  $s = substr($tmp[1],1);
		  
	
	$sql = "
	
		select 
		pdp_cta.maquina,pdp_cta.op,pdp_cta.Pieza,pdp_cta.Cantidad,pdp_cta.semana,pdp_cta.op,
		CONCAT(emat.CODIGOANTERIOR+0 ,'-', emat.NOMBRECOMPLETO) as m,
		CONCAT(eves.CODIGOANTERIOR+0 ,'-', eves.NOMBRECOMPLETO) as v,
		CONCAT(enoc.CODIGOANTERIOR+0 ,'-', enoc.NOMBRECOMPLETO) as n
		CONCAT(enoc.CODIGOANTERIOR+0 ,'-', emix.NOMBRECOMPLETO) as x
		from pdp_cta 
		JOIN pdp_maquina_turnos on  pdp_maquina_turnos.semana = pdp_cta.semana and pdp_maquina_turnos.maquina = pdp_cta.Maquina
		left join empleado as emat on emat.CODIGOANTERIOR = Matutino   
		left join empleado as eves on eves.CODIGOANTERIOR = Vespertino
		left join empleado as enoc on enoc.CODIGOANTERIOR = Nocturno 
		left join empleado as emix on enoc.CODIGOANTERIOR = Mixto 
		where pdp_cta.Semana = $s 
		ORDER BY pdp_cta.Maquina
	
	";
	
	$command = \Yii::$app->db_mysql;

	$result =$command
					->createCommand($sql)
					->queryAll();
	

	return $result;
}
   
   // public function p1minutos($maq,$pieza) {
    // $command = \Yii::$app->db_mysql;
	// $sql = "Select min(minutos) as min from pdp_maquina_pieza where pieza ='$pieza' and maquina = '$maq' and op = 10";
	// $result =$command
					// ->createCommand($sql)
					// ->queryAll();
		
		// return $result[0]['min'];
   
   // }
   
   public function exist_hold($prod){
	   	$command = \Yii::$app->db_mysql;
		
		$sql = "
					
					Select  count(IDENTIFICACION) as min 
					from maq_piezas 
					where IDENTIFICACION ='$prod' 
					
					";
		
		$result =$command->createCommand($sql)->queryAll();
		$res =$command->createCommand($sql)->getRawSql();
					print_r($res);
					print_r($result);
					
		
		return $result[0]['min'] >  0 ? true : false;
	   
	   
   }
   
   public function hold($data){
   
	$command = \Yii::$app->db_mysql;
	
	$hold = $data->{'Hold'};
	$prod =  $data->{'producto'};
	
	//echo "HOLD $prod"; exit; 
	
	if ( $this->exist_hold($prod) ){
	
	 $result =$command->createCommand()->update('maq_piezas',[
												'Hold' => $hold
												], 	[
												'IDENTIFICACION' => $prod
												]
								)->execute();
								// )->getRawSql();
								// echo $result;
	}else{
		$result =$command->createCommand()->insert('maq_piezas',[
												'Hold' => 1,
												'Activo' => 1,
												'TP' => 'AC',
												'IDENTIFICACION' => $prod
												]
								)->execute();
								// )->getRawSql();
								// echo $result;
		
	}
	
	
   }
   
   
   public function maquina($pieza,$op){
		$command = \Yii::$app->db_mysql;
		
		if ($op == '') $op = 10;
		$sql = "
				Select maquina
					from pdp_maquina_pieza where pieza ='$pieza' and op = $op
				";
		

		$result =$command
					->createCommand($sql)
					->queryAll();
		
		
		
		return $result ? $result[0]['maquina'] : 0;
	}
	
	 public function maquina_ocupada($maquina){
		 $command = \Yii::$app->db_mysql;
		 $sql = "
				select  count( maq.Maquina ) as clave	
		from pdp_celda
		LEFT JOIN  pdp_maquina as maq  on maq.id = pdp_celda.[Codigo Maquina]
		where maq.Maquina = '$maquina'
				";
		

		$result =$command
					->createCommand($sql)
					->queryAll();
		
		
		
		return $result ? $result[0]['clave'] : 0;
		 
	 }
   
   
   public function getOperaciones(){
	   
	   $command = \Yii::$app->db_mysql;
	   
	   
	 $result =$command->createCommand("Select  op 
					from pdp_ops
					")
					->queryAll();

	return $result;   
	   
   }
   
   public function p1tiempos($pieza, $maquina,$op){
	   
				
				$m =    $this->maquinapiezamin($pieza,$op,$maquina);
			
	   
	   return $m; 
	   
   }   
   
   public function p1tiemposgrupo($pieza, $maquina){
	   
	   $command = \Yii::$app->db_mysql;
			$op = $this->getOperaciones();
			
			$minpieza = null;
			
			foreach($op as $o ){
				
				$m =    $this->maquinapiezamin($pieza,$o['op'],"");
			
			  if($m != null)
				  $minpieza += $m;
				 
//				 $minpieza .= " ".$m;
			
			}
	   
	   
	   return $minpieza; 
	   
   }
   
    
   
   // public function p2exist($maquina,$semana){
		
		// $command = \Yii::$app->db_mysql;
		
		// $result =$command
					// ->createCommand("
					
					// Select  count(Maquina) as maq 
					// from pdp_maquina_turnos
					// where maquina = '$maquina' and semana = '$semana'
					
					// ")
					// ->queryAll();
					
		// regresa mayor a 0 si existe			
		// return $result[0]['maq'] >  0 ? true : false;
	// }

	public function p1exist($pieza,$semana,$op,$maq){
		
		$command = \Yii::$app->db_mysql;
		
		$sql = "
					
					Select  count(Maquina) as min 
					from pdp_cta 
					where pieza ='$pieza'  and semana = '$semana' and op = $op  and Maquina = '$maq'
					
					";
		
		$result =$command->createCommand($sql)->queryAll();
		$res =$command->createCommand($sql)->getRawSql();
					print_r($res);
					print_r($result);
					
		
		return $result[0]['min'] >  0 ? true : false;
	}
	
	public function p2Turnos($maq,$sem){
		$command = \Yii::$app->db_mysql;
		$sql= "
		
		Select  Matutino,Vespertino,Nocturno
					from pdp_maquina_turnos
					where maquina = '$maq' and semana = $sem
		
		";
		$result =$command
					->createCommand($sql)->queryAll();
		
	return $result[0];
	}
	
	public function p2minutos($maquina,$semana){
		$command = \Yii::$app->db_mysql;
		$sql = "
		
					Select  minutos as min 
					from pdp_maquina_turnos
					where maquina = '$maquina' and semana = '$semana'
		";
		
		$result =$command
					->createCommand($sql)->queryAll();
					
		//return $result[0]['min'];
		
	}
	
	public function p2save($data) {
		
			$command = \Yii::$app->db_mysql;
		
		$semana  =$data['semana']  ;
		
		$tmp = explode('-',$semana);
		  $semana = substr($tmp[1],1);
		 $data['semana'] = $semana;
				
				$data['minutos_m'] = str_replace(',','',$data['minutos_m']);
				if ($data['Matutino'] == 0) $data['Matutino'] = "";
				if ($data['Vespertino'] == 0) $data['Vespertino'] = "";
				if ($data['Nocturno'] == 0) $data['Nocturno'] = "";
				if ($data['Mixto'] == 0) $data['Mixto'] = "";
	
			print_r($data);
		// $min = $this->p2minutos($data['maquina'], $semana);
		
		// $turnosActual = $this->p2Turnos($data['maquina'],$semana);
		 // $turnoCapturado = array(  'Matutino'   => $data['Matutino'],
								  // 'Vespertino' => $data['Vespertino'],
								  // 'Nocturno'   => $data['Nocturno']
								// );
		 // $diff = array_diff_assoc($turnosActual,$turnoCapturado);
		// $arregloSize = count($diff);
		$turnos = 0; 
		if ($data['Matutino'] != null || $data['Matutino'] != '') $turnos ++;
		if ($data['Vespertino'] != null || $data['Vespertino'] != '') $turnos ++;
		if ($data['Nocturno'] != null || $data['Nocturno'] !=  '') $turnos ++;
		if ($data['Mixto'] != null || $data['Mixto'] !=  '') $turnos ++;
	
		if($turnos > 0 )
			$minutos = $data['minutos_m']/$turnos;
		else 
			$minutos = $data['minutos_m'];
		
		
			 $result =$command->createCommand()->update('pdp_maquina_turnos',[
									'Matutino' => $data['Matutino'],
									'Vespertino' => $data['Vespertino'],
									'Nocturno' => $data['Nocturno'], 
									'Mixto' => $data['Mixto'], 
									'minutos_o' => $minutos
									
									], 	[
									'maquina' => $data['maquina'],
									'semana' => $semana
									]
								)->execute();
		
			$this->maquinaturnossemana($data);
		
	}
	
	public function p1save($data) {
		$command = \Yii::$app->db_mysql;
		 // print_r($data); exit;
		$fsemana  =$data['semana']  ;
						
		$maq_pieza = $this->maquinapieza_todo($data['producto']);
		if( $data['otramaq'] == 1){
			
				$result =$command->createCommand()->insert('pdp_cta',[

									'pieza' => $data['producto'], //captura row
									'maquina' => $data['maquina'], // captura row  maquina1 maquina2 maquina3
									'cantidad' => $data['cantidad'],// captura row s1 s2 s3 s4
									'minutos' => $data['minutos'], // hacer funcion para sacar de maquina_pieza
									'semana' => $data['semana'], // de inpul cal
									'aio' => $data['aio'],// scaar año de sem actual
									'semEntrega' => $fsemana,
									'prioridad' => $data['prioridad'],
									'programado' => $data['programado'],
									'op' => $data['opx'],
		
			 ])->execute();
			// ])->getRawSql();
			// echo $result;
			$this->maquinaturnossemana( $data );
			 return true;
			
		}
		// $cambio_maq = 1;
		//echo 'grabando  existe : ' ;  echo $this->p1exist($data['producto'],$data['semana'],$data['opx'],$data['maquina']) ;print_r($data);exit;
		if (isset($data['oldmaq'])  ){
					$maquina = $data['oldmaq'];
					$actualiza = 1;
		}else{
					$maquina = $data['maquina'];
					$actualiza = 0;
		}
		//echo "G R A  B O ".$data['semana'];
		if (!$this->p1exist($data['producto'],$data['semana'],$data['opx'],$maquina) && $actualiza !=0 ){
			if ($data['cantidad'] == 0) return ;
			
		if ($data['otramaq'] == 0)
			$result =$command->createCommand()->insert('pdp_cta',[

									'pieza' => $data['producto'], //captura row
									'maquina' => $data['maquina'], // captura row  maquina1 maquina2 maquina3
									'cantidad' => $data['cantidad'],// captura row s1 s2 s3 s4
									'minutos' => $data['minutos'], // hacer funcion para sacar de maquina_pieza
									'semana' => $data['semana'], // de inpul cal
									'aio' => $data['aio'],// scaar año de sem actual
									'semEntrega' => $fsemana,
									'prioridad' => $data['prioridad'],
									'programado' => $data['programado'],
									'op' => $data['opx'],
									//'semana_q' => $data['semana_q']
			])->execute();
			// ])->getRawSql();
			
			
			//se llena pdp_maquina turnos
			$this->maquinaturnossemana( $data );
			
			//si es celda inserta en id pdp_prgcelda
			if( strlen($data['maquina']) > 7 ){
				$model = new Celda;
				$model->creaPRGCelda( $data['maquina']);
			}
		}else{
		  //echo ' existe se actualiza';
		  
			  if($data['cantidad'] == 0  ){
					
				$result =$command->createCommand()->delete('pdp_cta',[
														
														'semana' => $data['semana'],
														'op' => $data['opx'],
														'pieza' => $data['producto'],
														'maquina' => $data['maquina']
																					])->execute();
																					// ])->getRawSql();
																					// print_r($result);
				//se llena pdp_maquina turnos
				$this->maquinaturnossemana( $data );																	
				
												return true; //corta ejecucion y sale
				}
			  
			  $result =$command->createCommand()->update('pdp_cta',[
										'maquina' => $data['maquina'],
										'prioridad' => $data['prioridad'],
										'minutos' => $data['minutos'],
										'cantidad' => $data['cantidad'] 
										], 	[
										
										'pieza' => $data['producto'],
										'op' => $data['opx'],
										'semana' => $data['semana'],
										'maquina' => $maquina
										]
									)->execute();
								// )->getRawSql();
								// echo $result;
				//se llena pdp_maquina turnos
				$this->maquinaturnossemana( $data );
			//si es celda se actualiza pdp_prgcta
									
		  }
		echo "query: $result \n";	

		
		
	
	}
	
	// public function maquinaturno_todo($maquina,$semana){
		// $command = \Yii::$app->db_mysql;
		// $sql = "
			// Select * from pdp_maquina_turnos 
					// where  maquina = '$maquina' and semana = $semana
		
		// ";
		// $result =$command
					// ->createCommand($sql)
					// ->queryAll();
	
		// return $result;
	// }
	
	public function MaquinaSemanaCtaexist($maquina,$semana){
		
		$command = \Yii::$app->db_mysql;
		
		$sql = 
		"Select  count(Maquina) as turno 
					from pdp_cta
					where  maquina = '$maquina' and semana = $semana";
		
		$result =$command
					->createCommand($sql)
					->queryAll();
		
		// regresa mayor a 0 si existe		
		return $result[0]['turno'] >  0 ? true : false;
		
		
	}
		
		
		
		
	
	public function maquinaturnosexist($maquina,$semana){
		
		$command = \Yii::$app->db_mysql;
		
		$sql = 
		"Select  count(Maquina) as turno 
					from pdp_maquina_turnos
					where  maquina = '$maquina' and semana = $semana";
		
		$result =$command
					->createCommand($sql)
					->queryAll();
		
		// regresa mayor a 0 si existe			
		return $result[0]['turno'];
	
	}
	
	
	
	public function calMaquinaSemana($maquina,$semana){
		
		$command = \Yii::$app->db_mysql;
		
		$sql = 
		"
		select sum(ct.cantidad*mp.Minutos)  as min 
		from pdp_cta as ct 
		LEFT JOIN pdp_maquina_pieza as mp on mp.Pieza = ct.Pieza and mp.Maquina = ct.Maquina and ct.op = mp.OP

		where ct.maquina = '$maquina' and ct.semana = $semana ";
		
		$result =$command
					->createCommand($sql)
					->queryAll();
		
		// regresa mayor a 0 si existe			
		return $result[0]['min'];
		
	}
	
	public function maquinaturnossemana($data){
		$command = \Yii::$app->db_mysql;
	
		//$pieza = $data['producto']; 
		$maquina = $data['maquina'];
		//$cantidad = $data['cantidad'];
		$semana = $data['semana'];
			
			$minutos =  $this->calMaquinaSemana($maquina,$semana);
	    $minutos = (int)$minutos; 
		if($this->maquinaturnosexist($maquina,$semana)){
			// update o delete
		
			//si no existe en cta la maquina con ninguna pieza en la semana especificada
			if( !$this->MaquinaSemanaCtaexist($maquina,$semana)){	
					  	
						$result =$command->createCommand()->delete('pdp_maquina_turnos',[
																	'maquina' => $maquina,
																	'semana' => $semana
																	])->execute();
																	// ])->getRawSql();
																	// print_r($result);
						return true; //corta ejecucion y sale
			}
			 
						$result =$command->createCommand()->update('pdp_maquina_turnos',[
												'minutos' => $minutos
												], 	[
												'maquina' => $maquina,
												'semana' => $semana
												]
											)->execute();
											 // )->getRawSql();
											 // print_r($result);
			
			
		}else{
			
				
			$result =$command->createCommand()->insert('pdp_maquina_turnos',[
									'minutos' => $minutos,
									'maquina' => $maquina,
									'semana' => $semana
									]
								) ->execute();
								// )->getRawSql();
							 // print_r($result);
								
		}
		
			
	}
	
	public function maquinapieza($pieza,$op){
		$command = \Yii::$app->db_mysql;
		$result =$command
					->createCommand("
							Select distinct mp.maquina,mp.minutos,*
							from pdp_maquina_pieza as mp 
						RIGHT JOIN pdp_maquina m on  mp.maquina = m.maquina and m.activa = 1
							 where mp.pieza ='$pieza' and mp.op = $op 
							
							order by mp.minutos ASC
							")
					->queryAll();
		
		return $result;
	}
	
	public function maquinapiezamin($pieza,$op,$maquina){
		$command = \Yii::$app->db_mysql;
		$sql = "
				Select minutos
					from pdp_maquina_pieza where pieza ='$pieza' and op = $op
				";
		
		if ($maquina != "")
			$sql .= " and maquina = '$maquina' ";
		
		if ($op == null || $maquina == null ){
			return 0;
			
		}
		
		$result =$command
					->createCommand($sql)
					->queryAll();
		
		//$result[0]['minutos'] =  (double)$result[0]['minutos'];
		
		return $result ? $result[0]['minutos'] : null;
	}

	
	public function maquinaoperador($maquina){
		$command = \Yii::$app->db_mysql;
		$r1 =$command
					->createCommand("
				select operador , rtrim( empleado.CODIGOANTERIOR)+'-'+empleado.NOMBRECOMPLETO as NOMBRECOMPLETO
					from maquina_operador
					left join  Empleado  on empleado.CODIGOANTERIOR = maquina_operador.operador
					where maquina = '". $maquina."'
					UNION
					select operador+10000 , concat ('FAN-',rtrim(empleado.CODIGOANTERIOR) ,'-',empleado.NOMBRECOMPLETO) as NOMBRECOMPLETO
					from maquina_operador
					left join  Empleado  on empleado.CODIGOANTERIOR = maquina_operador.operador
					where maquina = '". $maquina."'
					
					
					")
					->queryAll();
		$r0 =[ "-1" => ["operador"=>"---","NOMBRECOMPLETO"=>"---"]];
		$result = array_merge($r0,$r1);
		return $result;
	}
	
	public function maquinapieza_todo($pieza){
		$command = \Yii::$app->db_mysql;
		$result =$command
					->createCommand("Select * from pdp_maquina_pieza where pieza ='". $pieza."'")
					->queryAll();
		
		return $result;
	}
	
	//operaciones amarillo
	
	public function maquinapieza_todo_mp($pieza){
		$command = \Yii::$app->db_mysql;
		$result =$command
					->createCommand("Select maquina,op from pdp_maquina_pieza where pieza ='$pieza' ORDER BY maquina,op asc")
					->queryAll();
		
		return $result;
	}
	
	
	public function operacionesPieza($pieza){
		$command = \Yii::$app->db_mysql;
		$result =$command
					->createCommand("Select MAX(op) as op from pdp_maquina_pieza where pieza = '$pieza'")
					->queryAll();
		
		$ops = $result[0]['op'];
		$ops = $ops /10;

		return $ops;
	}
	
	
	  public function p1ops($pieza){
	   				
		$res= $this->maquinapieza_todo_mp($pieza);
		// $ops= $this->operacionesPieza($pieza);
		$maq = null;
		// $i = 1;
			foreach ($res as $mp){
				
				// if ($maq == null){
					// $maq = $mp['maquina'];
				// }
				
				// if ($i == $ops &&  $maq !=  $mp['maquina']){
					// $maq = $mp['maquina'] ;
				// }
				
				// if( $maq !=  $mp['maquina'] && ($i*10 != $mp['op'])  ){
					// return 0;
				// } 

				
			 	// if ($i < $ops ){
					// $i++;
				// }else{
					// $i=1;
				// }
				
				
				if ( $maq !=  $mp['maquina']){
					$maq = $mp['maquina'] ;
					$i = 1;
				 }
				 
				 if( $maq !=  $mp['maquina'] || ($i*10 != $mp['op'])  ){
					return 0;
				} 
				$i++;
				
			}
			

		return 1;
		}
	
}