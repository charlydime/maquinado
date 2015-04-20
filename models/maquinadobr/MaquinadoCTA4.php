<?php

namespace frontend\Models\Maquinadobr;
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
				pdp_ctb.Pieza,
				pdp_ctb.Prioridad,
				pdp_ctb.Cantidad,
				pdp_ctb.Maquina,
				pdp_ctb.op,
				pdp_ctb.Minutos as minmaq,
				round(480/pdp_ctb.Minutos,0) as p_t,
				pdp_ctb.Minutos * pdp_ctb.Cantidad as Minutos,
				isnull(almplb.existencia,0)+isnull(almplb2.existencia,0) as PLB,
				isnull(almpmb.existencia,0)+isnull(almpmb2.existencia,0) as PMB,
				isnull(almctb.existencia,0)+isnull(almctb2.existencia,0) as CTB,
				almctb.existencia as CTB,
				almptb.existencia as PTB,
				dux1.cantidad as e0,
				dux2.cantidad as e1,
				mp.Minutos1Maquinado as setup,
				
				lun.cantidad as lun_prg,
				lun.min as lun_min,
				lun.setup as lun_set,
				
				mar.cantidad as mar_prg,
				mar.min as mar_min,
				mar.setup as mar_set,
				
				mie.cantidad as mie_prg,
				mie.min as mie_min,
				mie.setup as mie_set,
				
				jue.cantidad as jue_prg,
				jue.min as jue_min,
				jue.setup as jue_set,
				
				vie.cantidad as vie_prg,
				vie.min as vie_min,
				vie.setup as vie_set,
				
				sab.cantidad as sab_prg,
				sab.min as sab_min,
				sab.setup as sab_set,
			
				dom.cantidad as dom_prg,
				dom.min as dom_min,
				dom.setup as dom_set,
				
				isnull(lun.cantidad,0)+
				isnull(mar.cantidad,0)+
				isnull(mie.cantidad,0)+
				isnull(jue.cantidad,0)+
				isnull(vie.cantidad,0)+
				isnull(sab.cantidad,0)+
				isnull(dom.cantidad,0)
				
				as sum,
				
				pdp_ctb.Cantidad  -
				(
				isnull(lun.cantidad,0)+
				isnull(mar.cantidad,0)+
				isnull(mie.cantidad,0)+
				isnull(jue.cantidad,0)+
				isnull(vie.cantidad,0)+
				isnull(sab.cantidad,0)+
				isnull(dom.cantidad,0)
				)
				as rest,
				
				isnull(lun.min,0)+
				isnull(mar.min,0)+
				isnull(mie.min,0)+
				isnull(jue.min,0)+
				isnull(vie.min,0)+
				isnull(sab.min,0)+
				isnull(dom.min,0)
				
				as sum_min,
				
				pdp_ctb.Minutos  * pdp_ctb.Cantidad -
				(
				isnull(lun.min,0)+
				isnull(mar.min,0)+
				isnull(mie.min,0)+
				isnull(jue.min,0)+
				isnull(vie.min,0)+
				isnull(sab.min,0)+
				isnull(dom.min,0)
				)
				as rest_min,
				
				isnull(lun.setup,0)+
				isnull(mar.setup,0)+
				isnull(mie.setup,0)+
				isnull(jue.setup,0)+
				isnull(vie.setup,0)+
				isnull(sab.setup,0)+
				isnull(dom.setup,0)
				
				as maq1
				
				from pdp_ctb 
				

				LEFT JOIN(
						SELECT 
						 ALMPROD.producto,min(PAROEN.doctoadicionalfecha) as fechaemb, sum(CANTIDAD) as cantidad
						FROM ALMPROD
						LEFT JOIN PAROEN on ALMPROD.producto = PAROEN.PRODUCTO
						WHERE
						DATEpart( week,PAROEN.doctoadicionalfecha ) = $se1
						-- and almprod.ALMACEN = 'CTB'
						GROUP BY ALMPROD.producto

						
				) as dux1 on pdp_ctb.Pieza = dux1.producto 
				
				LEFT JOIN(
						SELECT 
						 ALMPROD.producto,min(PAROEN.doctoadicionalfecha) as fechaemb, sum(CANTIDAD) as cantidad
						FROM ALMPROD
						LEFT JOIN PAROEN on ALMPROD.producto = PAROEN.PRODUCTO
						WHERE
						DATEpart( week,PAROEN.doctoadicionalfecha )= $se2
						-- and almprod.ALMACEN = 'CTB'
						GROUP BY ALMPROD.producto

						
				) as dux2 on pdp_ctb.Pieza = dux2.producto 


				LEFT JOIN(
					SELECT   
						sum(ALMPROD.EXISTENCIA) AS EXISTENCIA, almprod.producto
					FROM ALMPROD
					WHERE 
					ALMPROD.ALMACEN =   'CTB'
					GROUP BY almprod.producto
				) as almctb on pdp_ctb.Pieza = almctb.producto
				
				LEFT JOIN(
					SELECT   
						sum(ALMPROD.EXISTENCIA) AS EXISTENCIA, almprod.producto
					FROM ALMPROD
					WHERE 
					ALMPROD.ALMACEN =   'CTB2'
					GROUP BY almprod.producto
				) as almctb2 on pdp_ctb.Pieza = almctb2.producto

				LEFT JOIN(
					SELECT   
						sum(ALMPROD.EXISTENCIA) AS EXISTENCIA , almprod.producto
					FROM ALMPROD
					WHERE 
					ALMPROD.ALMACEN =   'PTB'
					GROUP BY almprod.producto
				) as almptb on pdp_ctb.Pieza = almptb.producto

				LEFT JOIN(
					SELECT   
						sum(ALMPROD.EXISTENCIA) AS EXISTENCIA , almprod.producto
					FROM ALMPROD
					WHERE 
					ALMPROD.ALMACEN =   'PLB'
					GROUP BY almprod.producto
				) as almplB on pdp_ctb.Pieza = almplb.producto

				LEFT JOIN(
					SELECT   
						sum(ALMPROD.EXISTENCIA) AS EXISTENCIA, almprod.producto
					FROM ALMPROD
					WHERE 
					ALMPROD.ALMACEN =   'PLB2'
					GROUP BY almprod.producto
				) as almplB2 on pdp_ctb.Pieza = almplb2.producto
	
				LEFT JOIN(
					SELECT   
						sum(ALMPROD.EXISTENCIA) AS EXISTENCIA, almprod.producto
					FROM ALMPROD
					WHERE 
					ALMPROD.ALMACEN =   'PMB'
					GROUP BY almprod.producto
				) as almpmb on pdp_ctb.Pieza = almpmb.producto

				LEFT JOIN(
					SELECT   
						sum(ALMPROD.EXISTENCIA) AS EXISTENCIA, almprod.producto
					FROM ALMPROD
					WHERE 
					ALMPROD.ALMACEN =   'PMB2'
					GROUP BY almprod.producto
				) as almpmb2 on pdp_ctb.Pieza = almpmb2.producto
				
				LEFT JOIN(
					select cantidad,min,operador,pieza,dia,op,setup from pdp_ctb_dia
				)as lun on pdp_ctb.Pieza = lun.pieza and pdp_ctb.op = lun.op and lun.dia = '$lun'
				
				LEFT JOIN(
					select cantidad,min,operador,pieza,dia,op,setup from pdp_ctb_dia
				)as mar on pdp_ctb.Pieza = mar.pieza and pdp_ctb.op = mar.op and mar.dia = '$mar'
				
				LEFT JOIN(
					select cantidad,min,operador,pieza,dia,op,setup from pdp_ctb_dia
				)as mie on pdp_ctb.Pieza = mie.pieza and pdp_ctb.op = mie.op and mie.dia = '$mie'
				
				LEFT JOIN(
					select cantidad,min,operador,pieza,dia,op,setup from pdp_ctb_dia
				)as jue on pdp_ctb.Pieza = jue.pieza and pdp_ctb.op = jue.op and jue.dia = '$jue'
				
				LEFT JOIN(
					select cantidad,min,operador,pieza,dia,op,setup from pdp_ctb_dia
				)as vie on pdp_ctb.Pieza = vie.pieza and pdp_ctb.op = vie.op and vie.dia = '$vie'
				
				LEFT JOIN(
					select cantidad,min,operador,pieza,dia,op,setup from pdp_ctb_dia
				)as sab on pdp_ctb.Pieza = sab.pieza and pdp_ctb.op = sab.op and sab.dia = '$sab'
				
				LEFT JOIN(
					select cantidad,min,operador,pieza,dia,op,setup from pdp_ctb_dia
				)as dom on pdp_ctb.Pieza = dom.pieza and pdp_ctb.op = dom.op and dom.dia = '$dom'
				
				LEFT JOIN 
				pdp_maquina_piezabr as mp  on  mp.Pieza = pdp_ctb.Pieza and mp.Maquina = pdp_ctb.Maquina and  mp.OP = pdp_ctb.OP
				
				where semana = $se1
				
				order by Maquina
				

			"
			)->queryAll();
			// )->getRawSql();
			// echo $result;exit;
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
				
				$gp = 0;
				$gm = 0;
				$glp = 0;
				$glm = 0;
				$gmp = 0;
				$gmm = 0;
				$gip = 0;
				$gim = 0;
				$gjp = 0;
				$gjm = 0;
				$gvp = 0;
				$gvm = 0;
				$gsp = 0;
				$gsm = 0;
				$gdp = 0;
				$gdm = 0;
				
				
				$congrupo = [];
				foreach($result as &$rini){
				
				$m = $rini['Maquina'] ; break;
				}
				
				foreach($result as &$r){

					if ( $r["setup"] == 0)	$r["setup"] = '';
					if ( $r["maq1"] == 0)	$r["maq1"] = '';
					 $r["setup"] =  (int)$r["setup"]; 
								
					//sumas totales
					$tp += $r["Cantidad"];
					$tm += $r["Minutos"];
					
					$tlp += $r["lun_prg"];
					if ( $r["lun_set"] == 1 ) 
						$r["lun_min"] =   $r["lun_min"] - 1 + $r["setup"] ;
					
					$tlm += $r["lun_min"];
					
					
					$tmp += $r["mar_prg"];
					if ( $r["mar_set"] == 1 ) 
						$r["mar_min"] =   $r["mar_min"] - 1 + $r["setup"] ;
					
					$tmm += $r["mar_min"];
						
					
					$tip += $r["mie_prg"];
					if ( $r["mie_set"] == 1 ) 
						$r["mie_min"] =   $r["mie_min"] - 1 + $r["setup"] ;
					$tim += $r["mie_min"];
					
					
					$tjp += $r["jue_prg"];
					if ( $r["jue_set"] == 1 ) 
						$r["jue_min"] =   $r["jue_min"] - 1 + $r["setup"] ;
					$tjm += $r["jue_min"];
					
					
					$tvp += $r["vie_prg"];
					if ( $r["vie_set"] == 1 ) 
						$r["vie_min"] =   $r["vie_min"] - 1 + $r["setup"] ;
					$tvm += $r["vie_min"];
					
					
					$tsp += $r["sab_prg"];
					if ( $r["sab_set"] == 1 ) 
						$r["sab_min"] =   $r["sab_min"] - 1 + $r["setup"] ;
					$tsm += $r["sab_min"];
					
					
					$tdp += $r["dom_prg"];
					if ( $r["dom_set"] == 1 ) 
						$r["dom_min"] =   $r["dom_min"] - 1 + $r["setup"] ;
					$tdm += $r["dom_min"];
					
					
					$tsum += $r["sum"];
					$tsum_min += $r["sum_min"];
					
					$r["p_t"] = number_format($r["p_t"]);
					
					
					// asunto de 0s para que no se deplieguen en grid
					if ($r['CTB'] ==  0) $r['CTB'] = ''; else $r['CTB'] = (int)$r['CTB']  ;
					if ($r['PLB'] ==  0) $r['PLB'] = ''; else $r['PLB'] = (int)$r['PLB']  ;
					if ($r['PMB'] ==  0) $r['PMB'] = ''; else $r['PMB'] = (int)$r['PMB']  ;
					if ($r['PTB'] ==  0) $r['PTB'] = ''; else $r['PTB'] = (int)$r['PTB']  ;
					if ($r['sum'] ==  0) $r['sum'] = ''; else $r['sum'] = (int)$r['sum']  ;
					if ($r['e0'] ==  0) $r['e0'] = ''; else $r['e0'] = (int)$r['e0']  ;
					if ($r['e1'] ==  0) $r['e1'] = ''; else $r['e1'] = (int)$r['e1']  ;
					if ($r['rest'] ==  0) $r['rest'] = ''; 
					if ($r['rest_min'] ==  0) $r['rest_min'] = ''; 
					if ($r['sum_min'] ==  0) $r['sum_min'] = ''; 
					
					//grupal
					

									if(  $r["Maquina"] != $m  ){
						
						
					
						array_push ($congrupo , [
						
						'Cantidad' => $gp,
						'Minutos' => $gm,
						'Maquina' => $m,
						'Pieza' => "Totales - ".$m,
						"lun_prg" => $glp == 0 ? '' : $glp ,
						"lun_min" => $glm == 0 ? '' : $glm ,
						"mar_prg" => $gmp == 0 ? '' : $gmp ,
						"mar_min" => $gmm == 0 ? '' : $gmm ,
						"mie_prg" => $gip == 0 ? '' : $gip ,
						"mie_min" => $gim == 0 ? '' : $gim ,
						"jue_prg" => $gjp == 0 ? '' : $gjp ,
						"jue_min" => $gjm == 0 ? '' : $gjm ,
						"vie_prg" => $gvp == 0 ? '' : $gvp ,
						"vie_min" => $gvm == 0 ? '' : $gvm  ,
						"sab_prg" => $gsp == 0 ? '' : $gsp ,
						"sab_min" => $gsm == 0 ? '' : $gsm ,
						"dom_prg" => $gdp == 0 ? '' : $gdp ,
						"dom_min" => $gdm == 0 ? '' : $gdm,
						"ordenGrupo" => 1
						]);
						$m = $r['Maquina'] ;
						$gp = 0;
						$gm = 0;
						$glp = 0;
						$glm = 0;
						$gmp = 0;
						$gmm = 0;
						$gip = 0;
						$gim = 0;
						$gjp = 0;
						$gjm = 0;
						$gvp = 0;
						$gvm = 0;
						$gsp = 0;
						$gsm = 0;
						$gdp = 0;
						$gdm = 0;
					}

						$gp += $r["Cantidad"];
						$gm += $r["Minutos"];
						$glp += $r["lun_prg"];
						$glm += $r["lun_min"];
						$gmp += $r["mar_prg"];
						$gmm += $r["mar_min"];
						$gip += $r["mie_prg"];
						$gim += $r["mie_min"];
						$gjp += $r["jue_prg"];
						$gjm += $r["jue_min"];
						$gvp += $r["vie_prg"];
						$gvm += $r["vie_min"];
						$gsp += $r["sab_prg"];
						$gsm += $r["sab_min"];
						$gdp += $r["dom_prg"];
						$gdm += $r["dom_min"];
						
	
					
					array_push($congrupo , $r);
					
				
					//conteo 
					$rows++;
					
					
				}
					array_push ($congrupo , [
						
						'Cantidad' => $gp,
						'Minutos' => $gm,
						'Maquina' => $m,
						'Pieza' => "Totales - ".$m,
						"lun_prg" => $glp == 0 ? '' : $glp ,
						"lun_min" => $glm == 0 ? '' : $glm ,
						"mar_prg" => $gmp == 0 ? '' : $gmp ,
						"mar_min" => $gmm == 0 ? '' : $gmm ,
						"mie_prg" => $gip == 0 ? '' : $gip ,
						"mie_min" => $gim == 0 ? '' : $gim ,
						"jue_prg" => $gjp == 0 ? '' : $gjp ,
						"jue_min" => $gjm == 0 ? '' : $gjm ,
						"vie_prg" => $gvp == 0 ? '' : $gvp ,
						"vie_min" => $gvm == 0 ? '' : $gvm  ,
						"sab_prg" => $gsp == 0 ? '' : $gsp ,
						"sab_min" => $gsm == 0 ? '' : $gsm ,
						"dom_prg" => $gdp == 0 ? '' : $gdp ,
						"dom_min" => $gdm == 0 ? '' : $gdm,
						"ordenGrupo" => 1
						]);
				
				
				$totales[0]['Minutos'] = $tm;
				

				$totales[0]['lun_min'] = $tlm == 0 ? '' : number_format($tlm) ;

				$totales[0]['mar_min'] = $tmm == 0 ? '' : number_format($tmm) ;

				$totales[0]['mie_min'] = $tim == 0 ? '' : number_format($tim) ;

				$totales[0]['jue_min'] = $tjm == 0 ? '' : number_format($tjm) ;

				$totales[0]['vie_min'] = $tvm == 0 ? '' : number_format($tvm) ;

				$totales[0]['sab_min'] = $tsm == 0 ? '' : number_format($tsm) ;

				$totales[0]['dom_min'] = $tdm == 0 ? '' : number_format($tdm) ;
				
				$totales[0]['sum_min'] = $tsum_min ;
				
				$totales[0]['Pieza'] = 'Totales Minutos:';
				

				$totales[1]['lun_min'] =  $tlm == 0 ? '' :number_format($tlm / 60);

				$totales[1]['mar_min'] =  $tmm == 0 ? '' :number_format($tmm / 60);

				$totales[1]['mie_min'] =  $tim == 0 ? '' :number_format($tim /60);

				$totales[1]['jue_min'] =  $tjm == 0 ? '' :number_format($tjm / 60);

				$totales[1]['vie_min'] = $tvm == 0 ? '' :number_format($tvm / 60);

				$totales[1]['sab_min'] = $tsm == 0 ? '' : number_format($tsm / 60) ;

				$totales[1]['dom_min'] =  $tdm == 0 ? '' :number_format($tdm / 60);
				
				$totales[1]['sum_min'] =  $tsum_min == 0 ? '' :number_format($tsum_min / 60);
				
				$totales[1]['Pieza'] = 'Totales horas:';
				
				/* // $totales[0]['lun_prg'] = $tlp;
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
				
				$totales[2]['Pieza'] = 'Totales turno T8:'; */
				
				

				$totales[2]['lun_min'] = $tlm == 0 ? '' :number_format(($tlm / 60)/9);

				$totales[2]['mar_min'] = $tmm == 0 ? '' :number_format(($tmm / 60)/9);

				$totales[2]['mie_min'] = $tim == 0 ? '' :number_format(($tim /60)/9);

				$totales[2]['jue_min'] = $tjm == 0 ? '' :number_format(($tjm / 60)/9);

				$totales[2]['vie_min'] = $tvm == 0 ? '' :number_format(($tvm / 60)/9);

				$totales[2]['sab_min'] = $tsm == 0 ? '' :number_format(($tsm / 60)/9) ;

				$totales[2]['dom_min'] = $tdm == 0 ? '' :number_format(($tdm / 60)/9);
				
				$totales[2]['sum_min'] = $tsum_min == 0 ? '' :number_format($tsum_min / 60)/9 ;
				
				$totales[2]['Pieza'] = 'Totales turno T9:';
				
				
				$totales[3]['Cantidad'] =  $tp == 0 ? '' :$tp;
				$totales[3]['lun_prg'] =  $tlp == 0 ? '' :$tlp;

				$totales[3]['mar_prg'] =  $tmp == 0 ? '' :$tmp;

				$totales[3]['mie_prg'] =  $tip == 0 ? '' :$tip;

				$totales[3]['jue_prg'] =  $tjp == 0 ? '' :$tjp;

				$totales[3]['vie_prg'] =  $tvp == 0 ? '' :$tvp;

				$totales[3]['sab_prg'] =  $tsp == 0 ? '' :$tsp;

				$totales[3]['dom_prg'] =  $tdp == 0 ? '' :$tdp;
				
				// $totales[0]['dom_min'] = $tdm;
				
				$totales[3]['sum'] = $tsum ;
				
				$totales[3]['Pieza'] = 'Totales Piezas:';
			}
			
		// $datos['rows'] = $result;
		$datos['rows'] = $congrupo;
		$datos['footer'] = $totales;
		$datos['total'] = $rows;
        //print_r($congrupo);
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
					
					// 'maquina' => $data['maquina'], 
					// 'dia' => $data['dia'],
					// 'turno' => $data['turno'],
					// 'minutos' => $data['minutos'],
					// 'op' => $data['operador']
					// traeOpSemanal($data,$dia,$turno,$multiple=0)
					 
					$data_rec2['maquina'] =$datarec['Maquina'];
					
					 
					if($data->{'lun_prg'} != '' && $data->{'lun_prg'} != 'n') {
						$datarec['fecha'] = $this->semana2fecha($a,$sem,'lun');
						$datarec['cantidad'] = $data->{'lun_prg'};
						$datarec['min'] = $data->{'lun_min'};
						$datarec['setup'] = $data->{'lun_set'};
						
						$this->save($datarec);
						
						$ops = $this->traeOpSemanal($datarec['Maquina'],$datarec['fecha'],'Matutino',1);
						
						 // $data_rec2['minutos'] = $ops['Minutos'];
						 $data_rec2['minutos'] = $datarec['min'];
						// echo "ops: ";print_r($ops);
						$data_rec2['dia'] = $datarec['fecha']; 
						$data_rec2['cantidad_prog'] = $datarec['cantidad'];
						
							$data_rec2['operador'] = $ops['Matutino'];
							$data_rec2['turno'] = 'Matutino';
							$this->save_opturno_p2( $data_rec2);

							$data_rec2['operador'] = $ops['Vespertino'];
							$data_rec2['turno'] = 'Vespertino';
							$this->save_opturno_p2( $data_rec2);
						
							$data_rec2['operador'] = $ops['Nocturno'];
							$data_rec2['turno'] = 'Nocturno';
							$this->save_opturno_p2( $data_rec2);
						
						
					}
					if($data->{'mar_prg'} != '' && $data->{'mar_prg'} != 'n') {
						$datarec['fecha'] = $this->semana2fecha($a,$sem,'mar');
						$datarec['cantidad'] = $data->{'mar_prg'};
						$datarec['min'] = $data->{'mar_min'};
						$datarec['setup'] = $data->{'mar_set'};
						$this->save($datarec);
						
						$ops = $this->traeOpSemanal($datarec['Maquina'],$datarec['fecha'],'Matutino',1);
						// echo "ops: ";print_r($ops);
						
						 $data_rec2['minutos'] = $datarec['min'];
						$data_rec2['dia'] = $datarec['fecha']; 
						$data_rec2['cantidad_prog'] = $datarec['cantidad'];
						
							$data_rec2['operador'] = $ops['Matutino'];
							$data_rec2['turno'] = 'Matutino';
							$this->save_opturno_p2( $data_rec2);

							$data_rec2['operador'] = $ops['Vespertino'];
							$data_rec2['turno'] = 'Vespertino';
							$this->save_opturno_p2( $data_rec2);
						
							$data_rec2['operador'] = $ops['Nocturno'];
							$data_rec2['turno'] = 'Nocturno';
							$this->save_opturno_p2( $data_rec2);
						
					}
					if($data->{'mie_prg'} != '' && $data->{'mie_prg'} != 'n') {
						$datarec['fecha'] = $this->semana2fecha($a,$sem,'mie');
						$datarec['cantidad'] = $data->{'mie_prg'};
						$datarec['min'] = $data->{'mie_min'};
						$datarec['setup'] = $data->{'mie_set'};
						$this->save($datarec);
						
						$ops = $this->traeOpSemanal($datarec['Maquina'],$datarec['fecha'],'Matutino',1);
						// echo "ops: ";print_r($ops);
						// $data_rec2['minutos'] = $ops['Minutos'];
						$data_rec2['minutos'] = $datarec['min'];
						$data_rec2['dia'] = $datarec['fecha']; 
						$data_rec2['cantidad_prog'] = $datarec['cantidad'];
						
							$data_rec2['operador'] = $ops['Matutino'];
							$data_rec2['turno'] = 'Matutino';
							$this->save_opturno_p2( $data_rec2);

							$data_rec2['operador'] = $ops['Vespertino'];
							$data_rec2['turno'] = 'Vespertino';
							$this->save_opturno_p2( $data_rec2);
						
							$data_rec2['operador'] = $ops['Nocturno'];
							$data_rec2['turno'] = 'Nocturno';
							$this->save_opturno_p2( $data_rec2);
						
					}
					if($data->{'jue_prg'} != '' && $data->{'jue_prg'} != 'n') {
						$datarec['fecha'] = $this->semana2fecha($a,$sem,'jue');
						$datarec['cantidad'] = $data->{'jue_prg'};
						$datarec['min'] = $data->{'jue_min'};
						$datarec['setup'] = $data->{'jue_set'};
						$this->save($datarec);
						
						$ops = $this->traeOpSemanal($datarec['Maquina'],$datarec['fecha'],'Matutino',1);
						
						// $data_rec2['minutos'] = $ops['Minutos'];
						 $data_rec2['minutos'] = $datarec['min'];
						// echo "ops: ";print_r($ops);
						$data_rec2['dia'] = $datarec['fecha']; 
						$data_rec2['cantidad_prog'] = $datarec['cantidad'];
						
							$data_rec2['operador'] = $ops['Matutino'];
							$data_rec2['turno'] = 'Matutino';
							$this->save_opturno_p2( $data_rec2);

							$data_rec2['operador'] = $ops['Vespertino'];
							$data_rec2['turno'] = 'Vespertino';
							$this->save_opturno_p2( $data_rec2);
						
							$data_rec2['operador'] = $ops['Nocturno'];
							$data_rec2['turno'] = 'Nocturno';
							$this->save_opturno_p2( $data_rec2);
						
						
					}
					if($data->{'vie_prg'} != '' && $data->{'vie_prg'} != 'n') {
						$datarec['fecha'] = $this->semana2fecha($a,$sem,'vie');
						$datarec['cantidad'] = $data->{'vie_prg'};
						$datarec['min'] = $data->{'vie_min'};
						$datarec['setup'] = $data->{'vie_set'};
						$this->save($datarec);
						
						$ops = $this->traeOpSemanal($datarec['Maquina'],$datarec['fecha'],'Matutino',1);
						
						// $data_rec2['minutos'] = $ops['Minutos'];
						 $data_rec2['minutos'] = $datarec['min'];
						// echo "ops: ";print_r($ops);
						$data_rec2['dia'] = $datarec['fecha']; 
						$data_rec2['cantidad_prog'] = $datarec['cantidad'];
						
							$data_rec2['operador'] = $ops['Matutino'];
							$data_rec2['turno'] = 'Matutino';
							$this->save_opturno_p2( $data_rec2);

							$data_rec2['operador'] = $ops['Vespertino'];
							$data_rec2['turno'] = 'Vespertino';
							$this->save_opturno_p2( $data_rec2);
						
							$data_rec2['operador'] = $ops['Nocturno'];
							$data_rec2['turno'] = 'Nocturno';
							$this->save_opturno_p2( $data_rec2);
						
						
					}
					if($data->{'sab_prg'} != '' && $data->{'sab_prg'} != 'n') {
						$datarec['fecha'] = $this->semana2fecha($a,$sem,'sab');
						$datarec['cantidad'] = $data->{'sab_prg'};
						$datarec['min'] = $data->{'sab_min'};
						$datarec['setup'] = $data->{'sab_set'};
						$this->save($datarec);
						
						$ops = $this->traeOpSemanal($datarec['Maquina'],$datarec['fecha'],'Matutino',1);
						// echo "ops: ";print_r($ops);
						// $data_rec2['minutos'] = $ops['Minutos'];
						 $data_rec2['minutos'] = $datarec['min'];
						
						$data_rec2['dia'] = $datarec['fecha']; 
						$data_rec2['cantidad_prog'] = $datarec['cantidad'];
						
							$data_rec2['operador'] = $ops['Matutino'];
							$data_rec2['turno'] = 'Matutino';
							$this->save_opturno_p2( $data_rec2);

							$data_rec2['operador'] = $ops['Vespertino'];
							$data_rec2['turno'] = 'Vespertino';
							$this->save_opturno_p2( $data_rec2);
						
							$data_rec2['operador'] = $ops['Nocturno'];
							$data_rec2['turno'] = 'Nocturno';
							$this->save_opturno_p2( $data_rec2);
						
					}
					if($data->{'dom_prg'} != '' && $data->{'dom_prg'} != 'n') {
						$datarec['fecha'] = $this->semana2fecha($a,$sem,'dom');
						$datarec['cantidad']= $data->{'dom_prg'};
						$datarec['min'] = $data->{'dom_min'};
						$datarec['setup'] = $data->{'dom_set'};
						$this->save($datarec);
						
						$ops = $this->traeOpSemanal($datarec['Maquina'],$datarec['fecha'],'Matutino',1);
						// echo "ops: ";print_r($ops);
						// $data_rec2['minutos'] = $ops['Minutos'];
						 $data_rec2['minutos'] = $datarec['min'];
						
						$data_rec2['dia'] = $datarec['fecha']; 
						$data_rec2['cantidad_prog'] = $datarec['cantidad'];
						
							$data_rec2['operador'] = $ops['Matutino'];
							$data_rec2['turno'] = 'Matutino';
							$this->save_opturno_p2( $data_rec2);

							$data_rec2['operador'] = $ops['Vespertino'];
							$data_rec2['turno'] = 'Vespertino';
							$this->save_opturno_p2( $data_rec2);
						
							$data_rec2['operador'] = $ops['Nocturno'];
							$data_rec2['turno'] = 'Nocturno';
							$this->save_opturno_p2( $data_rec2);
						
					}
					
			}
		
		}
   
		public function save($data) {
		$command = \Yii::$app->db_mysql;
		 // echo "salvar"; 
		 // print_r($data);
		if (!$this->exist($data['fecha'],$data['Pieza'],$data['op'] ) ){
			if ( $data['cantidad'] == 0) return ;
			
			$result =$command->createCommand()->insert('pdp_ctb_dia',[
									'maquina' => $data['Maquina'], 
									'semana' => $data['sem'],
									'dia' => $data['fecha'],
									'op' => $data['op'], 
									'pieza' => $data['Pieza'], 
									'cantidad' => $data['cantidad'],
									'min' => $data['min'],
									'setup' => $data['setup']
				])->execute();
			// ])->getRawSql();
			
		}else{
		  //echo ' existe se actualiza';
		 
			  if($data['cantidad'] == 0  ){
					
				$result =$command->createCommand()->delete('pdp_ctb_dia',[
														'dia' => $data['fecha'],
														'op' => $data['op'],
														'pieza' => $data['Pieza']
													])->execute();
												// ])->getRawSql();
												 // print_r($result);
										
				
					return true; //corta ejecucion y sale
				}
			  
			  $result =$command->createCommand()->update('pdp_ctb_dia',[
										'maquina' => $data['Maquina'], 
										'semana' => $data['sem'],
										'cantidad' => $data['cantidad'],
										'min' => $data['min'],
										'setup' => $data['setup']
										], 	[
										'dia' => $data['fecha'],
										'op' => $data['op'],
										'pieza' => $data['Pieza']
										]
									)->execute();
								// )->getRawSql();
			
									
		  }
			
		}
		
		public function exist($dia,$pieza,$op) {
			
				$command = \Yii::$app->db_mysql;
		
		$result =$command
					->createCommand("
					
					Select  count(Maquina) as m 
					from pdp_ctb_dia 
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
						from pdp_ctb_dia
						where dia = '$fecha' 
						GROUP BY maquina
						)as d
					LEFT JOIN  pdp_maquina_turno_diabr as m on  d.maquina = m.maquina and   m.dia =  '$fecha' and m.turno = 'Matutino'  
					LEFT JOIN  pdp_maquina_turno_diabr as v on  d.maquina = v.maquina and   v.dia =  '$fecha' and v.turno = 'Vespertino'  
					LEFT JOIN  pdp_maquina_turno_diabr as n on  d.maquina = n.maquina and   n.dia =  '$fecha' and n.turno = 'Nocturno'  
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
				$guardar['cantidad_prog'] = 1;
			
			
	
			if($data->{'Matutino'} != '' ) {
				$guardar['operador'] =  $data->{'Matutino'};
				
				$guardar['turno'] =  'Matutino';
				$this->save_opturno_p2($guardar);
			}

			if($data->{'Vespertino'} != '' ) {
				$guardar['operador'] =  $data->{'Vespertino'};
				
				$guardar['turno'] =  'Vespertino';
				$this->save_opturno_p2($guardar);
			}

			if($data->{'Nocturno'} != '' ) {
				$guardar['operador'] =  $data->{'Nocturno'};
					
				$guardar['turno'] =  'Nocturno';
				$this->save_opturno_p2($guardar);
			}
			
			
		}
		
		public function traeOpSemanal($maquina,$dia,$turno,$multiple=0){
			$maq = $maquina;
			echo "$maq -  $dia";
			$sql = "
			
			select Matutino,Vespertino,Nocturno,Minutos
			from pdp_maquina_turnosbr
			where
			maquina = '$maq' and
			semana =  DATEpart(week,'$dia')
			
			";
			
			$command = \Yii::$app->db_mysql;
			
			$result =$command->createCommand($sql)->queryAll();
		
			$op = 0;
			$opa = [
			'Matutino'   => '---', 
			'Vespertino' => '---',
			'Nocturno'   => '---'
			];
			
		
			
				if( $result[0]['Matutino'] != null ){ 
					$op = $result[0]['Matutino'];
					$opa['Matutino'] = $result[0]['Matutino'];
				}
				if( $result[0]['Vespertino'] != null ){  
					$op = $result[0]['Vespertino'];
					$opa['Vespertino'] = $result[0]['Vespertino'];
				}
				if( $result[0]['Nocturno'] != null ){ 
					$op = $result[0]['Nocturno'];
					$opa['Nocturno'] = $result[0]['Nocturno'];
				} 
			$opa['Minutos']= $result[0]['Minutos'];
			// print_r($opa);
			if ($multiple == 0)
					return $op;
			else
					return $opa;
		}
		
		public function exist_turno($dia,$maquina,$turno) {
			
				$command = \Yii::$app->db_mysql;
		
		$result =$command
					->createCommand("
					
					Select  count(Maquina) as m 
					from pdp_maquina_turno_diabr 
					where maquina ='$maquina'  and dia = '$dia' and turno = '$turno'
					
					")->queryAll();
					$tmp = $result[0]['m'];
		echo " existe ?  $tmp   \n ";
		return $result[0]['m'] >  0 ? true : false;
			
		}
		
		
		public function save_opturno_p2($data){
			
			 
			$command = \Yii::$app->db_mysql;
			// echo "         turno :              " ; print_r($data);
		
		// echo "save_opturno_p2";print_r($data);
			
		if (!$this->exist_turno($data['dia'],$data['maquina'],$data['turno'] ) ){
			if ( $data['turno'] == '---' || $data['operador'] == '---' ) return ;
			
			$result =$command->createCommand()->insert('pdp_maquina_turno_diabr',[
									'maquina' => $data['maquina'], 
									'dia' => $data['dia'],
									'turno' => $data['turno'],
									'minutos' => $data['minutos'],
									'op' => $data['operador']
				])->execute();
				// ])->getRawSql();
				// echo $result;
		}else{
		  //echo ' existe se actualiza';
			
			  if ( $data['operador'] == '---' ||  $data['operador'] == 0 ) {
					
				$result =$command->createCommand()->delete('pdp_maquina_turno_diabr',[
														'dia' => $data['dia'],
														'maquina' => $data['maquina'],
														'turno' => $data['turno'],
													])->execute();
													// ])->getRawSql();
													// echo $result;
										
				
					return true; //corta ejecucion y sale
				}
			  
				if ( $data['cantidad_prog'] == 0  ) {
					
				$result =$command->createCommand()->delete('pdp_maquina_turno_diabr',[
														'dia' => $data['dia'],
														'maquina' => $data['maquina'],
													])->execute();
													// ])->getRawSql();
													// echo $result;
										
				
					return true; //corta ejecucion y sale
				}
			  
			  $result =$command->createCommand()->update('pdp_maquina_turno_diabr',[
										'minutos' => $data['minutos'],
										'op' => $data['operador']
										], 	[
										'turno' => $data['turno'],
										'dia' => $data['dia'],
										'maquina' => $data['maquina']
										]
									)->execute();
								// )->getRawSql();
								// echo $result;
									
		  }
		  
			
		}
			
			
		
		
		/// operadores
		public function GetInfo_diaop($fecha){
			$sql = "
				
				select   d.op, e.NOMBRECOMPLETO ,d.maquina,D.turno,d.minutos
				from pdp_maquina_turno_diabr as d
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