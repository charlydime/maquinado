<?php

namespace frontend\Models\Maquinado;
use Yii;
use yii\base\Model;

Class MaquinadoCTA4 extends Model {

    public function GetInfo($semana) {
          $tmp = explode('-',$semana);
		  $tmp_s = substr($tmp[1],1);
		$se1 =  $tmp_s +0;
		$se2 =  $tmp_s +1;
		 
		 $lun = $this->semana2fecha($tmp[0],$se1,'lun');
		 $mar = $this->semana2fecha($tmp[0],$se1,'mar');
		 $mie = $this->semana2fecha($tmp[0],$se1,'mie');
		 $jue = $this->semana2fecha($tmp[0],$se1,'jue');
		 $vie = $this->semana2fecha($tmp[0],$se1,'vie');
		 $sab = $this->semana2fecha($tmp[0],$se1,'sab');
		 $dom = $this->semana2fecha($tmp[0],$se1,'dom');
		
        $command = \Yii::$app->db_mysql;
        $result =$command->createCommand("
 				
				select 
				pdp_cta.Pieza,
				pdp_cta.Prioridad,
				pdp_cta.Cantidad,
				pdp_cta.Maquina,
				pdp_cta.op,
				pdp_cta.Minutos as minmaq,
				round(480/pdp_cta.Minutos) as p_t,
				pdp_cta.Minutos * pdp_cta.Cantidad as Minutos,
				IFNULL(almpla.existencia,0)+IFNULL(almpla2.existencia,0) as PLA,
				IFNULL(almpma.existencia,0)+IFNULL(almpma2.existencia,0) as PMA,
				IFNULL(almcta.existencia,0)+IFNULL(almcta2.existencia,0) as CTA,
				almcta.existencia as CTA,
				almpta.existencia as PTA,
				dux1.fechaemb as e0,
				
				lun.cantidad as lun_prg,
				lun.min as lun_min,
				
				mar.cantidad as mar_prg,
				mar.min as mar_min,
				
				mie.cantidad as mie_prg,
				mie.min as mie_min,
				
				jue.cantidad as jue_prg,
				jue.min as jue_min,
				
				vie.cantidad as vie_prg,
				vie.min as vie_min,
				
				sab.cantidad as sab_prg,
				sab.min as sab_min,
			
				dom.cantidad as dom_prg,
				dom.min as dom_min,
				
				IFNULL(lun.cantidad,0)+
				IFNULL(mar.cantidad,0)+
				IFNULL(mie.cantidad,0)+
				IFNULL(jue.cantidad,0)+
				IFNULL(vie.cantidad,0)+
				IFNULL(sab.cantidad,0)+
				IFNULL(dom.cantidad,0)
				
				as sum,
				
				pdp_cta.Cantidad  -
				(
				IFNULL(lun.cantidad,0)+
				IFNULL(mar.cantidad,0)+
				IFNULL(mie.cantidad,0)+
				IFNULL(jue.cantidad,0)+
				IFNULL(vie.cantidad,0)+
				IFNULL(sab.cantidad,0)+
				IFNULL(dom.cantidad,0)
				)
				as rest,
				
				IFNULL(lun.min,0)+
				IFNULL(mar.min,0)+
				IFNULL(mie.min,0)+
				IFNULL(jue.min,0)+
				IFNULL(vie.min,0)+
				IFNULL(sab.min,0)+
				IFNULL(dom.min,0)
				
				as sum_min,
				
				pdp_cta.Minutos  * pdp_cta.Cantidad -
				(
				IFNULL(lun.min,0)+
				IFNULL(mar.min,0)+
				IFNULL(mie.min,0)+
				IFNULL(jue.min,0)+
				IFNULL(vie.min,0)+
				IFNULL(sab.min,0)+
				IFNULL(dom.min,0)
				)
				as rest_min
				
				
				from pdp_cta 
				

				LEFT JOIN(
						SELECT 
						 ALMPROD.producto,min(PAROEN.doctoadicionalfecha) as fechaemb, sum(CANTIDAD) as cantidad
						FROM ALMPROD
						LEFT JOIN PAROEN on ALMPROD.producto = PAROEN.PRODUCTO
						WHERE
						DATE_FORMAT( PAROEN.doctoadicionalfecha ,'%U') = $se2
						and almprod.ALMACEN = 'CTA'
						GROUP BY ALMPROD.producto
						order by ALMPROD.producto
				) as dux1 on pdp_cta.Pieza = dux1.producto 


				LEFT JOIN(
					SELECT   
						sum(ALMPROD.EXISTENCIA) AS EXISTENCIA, almprod.producto
					FROM ALMPROD
					WHERE 
					ALMPROD.ALMACEN =   'CTA'
					GROUP BY almprod.producto
				) as almcta on pdp_cta.Pieza = almcta.producto
				
				LEFT JOIN(
					SELECT   
						sum(ALMPROD.EXISTENCIA) AS EXISTENCIA, almprod.producto
					FROM ALMPROD
					WHERE 
					ALMPROD.ALMACEN =   'CTA2'
					GROUP BY almprod.producto
				) as almcta2 on pdp_cta.Pieza = almcta2.producto

				LEFT JOIN(
					SELECT   
						sum(ALMPROD.EXISTENCIA) AS EXISTENCIA , almprod.producto
					FROM ALMPROD
					WHERE 
					ALMPROD.ALMACEN =   'PTA'
					GROUP BY almprod.producto
				) as almpta on pdp_cta.Pieza = almpta.producto

				LEFT JOIN(
					SELECT   
						sum(ALMPROD.EXISTENCIA) AS EXISTENCIA , almprod.producto
					FROM ALMPROD
					WHERE 
					ALMPROD.ALMACEN =   'PLA'
					GROUP BY almprod.producto
				) as almpla on pdp_cta.Pieza = almpla.producto

				LEFT JOIN(
					SELECT   
						sum(ALMPROD.EXISTENCIA) AS EXISTENCIA, almprod.producto
					FROM ALMPROD
					WHERE 
					ALMPROD.ALMACEN =   'PLA2'
					GROUP BY almprod.producto
				) as almpla2 on pdp_cta.Pieza = almpla2.producto
	
				LEFT JOIN(
					SELECT   
						sum(ALMPROD.EXISTENCIA) AS EXISTENCIA, almprod.producto
					FROM ALMPROD
					WHERE 
					ALMPROD.ALMACEN =   'PMA'
					GROUP BY almprod.producto
				) as almpma on pdp_cta.Pieza = almpma.producto

				LEFT JOIN(
					SELECT   
						sum(ALMPROD.EXISTENCIA) AS EXISTENCIA, almprod.producto
					FROM ALMPROD
					WHERE 
					ALMPROD.ALMACEN =   'PMA2'
					GROUP BY almprod.producto
				) as almpma2 on pdp_cta.Pieza = almpma2.producto
				
				LEFT JOIN(
					select cantidad,min,operador,pieza,dia,op from pdp_cta_dia
				)as lun on pdp_cta.Pieza = lun.pieza and pdp_cta.op = lun.op and lun.dia = '$lun'
				
				LEFT JOIN(
					select cantidad,min,operador,pieza,dia,op from pdp_cta_dia
				)as mar on pdp_cta.Pieza = mar.pieza and pdp_cta.op = mar.op and mar.dia = '$mar'
				
				LEFT JOIN(
					select cantidad,min,operador,pieza,dia,op from pdp_cta_dia
				)as mie on pdp_cta.Pieza = mie.pieza and pdp_cta.op = mie.op and mie.dia = '$mie'
				
				LEFT JOIN(
					select cantidad,min,operador,pieza,dia,op from pdp_cta_dia
				)as jue on pdp_cta.Pieza = jue.pieza and pdp_cta.op = jue.op and jue.dia = '$jue'
				
				LEFT JOIN(
					select cantidad,min,operador,pieza,dia,op from pdp_cta_dia
				)as vie on pdp_cta.Pieza = vie.pieza and pdp_cta.op = vie.op and vie.dia = '$vie'
				
				LEFT JOIN(
					select cantidad,min,operador,pieza,dia,op from pdp_cta_dia
				)as sab on pdp_cta.Pieza = sab.pieza and pdp_cta.op = sab.op and sab.dia = '$sab'
				
				LEFT JOIN(
					select cantidad,min,operador,pieza,dia,op from pdp_cta_dia
				)as dom on pdp_cta.Pieza = dom.pieza and pdp_cta.op = dom.op and dom.dia = '$dom'
				
				where semana = $se1 
				
			")->queryAll();
			
			if(count($result)!=0){
				$tsum= 0;
				$tsum_min = 0;
				$tp = 0;
				$tm = 0;
				$tlp = 0;
				$tlm = 0;
				$tmp = 0;
				$tmm = 0;
				$tip = 0;
				$tim = 0;
				$tjp = 0;
				$tjm = 0;
				$tvp = 0;
				$tvm = 0;
				$tsp = 0;
				$tsm = 0;
				$tdp = 0;
				$tdm = 0;
				$rows=0;
				foreach($result as &$r){
					$tp += $r["Cantidad"];
					$tm += $r["Minutos"];
					
					$tlp += $r["lun_prg"];
					$tlm += $r["lun_min"];
					$tmp += $r["mar_prg"];
					$tmm += $r["mar_min"];
					$tip += $r["mie_prg"];
					$tim += $r["mie_min"];
					$tjp += $r["jue_prg"];
					$tjm += $r["jue_min"];
					$tvp += $r["vie_prg"];
					$tvm += $r["vie_min"];
					$tsp += $r["sab_prg"];
					$tsm += $r["sab_min"];
					$tdp += $r["dom_prg"];
					$tdm += $r["dom_min"];
					
					$tsum += $r["sum"];
					$tsum_min += $r["sum_min"];
					
					$r["p_t"] = number_format($r["p_t"],2);
					
					if ($r['CTA'] ==  0) $r['CTA'] = ''; 
					if ($r['PLA'] ==  0) $r['PLA'] = ''; 
					if ($r['PMA'] ==  0) $r['PMA'] = ''; 
					if ($r['PTA'] ==  0) $r['PTA'] = ''; 
					if ($r['sum'] ==  0) $r['sum'] = ''; 
					if ($r['rest'] ==  0) $r['rest'] = ''; 
					if ($r['rest_min'] ==  0) $r['rest_min'] = ''; 
					if ($r['sum_min'] ==  0) $r['sum_min'] = ''; 
					$rows++;
				}
				
				
				$totales[0]['Minutos'] = $tm;
				
				//$totales[0]['lun_prg'] = $tlp;
				$totales[0]['lun_min'] = $tlm;
				// $totales[0]['mar_prg'] = $tmp;
				$totales[0]['mar_min'] = $tmm;
				// $totales[0]['mie_prg'] = $tip;
				$totales[0]['mie_min'] = $tim;
				// $totales[0]['jue_prg'] = $tjp;
				$totales[0]['jue_min'] = $tjm;
				// $totales[0]['vie_prg'] = $tvp;
				$totales[0]['vie_min'] = $tvm;
				// $totales[0]['sab_prg'] = $tsp;
				$totales[0]['sab_min'] = $tsm;
				// $totales[0]['dom_prg'] = $tdp;
				$totales[0]['dom_min'] = $tdm;
				
				$totales[0]['sum_min'] = $tsum_min ;
				
				$totales[0]['Pieza'] = 'Totales Minutos:';
				
				//$totales[0]['lun_prg'] = $tlp;
				$totales[1]['lun_min'] = number_format($tlm / 60);
				// $totales[0]['mar_prg'] = $tmp;
				$totales[1]['mar_min'] = number_format($tmm / 60);
				// $totales[0]['mie_prg'] = $tip;
				$totales[1]['mie_min'] = number_format($tim /60);
				// $totales[0]['jue_prg'] = $tjp;
				$totales[1]['jue_min'] = number_format($tjm / 60);
				// $totales[0]['vie_prg'] = $tvp;
				$totales[1]['vie_min'] =number_format($tvm / 60);
				// $totales[0]['sab_prg'] = $tsp;
				$totales[1]['sab_min'] = number_format($tsm / 60) ;
				// $totales[0]['dom_prg'] = $tdp;
				$totales[1]['dom_min'] = number_format($tdm / 60);
				
				$totales[1]['sum_min'] = number_format($tsum_min / 60);
				
				$totales[1]['Pieza'] = 'Totales horas:';
				
				//$totales[0]['lun_prg'] = $tlp;
				$totales[2]['lun_min'] = number_format(($tlm / 60)/8);
				// $totales[0]['mar_prg'] = $tmp;
				$totales[2]['mar_min'] = number_format(($tmm / 60)/8);
				// $totales[0]['mie_prg'] = $tip;
				$totales[2]['mie_min'] = number_format(($tim /60)/8);
				// $totales[0]['jue_prg'] = $tjp;
				$totales[2]['jue_min'] = number_format(($tjm / 60)/8);
				// $totales[0]['vie_prg'] = $tvp;
				$totales[2]['vie_min'] = number_format(($tvm / 60)/8);
				// $totales[0]['sab_prg'] = $tsp;
				$totales[2]['sab_min'] = number_format(($tsm / 60)/8) ;
				// $totales[0]['dom_prg'] = $tdp;
				$totales[2]['dom_min'] = number_format(($tdm / 60)/8);
				
				$totales[2]['sum_min'] = number_format(($tsum_min / 60)/8 );
				
				$totales[2]['Pieza'] = 'Totales turno T8:';
				
				
				//$totales[0]['lun_prg'] = $tlp;
				$totales[3]['lun_min'] = number_format(($tlm / 60)/9);
				// $totales[0]['mar_prg'] = $tmp;
				$totales[3]['mar_min'] = number_format(($tmm / 60)/9);
				// $totales[0]['mie_prg'] = $tip;
				$totales[3]['mie_min'] = number_format(($tim /60)/9);
				// $totales[0]['jue_prg'] = $tjp;
				$totales[3]['jue_min'] = number_format(($tjm / 60)/9);
				// $totales[0]['vie_prg'] = $tvp;
				$totales[3]['vie_min'] = number_format(($tvm / 60)/9);
				// $totales[0]['sab_prg'] = $tsp;
				$totales[3]['sab_min'] = number_format(($tsm / 60)/9) ;
				// $totales[0]['dom_prg'] = $tdp;
				$totales[3]['dom_min'] = number_format(($tdm / 60)/9);
				
				$totales[3]['sum_min'] = ($tsum_min / 60)/9 ;
				
				$totales[3]['Pieza'] = 'Totales turno T9:';
				
				
				$totales[4]['Cantidad'] = $tp;
				$totales[4]['lun_prg'] = $tlp;
				// $totales[0]['lun_min'] = $tlm;
				$totales[4]['mar_prg'] = $tmp;
				// $totales[0]['mar_min'] = $tmm;
				$totales[4]['mie_prg'] = $tip;
				// $totales[0]['mie_min'] = $tim;
				$totales[4]['jue_prg'] = $tjp;
				// $totales[0]['jue_min'] = $tjm;
				$totales[4]['vie_prg'] = $tvp;
				// $totales[0]['vie_min'] = $tvm;
				$totales[4]['sab_prg'] = $tsp;
				// $totales[0]['sab_min'] = $tsm;
				$totales[4]['dom_prg'] = $tdp;
				
				// $totales[0]['dom_min'] = $tdm;
				
				$totales[4]['sum'] = $tsum ;
				
				$totales[4]['Pieza'] = 'Totales Piezas:';
			}
			
		$datos['rows'] = $result;
		$datos['footer'] = $totales;
		$datos['total'] = $rows;
        
          return $datos; 
        }   
		
		public function semana2fecha($a,$s,$dia){
			
			$sem = [  
					0 =>'lun',
					1 =>'mar',
					2 =>'mie',
					3 =>'jue',
					4 =>'vie',
					5 =>'sab',
					6 =>'dom' 
					];
			
			$numdia = array_search($dia , $sem);
			
			if($s > 0 and $s < 54){
						
				$dia_ini = $s * 7 -10;
				$dia1 = "$a-01-01";
				$fecha_ini = date('Y-m-d',strtotime("$dia1 + $dia_ini DAY") );
			
			}else{
				return false; //semana no valida
			}
			
			return date('Y-m-d',strtotime("$fecha_ini + $numdia DAY") );
				
		}
		public function save_dia($datos,$sem){
			$command = \Yii::$app->db_mysql;			
			$a = date('Y');
			$datarec=null;
			
			
			foreach ($datos as $data){
			
					$datarec['Maquina'] = $data->{'Maquina'};
					$datarec['Pieza'] = $data->{'Pieza'};
					$datarec['op'] = $data->{'op'};
					$datarec['sem'] = $sem;
					// echo "prepara:";print_r ($data);
					if($data->{'lun_prg'} != '' && $data->{'lun_prg'} != 'n') {
						$datarec['fecha'] = $this->semana2fecha($a,$sem,'lun');
						$datarec['cantidad'] = $data->{'lun_prg'};
						$datarec['min'] = $data->{'lun_min'};
						
						$this->save($datarec);
						
					}
					if($data->{'mar_prg'} != '' && $data->{'mar_prg'} != 'n') {
						$datarec['fecha'] = $this->semana2fecha($a,$sem,'mar');
						$datarec['cantidad'] = $data->{'mar_prg'};
						$datarec['min'] = $data->{'mar_min'};
						
						$this->save($datarec);
						
					}
					if($data->{'mie_prg'} != '' && $data->{'mie_prg'} != 'n') {
						$datarec['fecha'] = $this->semana2fecha($a,$sem,'mie');
						$datarec['cantidad'] = $data->{'mie_prg'};
						$datarec['min'] = $data->{'mie_min'};
						
						$this->save($datarec);
						
					}
					if($data->{'jue_prg'} != '' && $data->{'jue_prg'} != 'n') {
						$datarec['fecha'] = $this->semana2fecha($a,$sem,'jue');
						$datarec['cantidad'] = $data->{'jue_prg'};
						$datarec['min'] = $data->{'jue_min'};
						
						$this->save($datarec);
						
						
					}
					if($data->{'vie_prg'} != '' && $data->{'vie_prg'} != 'n') {
						$datarec['fecha'] = $this->semana2fecha($a,$sem,'vie');
						$datarec['cantidad'] = $data->{'vie_prg'};
						$datarec['min'] = $data->{'vie_min'};
						
						$this->save($datarec);
						
						
					}
					if($data->{'sab_prg'} != '' && $data->{'sab_prg'} != 'n') {
						$datarec['fecha'] = $this->semana2fecha($a,$sem,'sab');
						$datarec['cantidad'] = $data->{'sab_prg'};
						$datarec['min'] = $data->{'sab_min'};
						
						$this->save($datarec);
						
					}
					if($data->{'dom_prg'} != '' && $data->{'dom_prg'} != 'n') {
						$datarec['fecha'] = $this->semana2fecha($a,$sem,'dom');
						$datarec['cantidad']= $data->{'dom_prg'};
						$datarec['min'] = $data->{'dom_min'};
						
						$this->save($datarec);
						
					}
					print_r($datarec);
			}
		
		}
   
		public function save($data) {
		$command = \Yii::$app->db_mysql;
		 // echo "salvar"; 
		 // print_r($data);
		if (!$this->exist($data['fecha'],$data['Pieza'],$data['op'] ) ){
			if ( $data['cantidad'] == 0) return ;
			
			$result =$command->createCommand()->insert('pdp_cta_dia',[
									'maquina' => $data['Maquina'], 
									'semana' => $data['sem'],
									'dia' => $data['fecha'],
									'op' => $data['op'], 
									'pieza' => $data['Pieza'], 
									'cantidad' => $data['cantidad'],
									'min' => $data['min']
				])->execute();
			// ])->getRawSql();
			
		}else{
		  //echo ' existe se actualiza';
		 
			  if($data['cantidad'] == 0  ){
					
				$result =$command->createCommand()->delete('pdp_cta_dia',[
														'dia' => $data['fecha'],
														'op' => $data['op'],
														'pieza' => $data['Pieza']
													])->execute();
												// ])->getRawSql();
												 print_r($result);
										
				
					return true; //corta ejecucion y sale
				}
			  
			  $result =$command->createCommand()->update('pdp_cta_dia',[
										'maquina' => $data['Maquina'], 
										'semana' => $data['sem'],
										'cantidad' => $data['cantidad'],
										'min' => $data['min']
										], 	[
										'dia' => $data['fecha'],
										'op' => $data['op'],
										'pieza' => $data['Pieza']
										]
									)->execute();
								// )->getRawSql();
			
									
		  }
			print_r($result);

		}
		
		public function exist($dia,$pieza,$op) {
			
				$command = \Yii::$app->db_mysql;
		
		$result =$command
					->createCommand("
					
					Select  count(Maquina) as m 
					from pdp_cta_dia 
					where 
					pieza ='$pieza'  
					and dia = '$dia'
					and op = '$op'
					
					")->queryAll();
					
		
		return $result[0]['m'] >  0 ? true : false;
			
		}
        
		
		// diario
		public function GetInfo_op($fecha){
			$sql = "
				select d.*, 
					m.op as Matutino,
					v.op as Vespertino,
					n.op as Nocturno
					 from 
						(select maquina,
										sum(min) as min,
										sum(min/60) as min_hrs,
										sum( (min/60)/8 ) as min_t8 
						from pdp_cta_dia 
						where dia = '$fecha' 
						GROUP BY maquina
						)as d
					LEFT JOIN  pdp_maquina_turno_dia as m on  d.maquina = m.maquina and   m.dia =  '$fecha' and m.turno = 'Matutino'  
					LEFT JOIN  pdp_maquina_turno_dia as v on  d.maquina = v.maquina and   v.dia =  '$fecha' and v.turno = 'Vespertino'  
					LEFT JOIN  pdp_maquina_turno_dia as n on  d.maquina = n.maquina and   n.dia =  '$fecha' and n.turno = 'Nocturno'  
			";
			
			$command = \Yii::$app->db_mysql;
			$result =$command->createCommand($sql)->queryAll();
								// )->getRawSql();
			
			
			return $result;
		}
		
		public function save_opturno_p1($data,$dia){
			
			

			
			$guardar['minutos'] = $data->{'min'};
			$guardar['maquina'] = $data->{'maquina'};
			$guardar['dia']     = $dia;
			
	
			if($data->{'Matutino'} != '' ) {
	
				$guardar['operador'] =  $data->{'Matutino'};
				$guardar['turno'] =  'Matutino';
				$this->save_opturno_p2($guardar);
			}

			if($data->{'Vespertino'} != '' ) {
	
				$guardar['operador'] = $data->{'Vespertino'};
				$guardar['turno'] =  'Vespertino';
				$this->save_opturno_p2($guardar);
			}

			if($data->{'Nocturno'} != '' ) {
		
				$guardar['operador'] = $data->{'Nocturno'};
				$guardar['turno'] =  'Nocturno';
				$this->save_opturno_p2($guardar);
			}
			
			
		}
		
		public function exist_turno($dia,$maquina,$turno) {
			
				$command = \Yii::$app->db_mysql;
		
		$result =$command
					->createCommand("
					
					Select  count(Maquina) as m 
					from pdp_maquina_turno_dia 
					where maquina ='$maquina'  and dia = '$dia' and turno = '$turno'
					
					")->queryAll();
					
		
		return $result[0]['m'] >  0 ? true : false;
			
		}
		
		
		public function save_opturno_p2($data){
			
			 
			$command = \Yii::$app->db_mysql;
			
		
			
		if (!$this->exist_turno($data['dia'],$data['maquina'],$data['turno'] ) ){
			if ( $data['turno'] == '---') return ;
			
			$result =$command->createCommand()->insert('pdp_maquina_turno_dia',[
									'maquina' => $data['maquina'], 
									'dia' => $data['dia'],
									'turno' => $data['turno'],
									'minutos' => $data['minutos'],
									'op' => $data['operador']
				])->execute();
			// ])->getRawSql();
			
		}else{
		  //echo ' existe se actualiza';
		  
			  if($data['turno'] == '---'  ){
					
				$result =$command->createCommand()->delete('pdp_maquina_turno_dia',[
														'dia' => $data['dia'],
														'maquina' => $data['maquina']
													])->execute();
												// ])->getRawSql();
												
										
				
					return true; //corta ejecucion y sale
				}
			  
			  $result =$command->createCommand()->update('pdp_maquina_turno_dia',[
										'turno' => $data['turno'],
										'minutos' => $data['minutos'],
										'op' => $data['operador']
										], 	[
										'dia' => $data['dia'],
										'maquina' => $data['maquina']
										]
									)->execute();
								// )->getRawSql();
			
									
		  }
			
		
			
			
		}
		
		/// operadores
		public function GetInfo_diaop($fecha){
			$sql = "
				
				select   d.op, e.NOMBRECOMPLETO ,d.maquina,D.turno
				from pdp_maquina_turno_dia as d
				left join  Empleado as e on e.CODIGOANTERIOR+0 = d.op+0
				where d.dia = '$fecha'
				
			";
			
			$command = \Yii::$app->db_mysql;
			$result =$command->createCommand($sql)
									->queryAll();
								// )->getRawSql();
			
			
			return $result;
		}
     
}