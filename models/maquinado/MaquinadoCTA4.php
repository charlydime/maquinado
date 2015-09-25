<?php

namespace frontend\Models\Maquinado;
use Yii;
use yii\base\Model;

Class MaquinadoCTA4 extends Model {

    public function GetInfo($semana,$page,$row) {
          $tmp = explode('-',$semana);
		  $tmp_s = substr($tmp[1],1);
		$se1 =  $tmp_s +0;
		$se2 =  $tmp_s +1;
		$year = date ("Y");
		 $registros = $this->GetInfo_total($semana);
		 $page = $page * $row - $row;
		 $lun = $this->semana2fecha($tmp[0],$se1,'lun');
		 $mar = $this->semana2fecha($tmp[0],$se1,'mar');
		 $mie = $this->semana2fecha($tmp[0],$se1,'mie');
		 $jue = $this->semana2fecha($tmp[0],$se1,'jue');
		 $vie = $this->semana2fecha($tmp[0],$se1,'vie');
		 $sab = $this->semana2fecha($tmp[0],$se1,'sab');
		 $dom = $this->semana2fecha($tmp[0],$se1,'dom');
		// echo $semana;
		// echo $lun.' <br> ';
		// echo $mar.' <br>';
		// echo $mie.' <br>';
		// echo $jue.' <br>';
		// echo $vie.' <br>';
		// echo $sab.' <br>';
		// echo $dom.' <br>';
        $command = \Yii::$app->db_mysql;
        $result =$command->createCommand("
 				select 
				pdp_cta.Pieza,
				prod_dux.CAMPOUSUARIO5 as casting,
				prod_dux.DESCRIPCION as descripcion,
				pdp_cta.Prioridad,
				pdp_cta.Cantidad,
				pdp_cta.Maquina,
				pdp_cta.op,
				mp.Minutos as minmaq,
				round(480/ nullif(mp.Minutos,0),0) as p_t,
				mp.Minutos * pdp_cta.Cantidad as Minutos,
				isnull(alm.pla,0) as PLA1,
				isnull(alm.pla2,0) as PLA2,
				isnull(alm.pla,0)+isnull(alm.pla2,0) as PLA,
				isnull(alm.pma,0)+isnull(alm.pma2,0) as PMA,
				isnull(alm.cta,0)+isnull(alm.cta2,0) as CTA,
				alm.cta as CTA,
				alm.pta as PTA,
				dux1.cantidad as e0,
				dux2.cantidad as e1,
				mp.Minutos1Maquinado as setup,
				
				--lun.cantidad as lun_prg,
				--lun.min as lun_min,
				--lun.setup as lun_set,
				--ETE_lun.hechas as hechaslun,
				--ETE_lun.rechazadas as rechazadaslun,
				
				ctam.[1] as lun_prg,
				ctam.[2] as mar_prg,
				ctam.[3] as mie_prg,
				ctam.[4] as jue_prg,
				ctam.[5] as vie_prg,
				ctam.[6] as sab_prg,
				ctam.[7] as dom_prg,
				
				ctami.[1] as lun_min,
				ctami.[2] as mar_min,
				ctami.[3] as mie_min,
				ctami.[4] as jue_min,
				ctami.[5] as vie_min,
				ctami.[6] as sab_min,
				ctami.[7] as dom_min,
				
				ctas.[1] as lun_set,
				ctas.[2] as mar_set,
				ctas.[3] as mie_set,
				ctas.[4] as jue_set,
				ctas.[5] as vie_set,
				ctas.[6] as sab_set,
				ctas.[7] as dom_set,
				
				eteh.[1] as hechaslun,
				eteh.[2] as hechasmar,
				eteh.[3] as hechasmie,
				eteh.[4] as hechasjue,
				eteh.[5] as hechasvie,
				eteh.[6] as hechassab,
				eteh.[7] as hechasdom,
				
				eter.[1] as rechazadaslun,
				eter.[2] as rechazadasmar,
				eter.[3] as rechazadasmie,
				eter.[4] as rechazadasjue,
				eter.[5] as rechazadasvie,
				eter.[6] as rechazadassab,
				eter.[7] as rechazadasdom,
				
				isnull(ctam.[1],0)+
				isnull(ctam.[2],0)+
				isnull(ctam.[3],0)+
				isnull(ctam.[4],0)+
				isnull(ctam.[5],0)+
				isnull(ctam.[6],0)+
				isnull(ctam.[7],0)
				
				as sum,
				
				pdp_cta.Cantidad  -
				(
				isnull(ctam.[1],0)+
				isnull(ctam.[2],0)+
				isnull(ctam.[3],0)+
				isnull(ctam.[4],0)+
				isnull(ctam.[5],0)+
				isnull(ctam.[6],0)+
				isnull(ctam.[7],0)
				)
				as rest,
				
				isnull(ctami.[1],0)+
				isnull(ctami.[2],0)+
				isnull(ctami.[3],0)+
				isnull(ctami.[4],0)+
				isnull(ctami.[5],0)+
				isnull(ctami.[6],0)+
				isnull(ctami.[7],0)
				
				as sum_min,
				
				pdp_cta.Minutos  * pdp_cta.Cantidad -
				(
				isnull(ctami.[1],0)+
				isnull(ctami.[2],0)+
				isnull(ctami.[3],0)+
				isnull(ctami.[4],0)+
				isnull(ctami.[5],0)+
				isnull(ctami.[6],0)+
				isnull(ctami.[7],0)
				)
				as rest_min,
				
				isnull(ctas.[1],0)+
				isnull(ctas.[2],0)+
				isnull(ctas.[3],0)+
				isnull(ctas.[4],0)+
				isnull(ctas.[5],0)+
				isnull(ctas.[6],0)+
				isnull(ctas.[7],0)
				
				as maq1
				
				from pdp_cta 
				
				LEFT JOIN  producto as prod_dux on prod_dux.IDENTIFICACION = pdp_cta.Pieza

				LEFT JOIN(
						SELECT 
						 DuxSinc.dbo.ALMPROD.producto,min(PAROEN.doctoadicionalfecha) as fechaemb, max(CANTIDAD) as cantidad
						FROM DuxSinc.dbo.ALMPROD
						LEFT JOIN PAROEN on DuxSinc.dbo.ALMPROD.producto = PAROEN.PRODUCTO
						WHERE
						DATEpart( week,PAROEN.doctoadicionalfecha ) = $se1 and  datepart( year,PAROEN.doctoadicionalfecha) = $year
						-- and DuxSinc.dbo.ALMPROD.ALMACEN = 'CTA'
						GROUP BY DuxSinc.dbo.ALMPROD.producto
						
				) as dux1 on pdp_cta.Pieza = dux1.producto 
				
				LEFT JOIN(
						SELECT 
						 DuxSinc.dbo.ALMPROD.producto,min(PAROEN.doctoadicionalfecha) as fechaemb, max(CANTIDAD) as cantidad
						FROM DuxSinc.dbo.ALMPROD
						LEFT JOIN PAROEN on DuxSinc.dbo.ALMPROD.producto = PAROEN.PRODUCTO
						WHERE
						DATEpart( week,PAROEN.doctoadicionalfecha )= $se2 and  datepart( year,PAROEN.doctoadicionalfecha) = $year
						-- and DuxSinc.dbo.ALMPROD.ALMACEN = 'CTA'
						GROUP BY DuxSinc.dbo.ALMPROD.producto
						
				) as dux2 on pdp_cta.Pieza = dux2.producto 


				LEFT JOIN (
					
						select * from (
							select PRODUCTO,ALMACEN,EXISTENCIA From DuxSinc.dbo.almprod where 
							almacen in
							('CTA','CTA2','PTA','PLA','PLA2','PMA','PMA2')
						) as p
						PIVOT
						(
							sum(existencia)
								FOR almacen in ([CTA],[CTA2],[PTA],[PLA],[PLA2],[PMA],[PMA2])
						) as piv
				) alm on pdp_cta.Pieza  = alm.PRODUCTO
				
				LEFT JOIN(
				select * from (
						select cantidad,pieza,datepart(dw,dia)as dia,op,maquina from pdp_cta_dia where dia BETWEEN cast ( '$lun' as date )  and cast ( '$dom' as date) 
						) as p
						PIVOT
						(
						sum(cantidad)
								FOR dia in([1],[2],[3],[4],[5],[6],[7])
						) as piv
				) as  ctam 
				on  pdp_cta.Pieza = ctam.pieza 
				and pdp_cta.op = ctam.op 
				and pdp_cta.maquina = ctam.maquina 
				
				LEFT JOIN(
				select * from (
						select min,pieza,datepart(dw,dia)as dia,op,maquina from pdp_cta_dia where dia BETWEEN cast ( '$lun' as date )  and cast ( '$dom' as date) 
						) as p
						PIVOT
						(
						sum(min)
								FOR dia in([1],[2],[3],[4],[5],[6],[7])
						) as piv
				) as  ctami 
				on  pdp_cta.Pieza = ctami.pieza 
				and pdp_cta.op = ctami.op 
				and pdp_cta.maquina = ctami.maquina 
				
				LEFT JOIN(
				select * from (
						select setup,pieza,datepart(dw,dia)as dia,op,maquina from pdp_cta_dia where dia BETWEEN cast ( '$lun' as date )  and cast ( '$dom' as date) 
						) as p
						PIVOT
						(
						sum(setup)
								FOR dia in([1],[2],[3],[4],[5],[6],[7])
						) as piv
				) as  ctas 
				on  pdp_cta.Pieza = ctas.pieza 
				and pdp_cta.op = ctas.op 
				and pdp_cta.maquina = ctas.maquina 
				
				LEFT JOIN(
					select * from (
					select 
					Producto,
					[Num Operacion]   as OP, 
					--sum ([Piezas Maquinadas] )as hechas, 
					sum ( isnull( [Rechazo Fund] , 0) +  isnull( [Rechazo Maq] , 0 ) ) as rechazadas ,
					maquina as clave,
					datepart( w,fecha) as fecha 
					 from  ete2.dbo.[Detalle de ETE] as DE 
					left join ete2.dbo.ETE as e  on de.Consecutivo = e.Consecutivo
					LEFT JOIN pdp_maquina as m on e.idmaquina = m.id 	
					where
						fecha BETWEEN cast ( '$lun' as date )  and cast ( '$dom' as date) 
					GROUP BY
						Producto,
						fecha,
						[Num Operacion],
						maquina
					) p 
					PIVOT
					(
						sum(rechazadas)
								FOR fecha in ([1],[2],[3],[4],[5],[6],[7])
					) as piv
				) eter on 
				pdp_cta.Pieza = eter.producto 
				and pdp_cta.op = eter.op 
				and pdp_cta.maquina = eter.clave 
				
				LEFT JOIN(
					select * from (
					select 
					Producto,
					[Num Operacion]   as OP, 
					sum ([Piezas Maquinadas] )as hechas, 
					--sum ( isnull( [Rechazo Fund] , 0) +  isnull( [Rechazo Maq] , 0 ) ) as rechazadas ,
					maquina as clave,
					datepart( w,fecha) as fecha 
					 from  ete2.dbo.[Detalle de ETE] as DE 
					left join ete2.dbo.ETE as e  on de.Consecutivo = e.Consecutivo
					LEFT JOIN pdp_maquina as m on e.idmaquina = m.id 	
					where
						fecha BETWEEN cast ( '$lun' as date )  and cast ( '$dom' as date) 
					GROUP BY
						Producto,
						fecha,
						[Num Operacion],
						maquina
					) p 
					PIVOT
					(
						sum(hechas)
								FOR fecha in ([1],[2],[3],[4],[5],[6],[7])
					) as piv
				) eteh on 
				pdp_cta.Pieza = eteh.producto 
				and pdp_cta.op = eteh.op 
				and pdp_cta.maquina = eteh.clave 
				
				LEFT JOIN 
				pdp_maquina_pieza as mp  on  mp.Pieza = pdp_cta.Pieza and mp.Maquina = pdp_cta.Maquina and  mp.OP = pdp_cta.OP
				

				
				where semana = $se1
				-- and pdp_cta.Pieza = '100217642(BSK-13080)'
				
				order by Maquina
				offset $page rows fetch next $row rows only  
				
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
					if ($r['Minutos'] ==  0) $r['Minutos'] = ''; else $r['Minutos'] = (int)$r['Minutos']  ;
					if ($r['minmaq'] ==  0) $r['minmaq'] = ''; else $r['minmaq'] = (int)$r['minmaq']  ;
					if ($r['CTA'] ==  0) $r['CTA'] = ''; else $r['CTA'] = (int)$r['CTA']  ;
					if ($r['PLA'] ==  0) $r['PLA'] = ''; else $r['PLA'] = (int)$r['PLA']  ;
					if ($r['PLA1'] ==  0) $r['PLA1'] = ''; else $r['PLA1'] = (int)$r['PLA1']  ;
					if ($r['PLA2'] ==  0) $r['PLA2'] = ''; else $r['PLA2'] = (int)$r['PLA2']  ;
					if ($r['PMA'] ==  0) $r['PMA'] = ''; else $r['PMA'] = (int)$r['PMA']  ;
					if ($r['PTA'] ==  0) $r['PTA'] = ''; else $r['PTA'] = (int)$r['PTA']  ;
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
				
				$resumen = $this->GetInfo_resumen($semana);
				$tlm = $resumen['lun_min'];
				$tlp = $resumen['lun_prg'];
				$tmm = $resumen['mar_min'];
				$tmp = $resumen['mar_prg'];
				$tim = $resumen['mie_min'];
				$tip = $resumen['mie_prg'];
				$tjm = $resumen['jue_min'];
				$tjp = $resumen['jue_prg'];
				$tvm = $resumen['vie_min'];
				$tvp = $resumen['vie_prg'];
				$tsm = $resumen['sab_min'];
				$tsp = $resumen['sab_prg'];
				$tdm = $resumen['dom_min'];
				$tdp = $resumen['dom_prg'];
				
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
		// $datos['total'] = $rows;
		$datos['total'] = $registros;
        //print_r($congrupo);
          return $datos; 
        }   
		
		    public function GetInfo_total($semana) {
          $tmp = explode('-',$semana);
		  $tmp_s = substr($tmp[1],1);
		$se1 =  $tmp_s +0;
		$se2 =  $tmp_s +1;
		$year = date ("Y");
		
		
		 $lun = $this->semana2fecha($tmp[0],$se1,'lun');
		 $mar = $this->semana2fecha($tmp[0],$se1,'mar');
		 $mie = $this->semana2fecha($tmp[0],$se1,'mie');
		 $jue = $this->semana2fecha($tmp[0],$se1,'jue');
		 $vie = $this->semana2fecha($tmp[0],$se1,'vie');
		 $sab = $this->semana2fecha($tmp[0],$se1,'sab');
		 $dom = $this->semana2fecha($tmp[0],$se1,'dom');
		// echo $semana;
		// echo $lun.' <br> ';
		// echo $mar.' <br>';
		// echo $mie.' <br>';
		// echo $jue.' <br>';
		// echo $vie.' <br>';
		// echo $sab.' <br>';
		// echo $dom.' <br>';
        $command = \Yii::$app->db_mysql;
        $result =$command->createCommand("
 				select 
				count(pdp_cta.Pieza) as cuenta
				
				
				
				from pdp_cta 
				
				LEFT JOIN  producto as prod_dux on prod_dux.IDENTIFICACION = pdp_cta.Pieza

				LEFT JOIN(
						SELECT 
						 DuxSinc.dbo.ALMPROD.producto,min(PAROEN.doctoadicionalfecha) as fechaemb, max(CANTIDAD) as cantidad
						FROM DuxSinc.dbo.ALMPROD
						LEFT JOIN PAROEN on DuxSinc.dbo.ALMPROD.producto = PAROEN.PRODUCTO
						WHERE
						DATEpart( week,PAROEN.doctoadicionalfecha ) = $se1 and  datepart( year,PAROEN.doctoadicionalfecha) = $year
						-- and DuxSinc.dbo.ALMPROD.ALMACEN = 'CTA'
						GROUP BY DuxSinc.dbo.ALMPROD.producto
						
				) as dux1 on pdp_cta.Pieza = dux1.producto 
				
				LEFT JOIN(
						SELECT 
						 DuxSinc.dbo.ALMPROD.producto,min(PAROEN.doctoadicionalfecha) as fechaemb, max(CANTIDAD) as cantidad
						FROM DuxSinc.dbo.ALMPROD
						LEFT JOIN PAROEN on DuxSinc.dbo.ALMPROD.producto = PAROEN.PRODUCTO
						WHERE
						DATEpart( week,PAROEN.doctoadicionalfecha )= $se2 and  datepart( year,PAROEN.doctoadicionalfecha) = $year
						-- and DuxSinc.dbo.ALMPROD.ALMACEN = 'CTA'
						GROUP BY DuxSinc.dbo.ALMPROD.producto
						
				) as dux2 on pdp_cta.Pieza = dux2.producto 


				LEFT JOIN(
					SELECT   
						sum(DuxSinc.dbo.ALMPROD.EXISTENCIA) AS EXISTENCIA, DuxSinc.dbo.ALMPROD.producto
					FROM DuxSinc.dbo.ALMPROD
					WHERE 
					DuxSinc.dbo.ALMPROD.ALMACEN =   'CTA'
					GROUP BY DuxSinc.dbo.ALMPROD.producto
				) as almcta on pdp_cta.Pieza = almcta.producto
				
				LEFT JOIN(
					SELECT   
						sum(DuxSinc.dbo.ALMPROD.EXISTENCIA) AS EXISTENCIA, DuxSinc.dbo.ALMPROD.producto
					FROM DuxSinc.dbo.ALMPROD
					WHERE 
					DuxSinc.dbo.ALMPROD.ALMACEN =   'CTA2'
					GROUP BY DuxSinc.dbo.ALMPROD.producto
				) as almcta2 on pdp_cta.Pieza = almcta2.producto

				LEFT JOIN(
					SELECT   
						sum(DuxSinc.dbo.ALMPROD.EXISTENCIA) AS EXISTENCIA , DuxSinc.dbo.ALMPROD.producto
					FROM DuxSinc.dbo.ALMPROD
					WHERE 
					DuxSinc.dbo.ALMPROD.ALMACEN =   'PTA'
					GROUP BY DuxSinc.dbo.ALMPROD.producto
				) as almpta on pdp_cta.Pieza = almpta.producto

				LEFT JOIN(
					SELECT   
						sum(DuxSinc.dbo.ALMPROD.EXISTENCIA) AS EXISTENCIA , DuxSinc.dbo.ALMPROD.producto
					FROM DuxSinc.dbo.ALMPROD
					WHERE 
					DuxSinc.dbo.ALMPROD.ALMACEN =   'PLA'
					GROUP BY DuxSinc.dbo.ALMPROD.producto
				) as almpla on pdp_cta.Pieza = almpla.producto

				LEFT JOIN(
					SELECT   
						sum(DuxSinc.dbo.ALMPROD.EXISTENCIA) AS EXISTENCIA, DuxSinc.dbo.ALMPROD.producto
					FROM DuxSinc.dbo.ALMPROD
					WHERE 
					DuxSinc.dbo.ALMPROD.ALMACEN =   'PLA2'
					GROUP BY DuxSinc.dbo.ALMPROD.producto
				) as almpla2 on pdp_cta.Pieza = almpla2.producto
	
				LEFT JOIN(
					SELECT   
						sum(DuxSinc.dbo.ALMPROD.EXISTENCIA) AS EXISTENCIA, DuxSinc.dbo.ALMPROD.producto
					FROM DuxSinc.dbo.ALMPROD
					WHERE 
					DuxSinc.dbo.ALMPROD.ALMACEN =   'PMA'
					GROUP BY DuxSinc.dbo.ALMPROD.producto
				) as almpma on pdp_cta.Pieza = almpma.producto

				LEFT JOIN(
					SELECT   
						sum(DuxSinc.dbo.ALMPROD.EXISTENCIA) AS EXISTENCIA, DuxSinc.dbo.ALMPROD.producto
					FROM DuxSinc.dbo.ALMPROD
					WHERE 
					DuxSinc.dbo.ALMPROD.ALMACEN =   'PMA2'
					GROUP BY DuxSinc.dbo.ALMPROD.producto
				) as almpma2 on pdp_cta.Pieza = almpma2.producto
				
				LEFT JOIN(
					select cantidad,min,operador,pieza,dia,op,setup from pdp_cta_dia
				)as lun on pdp_cta.Pieza = lun.pieza and pdp_cta.op = lun.op and lun.dia = '$lun'
				
				LEFT JOIN(
					select cantidad,min,operador,pieza,dia,op,setup from pdp_cta_dia
				)as mar on pdp_cta.Pieza = mar.pieza and pdp_cta.op = mar.op and mar.dia = '$mar'
				
				LEFT JOIN(
					select cantidad,min,operador,pieza,dia,op,setup from pdp_cta_dia
				)as mie on pdp_cta.Pieza = mie.pieza and pdp_cta.op = mie.op and mie.dia = '$mie'
				
				LEFT JOIN(
					select cantidad,min,operador,pieza,dia,op,setup from pdp_cta_dia
				)as jue on pdp_cta.Pieza = jue.pieza and pdp_cta.op = jue.op and jue.dia = '$jue'
				
				LEFT JOIN(
					select cantidad,min,operador,pieza,dia,op,setup from pdp_cta_dia
				)as vie on pdp_cta.Pieza = vie.pieza and pdp_cta.op = vie.op and vie.dia = '$vie'
				
				LEFT JOIN(
					select cantidad,min,operador,pieza,dia,op,setup from pdp_cta_dia
				)as sab on pdp_cta.Pieza = sab.pieza and pdp_cta.op = sab.op and sab.dia = '$sab'
				
				LEFT JOIN(
					select cantidad,min,operador,pieza,dia,op,setup from pdp_cta_dia
				)as dom on pdp_cta.Pieza = dom.pieza and pdp_cta.op = dom.op and dom.dia = '$dom'
				
				LEFT JOIN 
				pdp_maquina_pieza as mp  on  mp.Pieza = pdp_cta.Pieza and mp.Maquina = pdp_cta.Maquina and  mp.OP = pdp_cta.OP
				
				LEFT JOIN(
				
					select 

					Producto,
					[Num Operacion]   as OP, 
					sum ([Piezas Maquinadas] )as hechas, 
					sum ( isnull( [Rechazo Fund] , 0) +  isnull( [Rechazo Maq] , 0 ) ) as rechazadas ,
					maquina as clave,
					fecha
					 from  ete2.dbo.[Detalle de ETE] as DE 
					left join ete2.dbo.ETE as e  on de.Consecutivo = e.Consecutivo
					LEFT JOIN pdp_maquina as m on e.idmaquina = m.id 	
					where
						fecha =	 cast ( '$lun' as datetime2)
					GROUP BY
						Producto,
						fecha,
						[Num Operacion],
						maquina
				
				) AS ETE_lun on 
					ETE_lun.producto = pdp_cta.Pieza and 
					ETE_lun.OP =	pdp_cta.op
					and ETE_lun.clave = pdp_cta.Maquina
				
				LEFT JOIN(
				
										select 

					Producto,
					[Num Operacion]   as OP, 
					sum ([Piezas Maquinadas] )as hechas, 
					sum ( isnull( [Rechazo Fund] , 0) +  isnull( [Rechazo Maq] , 0 ) ) as rechazadas ,
					maquina as clave,
					fecha
					 from  ete2.dbo.[Detalle de ETE] as DE 
					left join ete2.dbo.ETE as e  on de.Consecutivo = e.Consecutivo
					LEFT JOIN pdp_maquina as m on e.idmaquina = m.id 	
					where
						fecha =	 cast ( '$mar' as datetime2)
					GROUP BY
						Producto,
						fecha,
						[Num Operacion],
						maquina
				
				
				) AS ETE_mar on 
					ETE_mar.producto = pdp_cta.Pieza and 
					ETE_mar.OP =	pdp_cta.op
					and ETE_mar.clave = pdp_cta.Maquina
				
				LEFT JOIN(
				
										select 

					Producto,
					[Num Operacion]   as OP, 
					sum ([Piezas Maquinadas] )as hechas, 
					sum ( isnull( [Rechazo Fund] , 0) +  isnull( [Rechazo Maq] , 0 ) ) as rechazadas ,
					maquina as clave,
					fecha
					 from  ete2.dbo.[Detalle de ETE] as DE 
					left join ete2.dbo.ETE as e  on de.Consecutivo = e.Consecutivo
					LEFT JOIN pdp_maquina as m on e.idmaquina = m.id 	
					where
						fecha =	 cast ( '$mie' as datetime2)
					GROUP BY
						Producto,
						fecha,
						[Num Operacion],
						maquina
				
				) AS ETE_mie on 
					ETE_mie.producto = pdp_cta.Pieza and 
					ETE_mie.OP =	pdp_cta.op

					and ETE_mie.clave = pdp_cta.Maquina
				
				LEFT JOIN(
				
					select 

					Producto,
					[Num Operacion]   as OP, 
					sum ([Piezas Maquinadas] )as hechas, 
					sum ( isnull( [Rechazo Fund] , 0) +  isnull( [Rechazo Maq] , 0 ) ) as rechazadas ,
					maquina as clave,
					fecha
					 from  ete2.dbo.[Detalle de ETE] as DE 
					left join ete2.dbo.ETE as e  on de.Consecutivo = e.Consecutivo
					LEFT JOIN pdp_maquina as m on e.idmaquina = m.id 	
					where
						fecha =	 cast ( '$jue' as datetime2)
					GROUP BY
						Producto,
						fecha,
						[Num Operacion],
						maquina
				
				) AS ETE_jue on 
					ETE_jue.producto = pdp_cta.Pieza and 
					ETE_jue.OP =	pdp_cta.op
					and ETE_jue.clave = pdp_cta.Maquina
				
				LEFT JOIN(
				
					select 

					Producto,
					[Num Operacion]   as OP, 
					sum ([Piezas Maquinadas] )as hechas, 
					sum ( isnull( [Rechazo Fund] , 0) +  isnull( [Rechazo Maq] , 0 ) ) as rechazadas ,
					maquina as clave,
					fecha
					 from  ete2.dbo.[Detalle de ETE] as DE 
					left join ete2.dbo.ETE as e  on de.Consecutivo = e.Consecutivo
					LEFT JOIN pdp_maquina as m on e.idmaquina = m.id 	
					where
						fecha =	 cast ( '$vie' as datetime2)
					GROUP BY
						Producto,
						fecha,
						[Num Operacion],
						maquina
				
				) AS ETE_vie on 
					ETE_vie.producto = pdp_cta.Pieza and 
					ETE_vie.OP =	pdp_cta.op
					and ETE_vie.clave = pdp_cta.Maquina
				
				LEFT JOIN(
				
					select 

					Producto,
					[Num Operacion]   as OP, 
					sum ([Piezas Maquinadas] )as hechas, 
					sum ( isnull( [Rechazo Fund] , 0) +  isnull( [Rechazo Maq] , 0 ) ) as rechazadas ,
					maquina as clave,
					fecha
					 from  ete2.dbo.[Detalle de ETE] as DE 
					left join ete2.dbo.ETE as e  on de.Consecutivo = e.Consecutivo
					LEFT JOIN pdp_maquina as m on e.idmaquina = m.id 	
					where
						fecha =	 cast ( '$sab' as datetime2)
					GROUP BY
						Producto,
						fecha,
						[Num Operacion],
						maquina
				
				) AS ETE_sab on 
					ETE_sab.producto = pdp_cta.Pieza and 
					ETE_sab.OP =	pdp_cta.op
					and ETE_sab.clave = pdp_cta.Maquina
				
				LEFT JOIN(
				
					select 

					Producto,
					[Num Operacion]   as OP, 
					sum ([Piezas Maquinadas] )as hechas, 
					sum ( isnull( [Rechazo Fund] , 0) +  isnull( [Rechazo Maq] , 0 ) ) as rechazadas ,
					maquina as clave,
					fecha
					 from  ete2.dbo.[Detalle de ETE] as DE 
					left join ete2.dbo.ETE as e  on de.Consecutivo = e.Consecutivo
					LEFT JOIN pdp_maquina as m on e.idmaquina = m.id 	
					where
						fecha =	 cast ( '$dom' as datetime2)
					GROUP BY
						Producto,
						fecha,
						[Num Operacion],
						maquina
				
				) AS ETE_dom on 
					ETE_dom.producto = pdp_cta.Pieza and 
					ETE_dom.OP =	pdp_cta.op
					and ETE_dom.clave = pdp_cta.Maquina
				
				where semana = $se1
				-- and pdp_cta.Pieza = '100217642(BSK-13080)'
				
		
			
				
			")->queryAll();
			
			return $result[0]['cuenta']; 
			
			}
			
		    public function GetInfo_resumen($semana) {
          $tmp = explode('-',$semana);
		  $tmp_s = substr($tmp[1],1);
		$se1 =  $tmp_s +0;
		$se2 =  $tmp_s +1;
		$year = date ("Y");
		 $registros = $this->GetInfo_total($semana);

		 $lun = $this->semana2fecha($tmp[0],$se1,'lun');
		 $mar = $this->semana2fecha($tmp[0],$se1,'mar');
		 $mie = $this->semana2fecha($tmp[0],$se1,'mie');
		 $jue = $this->semana2fecha($tmp[0],$se1,'jue');
		 $vie = $this->semana2fecha($tmp[0],$se1,'vie');
		 $sab = $this->semana2fecha($tmp[0],$se1,'sab');
		 $dom = $this->semana2fecha($tmp[0],$se1,'dom');
		// echo $semana;
		// echo $lun.' <br> ';
		// echo $mar.' <br>';
		// echo $mie.' <br>';
		// echo $jue.' <br>';
		// echo $vie.' <br>';
		// echo $sab.' <br>';
		// echo $dom.' <br>';
        $command = \Yii::$app->db_mysql;
        $result =$command->createCommand("
 				select 
				
				sum(isnull(almpla.existencia,0)+isnull(almpla2.existencia,0) )as PLA,
				sum(isnull(almpma.existencia,0)+isnull(almpma2.existencia,0) )as PMA,
				--sum(isnull(almcta.existencia,0)+isnull(almcta2.existencia,0) )as CTA,
				sum(almcta.existencia )as CTA,
				sum(almpta.existencia )as PTA,
				
				
				sum(lun.cantidad )as lun_prg,
				sum(lun.min )as lun_min,
				
				sum(ETE_lun.hechas) as hechaslun,
				sum(ETE_lun.rechazadas) as rechazadaslun,
				
				sum(mar.cantidad) as mar_prg,
				sum(mar.min) as mar_min,
				
				sum(ETE_mar.hechas) as hechasmar,
				sum(ETE_mar.rechazadas) as rechazadasmar,
				
				sum(mie.cantidad) as mie_prg,
				sum(mie.min )as mie_min,
				
				sum(ETE_mie.hechas) as hechasmie,
				sum(ETE_mie.rechazadas) as rechazadasmie,
				
				sum(jue.cantidad ) as jue_prg,
				sum(jue.min ) as jue_min,
				
				sum(ETE_jue.hechas ) as hechasjue,
				sum(ETE_jue.rechazadas ) as rechazadasjue,
				
				sum(vie.cantidad ) as vie_prg,
				sum(vie.min ) as vie_min,
				
				sum(ETE_vie.hechas ) as hechasvie,
				sum(ETE_vie.rechazadas ) as rechazadasvie,
				
				sum(sab.cantidad ) as sab_prg,
				sum(sab.min ) as sab_min,
				
				sum(ETE_sab.hechas ) as hechassab,
				sum(ETE_sab.rechazadas) as rechazadassab,
			
				sum(dom.cantidad) as dom_prg,
				sum(dom.min ) as dom_min,
				
				sum(ETE_dom.hechas) as hechasdom,
				sum(ETE_dom.rechazadas) as rechazadasdom
				
				from pdp_cta 
				
				LEFT JOIN  producto as prod_dux on prod_dux.IDENTIFICACION = pdp_cta.Pieza

				LEFT JOIN(
						SELECT 
						 DuxSinc.dbo.ALMPROD.producto,min(PAROEN.doctoadicionalfecha) as fechaemb, max(CANTIDAD) as cantidad
						FROM DuxSinc.dbo.ALMPROD
						LEFT JOIN PAROEN on DuxSinc.dbo.ALMPROD.producto = PAROEN.PRODUCTO
						WHERE
						DATEpart( week,PAROEN.doctoadicionalfecha ) = $se1 and  datepart( year,PAROEN.doctoadicionalfecha) = $year
						-- and DuxSinc.dbo.ALMPROD.ALMACEN = 'CTA'
						GROUP BY DuxSinc.dbo.ALMPROD.producto
						
				) as dux1 on pdp_cta.Pieza = dux1.producto 
				
				LEFT JOIN(
						SELECT 
						 DuxSinc.dbo.ALMPROD.producto,min(PAROEN.doctoadicionalfecha) as fechaemb, max(CANTIDAD) as cantidad
						FROM DuxSinc.dbo.ALMPROD
						LEFT JOIN PAROEN on DuxSinc.dbo.ALMPROD.producto = PAROEN.PRODUCTO
						WHERE
						DATEpart( week,PAROEN.doctoadicionalfecha )= $se2 and  datepart( year,PAROEN.doctoadicionalfecha) = $year
						-- and DuxSinc.dbo.ALMPROD.ALMACEN = 'CTA'
						GROUP BY DuxSinc.dbo.ALMPROD.producto
						
				) as dux2 on pdp_cta.Pieza = dux2.producto 


				LEFT JOIN(
					SELECT   
						sum(DuxSinc.dbo.ALMPROD.EXISTENCIA) AS EXISTENCIA, DuxSinc.dbo.ALMPROD.producto
					FROM DuxSinc.dbo.ALMPROD
					WHERE 
					DuxSinc.dbo.ALMPROD.ALMACEN =   'CTA'
					GROUP BY DuxSinc.dbo.ALMPROD.producto
				) as almcta on pdp_cta.Pieza = almcta.producto
				
				LEFT JOIN(
					SELECT   
						sum(DuxSinc.dbo.ALMPROD.EXISTENCIA) AS EXISTENCIA, DuxSinc.dbo.ALMPROD.producto
					FROM DuxSinc.dbo.ALMPROD
					WHERE 
					DuxSinc.dbo.ALMPROD.ALMACEN =   'CTA2'
					GROUP BY DuxSinc.dbo.ALMPROD.producto
				) as almcta2 on pdp_cta.Pieza = almcta2.producto

				LEFT JOIN(
					SELECT   
						sum(DuxSinc.dbo.ALMPROD.EXISTENCIA) AS EXISTENCIA , DuxSinc.dbo.ALMPROD.producto
					FROM DuxSinc.dbo.ALMPROD
					WHERE 
					DuxSinc.dbo.ALMPROD.ALMACEN =   'PTA'
					GROUP BY DuxSinc.dbo.ALMPROD.producto
				) as almpta on pdp_cta.Pieza = almpta.producto

				LEFT JOIN(
					SELECT   
						sum(DuxSinc.dbo.ALMPROD.EXISTENCIA) AS EXISTENCIA , DuxSinc.dbo.ALMPROD.producto
					FROM DuxSinc.dbo.ALMPROD
					WHERE 
					DuxSinc.dbo.ALMPROD.ALMACEN =   'PLA'
					GROUP BY DuxSinc.dbo.ALMPROD.producto
				) as almpla on pdp_cta.Pieza = almpla.producto

				LEFT JOIN(
					SELECT   
						sum(DuxSinc.dbo.ALMPROD.EXISTENCIA) AS EXISTENCIA, DuxSinc.dbo.ALMPROD.producto
					FROM DuxSinc.dbo.ALMPROD
					WHERE 
					DuxSinc.dbo.ALMPROD.ALMACEN =   'PLA2'
					GROUP BY DuxSinc.dbo.ALMPROD.producto
				) as almpla2 on pdp_cta.Pieza = almpla2.producto
	
				LEFT JOIN(
					SELECT   
						sum(DuxSinc.dbo.ALMPROD.EXISTENCIA) AS EXISTENCIA, DuxSinc.dbo.ALMPROD.producto
					FROM DuxSinc.dbo.ALMPROD
					WHERE 
					DuxSinc.dbo.ALMPROD.ALMACEN =   'PMA'
					GROUP BY DuxSinc.dbo.ALMPROD.producto
				) as almpma on pdp_cta.Pieza = almpma.producto

				LEFT JOIN(
					SELECT   
						sum(DuxSinc.dbo.ALMPROD.EXISTENCIA) AS EXISTENCIA, DuxSinc.dbo.ALMPROD.producto
					FROM DuxSinc.dbo.ALMPROD
					WHERE 
					DuxSinc.dbo.ALMPROD.ALMACEN =   'PMA2'
					GROUP BY DuxSinc.dbo.ALMPROD.producto
				) as almpma2 on pdp_cta.Pieza = almpma2.producto
				
				LEFT JOIN(
					select cantidad,min,operador,pieza,dia,op,setup from pdp_cta_dia
				)as lun on pdp_cta.Pieza = lun.pieza and pdp_cta.op = lun.op and lun.dia = '$lun'
				
				LEFT JOIN(
					select cantidad,min,operador,pieza,dia,op,setup from pdp_cta_dia
				)as mar on pdp_cta.Pieza = mar.pieza and pdp_cta.op = mar.op and mar.dia = '$mar'
				
				LEFT JOIN(
					select cantidad,min,operador,pieza,dia,op,setup from pdp_cta_dia
				)as mie on pdp_cta.Pieza = mie.pieza and pdp_cta.op = mie.op and mie.dia = '$mie'
				
				LEFT JOIN(
					select cantidad,min,operador,pieza,dia,op,setup from pdp_cta_dia
				)as jue on pdp_cta.Pieza = jue.pieza and pdp_cta.op = jue.op and jue.dia = '$jue'
				
				LEFT JOIN(
					select cantidad,min,operador,pieza,dia,op,setup from pdp_cta_dia
				)as vie on pdp_cta.Pieza = vie.pieza and pdp_cta.op = vie.op and vie.dia = '$vie'
				
				LEFT JOIN(
					select cantidad,min,operador,pieza,dia,op,setup from pdp_cta_dia
				)as sab on pdp_cta.Pieza = sab.pieza and pdp_cta.op = sab.op and sab.dia = '$sab'
				
				LEFT JOIN(
					select cantidad,min,operador,pieza,dia,op,setup from pdp_cta_dia
				)as dom on pdp_cta.Pieza = dom.pieza and pdp_cta.op = dom.op and dom.dia = '$dom'
				
				LEFT JOIN 
				pdp_maquina_pieza as mp  on  mp.Pieza = pdp_cta.Pieza and mp.Maquina = pdp_cta.Maquina and  mp.OP = pdp_cta.OP
				
				LEFT JOIN(
				
					select 

					Producto,
					[Num Operacion]   as OP, 
					sum ([Piezas Maquinadas] )as hechas, 
					sum ( isnull( [Rechazo Fund] , 0) +  isnull( [Rechazo Maq] , 0 ) ) as rechazadas ,
					maquina as clave,
					fecha
					 from  ete2.dbo.[Detalle de ETE] as DE 
					left join ete2.dbo.ETE as e  on de.Consecutivo = e.Consecutivo
					LEFT JOIN pdp_maquina as m on e.idmaquina = m.id 	
					where
						fecha =	 cast ( '$lun' as datetime2)
					GROUP BY
						Producto,
						fecha,
						[Num Operacion],
						maquina
				
				) AS ETE_lun on 
					ETE_lun.producto = pdp_cta.Pieza and 
					ETE_lun.OP =	pdp_cta.op
					and ETE_lun.clave = pdp_cta.Maquina
				
				LEFT JOIN(
				
										select 

					Producto,
					[Num Operacion]   as OP, 
					sum ([Piezas Maquinadas] )as hechas, 
					sum ( isnull( [Rechazo Fund] , 0) +  isnull( [Rechazo Maq] , 0 ) ) as rechazadas ,
					maquina as clave,
					fecha
					 from  ete2.dbo.[Detalle de ETE] as DE 
					left join ete2.dbo.ETE as e  on de.Consecutivo = e.Consecutivo
					LEFT JOIN pdp_maquina as m on e.idmaquina = m.id 	
					where
						fecha =	 cast ( '$mar' as datetime2)
					GROUP BY
						Producto,
						fecha,
						[Num Operacion],
						maquina
				
				
				) AS ETE_mar on 
					ETE_mar.producto = pdp_cta.Pieza and 
					ETE_mar.OP =	pdp_cta.op
					and ETE_mar.clave = pdp_cta.Maquina
				
				LEFT JOIN(
				
										select 

					Producto,
					[Num Operacion]   as OP, 
					sum ([Piezas Maquinadas] )as hechas, 
					sum ( isnull( [Rechazo Fund] , 0) +  isnull( [Rechazo Maq] , 0 ) ) as rechazadas ,
					maquina as clave,
					fecha
					 from  ete2.dbo.[Detalle de ETE] as DE 
					left join ete2.dbo.ETE as e  on de.Consecutivo = e.Consecutivo
					LEFT JOIN pdp_maquina as m on e.idmaquina = m.id 	
					where
						fecha =	 cast ( '$mie' as datetime2)
					GROUP BY
						Producto,
						fecha,
						[Num Operacion],
						maquina
				
				) AS ETE_mie on 
					ETE_mie.producto = pdp_cta.Pieza and 
					ETE_mie.OP =	pdp_cta.op

					and ETE_mie.clave = pdp_cta.Maquina
				
				LEFT JOIN(
				
					select 

					Producto,
					[Num Operacion]   as OP, 
					sum ([Piezas Maquinadas] )as hechas, 
					sum ( isnull( [Rechazo Fund] , 0) +  isnull( [Rechazo Maq] , 0 ) ) as rechazadas ,
					maquina as clave,
					fecha
					 from  ete2.dbo.[Detalle de ETE] as DE 
					left join ete2.dbo.ETE as e  on de.Consecutivo = e.Consecutivo
					LEFT JOIN pdp_maquina as m on e.idmaquina = m.id 	
					where
						fecha =	 cast ( '$jue' as datetime2)
					GROUP BY
						Producto,
						fecha,
						[Num Operacion],
						maquina
				
				) AS ETE_jue on 
					ETE_jue.producto = pdp_cta.Pieza and 
					ETE_jue.OP =	pdp_cta.op
					and ETE_jue.clave = pdp_cta.Maquina
				
				LEFT JOIN(
				
					select 

					Producto,
					[Num Operacion]   as OP, 
					sum ([Piezas Maquinadas] )as hechas, 
					sum ( isnull( [Rechazo Fund] , 0) +  isnull( [Rechazo Maq] , 0 ) ) as rechazadas ,
					maquina as clave,
					fecha
					 from  ete2.dbo.[Detalle de ETE] as DE 
					left join ete2.dbo.ETE as e  on de.Consecutivo = e.Consecutivo
					LEFT JOIN pdp_maquina as m on e.idmaquina = m.id 	
					where
						fecha =	 cast ( '$vie' as datetime2)
					GROUP BY
						Producto,
						fecha,
						[Num Operacion],
						maquina
				
				) AS ETE_vie on 
					ETE_vie.producto = pdp_cta.Pieza and 
					ETE_vie.OP =	pdp_cta.op
					and ETE_vie.clave = pdp_cta.Maquina
				
				LEFT JOIN(
				
					select 

					Producto,
					[Num Operacion]   as OP, 
					sum ([Piezas Maquinadas] )as hechas, 
					sum ( isnull( [Rechazo Fund] , 0) +  isnull( [Rechazo Maq] , 0 ) ) as rechazadas ,
					maquina as clave,
					fecha
					 from  ete2.dbo.[Detalle de ETE] as DE 
					left join ete2.dbo.ETE as e  on de.Consecutivo = e.Consecutivo
					LEFT JOIN pdp_maquina as m on e.idmaquina = m.id 	
					where
						fecha =	 cast ( '$sab' as datetime2)
					GROUP BY
						Producto,
						fecha,
						[Num Operacion],
						maquina
				
				) AS ETE_sab on 
					ETE_sab.producto = pdp_cta.Pieza and 
					ETE_sab.OP =	pdp_cta.op
					and ETE_sab.clave = pdp_cta.Maquina
				
				LEFT JOIN(
				
					select 

					Producto,
					[Num Operacion]   as OP, 
					sum ([Piezas Maquinadas] )as hechas, 
					sum ( isnull( [Rechazo Fund] , 0) +  isnull( [Rechazo Maq] , 0 ) ) as rechazadas ,
					maquina as clave,
					fecha
					 from  ete2.dbo.[Detalle de ETE] as DE 
					left join ete2.dbo.ETE as e  on de.Consecutivo = e.Consecutivo
					LEFT JOIN pdp_maquina as m on e.idmaquina = m.id 	
					where
						fecha =	 cast ( '$dom' as datetime2)
					GROUP BY
						Producto,
						fecha,
						[Num Operacion],
						maquina
				
				) AS ETE_dom on 
					ETE_dom.producto = pdp_cta.Pieza and 
					ETE_dom.OP =	pdp_cta.op
					and ETE_dom.clave = pdp_cta.Maquina
				
				where semana = $se1
				-- and pdp_cta.Pieza = '100217642(BSK-13080)'
				
			
				
			")->queryAll();
			
			return $result[0];
			
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
			
			
			
			// echo "datos save_dia"; print_r($datos); exit; 
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
						$opd = $this->traeOpDia($datarec['Maquina'],$datarec['fecha'],'Matutino',1);
						
						 // $data_rec2['minutos'] = $ops['Minutos'];
						 $data_rec2['minutos'] = $datarec['min'];
						// echo "ops: ";print_r($ops);
						$data_rec2['dia'] = $datarec['fecha']; 
						$data_rec2['cantidad_prog'] = $datarec['cantidad'];
						
							$data_rec2['operador'] =  $opd['Matutino'] == '---' ? $ops['Matutino'] :$opd['Matutino'];
							$data_rec2['turno'] = 'Matutino';
							$this->save_opturno_p2( $data_rec2);

							$data_rec2['operador'] = $opd['Vespertino'] == '---' ? $ops['Vespertino'] : $opd['Vespertino'] ;
							$data_rec2['turno'] = 'Vespertino';
							$this->save_opturno_p2( $data_rec2);
						
							$data_rec2['operador'] = $opd['Nocturno'] == '---'  ? $ops['Nocturno'] : $opd['Nocturno'];
							$data_rec2['turno'] = 'Nocturno';
							$this->save_opturno_p2( $data_rec2);
							
							$data_rec2['operador'] = $opd['Mixto'] == '---' ? $ops['Mixto'] : $opd['Mixto'];
							$data_rec2['turno'] = 'Mixto';
							$this->save_opturno_p2( $data_rec2);
						
						
					}
					if($data->{'mar_prg'} != '' && $data->{'mar_prg'} != 'n') {
						$datarec['fecha'] = $this->semana2fecha($a,$sem,'mar');
						$datarec['cantidad'] = $data->{'mar_prg'};
						$datarec['min'] = $data->{'mar_min'};
						$datarec['setup'] = $data->{'mar_set'};
						$this->save($datarec);
						
						$ops = $this->traeOpSemanal($datarec['Maquina'],$datarec['fecha'],'Matutino',1);
						$opd = $this->traeOpDia($datarec['Maquina'],$datarec['fecha'],'Matutino',1);
						// echo "savedia opd"; print_r($opd);
						// echo "savedia ops"; print_r($ops);
						 // $data_rec2['minutos'] = $ops['Minutos'];
						 $data_rec2['minutos'] = $datarec['min'];
						// echo "ops: ";print_r($ops);
						$data_rec2['dia'] = $datarec['fecha']; 
						$data_rec2['cantidad_prog'] = $datarec['cantidad'];
						
							$data_rec2['operador'] =  $opd['Matutino'] == '---' ? $ops['Matutino'] :$opd['Matutino'];
							$data_rec2['turno'] = 'Matutino';
							$this->save_opturno_p2( $data_rec2);

							$data_rec2['operador'] = $opd['Vespertino'] == '---' ? $ops['Vespertino'] : $opd['Vespertino'] ;
							$data_rec2['turno'] = 'Vespertino';
							$this->save_opturno_p2( $data_rec2);
						
							$data_rec2['operador'] = $opd['Nocturno'] == '---'  ? $ops['Nocturno'] : $opd['Nocturno'];
							$data_rec2['turno'] = 'Nocturno';
							$this->save_opturno_p2( $data_rec2);
							
							$data_rec2['operador'] = $opd['Mixto'] == '---' ? $ops['Mixto'] : $opd['Mixto'];
							$data_rec2['turno'] = 'Mixto';
							$this->save_opturno_p2( $data_rec2);
						
					}
					if($data->{'mie_prg'} != '' && $data->{'mie_prg'} != 'n') {
						$datarec['fecha'] = $this->semana2fecha($a,$sem,'mie');
						$datarec['cantidad'] = $data->{'mie_prg'};
						$datarec['min'] = $data->{'mie_min'};
						$datarec['setup'] = $data->{'mie_set'};
						$this->save($datarec);
						
						$ops = $this->traeOpSemanal($datarec['Maquina'],$datarec['fecha'],'Matutino',1);
						$opd = $this->traeOpDia($datarec['Maquina'],$datarec['fecha'],'Matutino',1);
						
						 // $data_rec2['minutos'] = $ops['Minutos'];
						 $data_rec2['minutos'] = $datarec['min'];
						// echo "ops: ";print_r($ops);
						$data_rec2['dia'] = $datarec['fecha']; 
						$data_rec2['cantidad_prog'] = $datarec['cantidad'];
						
							$data_rec2['operador'] =  $opd['Matutino'] == '---' ? $ops['Matutino'] :$opd['Matutino'];
							$data_rec2['turno'] = 'Matutino';
							$this->save_opturno_p2( $data_rec2);

							$data_rec2['operador'] = $opd['Vespertino'] == '---' ? $ops['Vespertino'] : $opd['Vespertino'] ;
							$data_rec2['turno'] = 'Vespertino';
							$this->save_opturno_p2( $data_rec2);
						
							$data_rec2['operador'] = $opd['Nocturno'] == '---'  ? $ops['Nocturno'] : $opd['Nocturno'];
							$data_rec2['turno'] = 'Nocturno';
							$this->save_opturno_p2( $data_rec2);
							
							$data_rec2['operador'] = $opd['Mixto'] == '---' ? $ops['Mixto'] : $opd['Mixto'];
							$data_rec2['turno'] = 'Mixto';
							$this->save_opturno_p2( $data_rec2);
						
					}
					if($data->{'jue_prg'} != '' && $data->{'jue_prg'} != 'n') {
						$datarec['fecha'] = $this->semana2fecha($a,$sem,'jue');
						$datarec['cantidad'] = $data->{'jue_prg'};
						$datarec['min'] = $data->{'jue_min'};
						$datarec['setup'] = $data->{'jue_set'};
						$this->save($datarec);
						
						$ops = $this->traeOpSemanal($datarec['Maquina'],$datarec['fecha'],'Matutino',1);
						$opd = $this->traeOpDia($datarec['Maquina'],$datarec['fecha'],'Matutino',1);
						
						 // $data_rec2['minutos'] = $ops['Minutos'];
						 $data_rec2['minutos'] = $datarec['min'];
						// echo "ops: ";print_r($ops);
						$data_rec2['dia'] = $datarec['fecha']; 
						$data_rec2['cantidad_prog'] = $datarec['cantidad'];
						
							$data_rec2['operador'] =  $opd['Matutino'] == '---' ? $ops['Matutino'] :$opd['Matutino'];
							$data_rec2['turno'] = 'Matutino';
							$this->save_opturno_p2( $data_rec2);

							$data_rec2['operador'] = $opd['Vespertino'] == '---' ? $ops['Vespertino'] : $opd['Vespertino'] ;
							$data_rec2['turno'] = 'Vespertino';
							$this->save_opturno_p2( $data_rec2);
						
							$data_rec2['operador'] = $opd['Nocturno'] == '---'  ? $ops['Nocturno'] : $opd['Nocturno'];
							$data_rec2['turno'] = 'Nocturno';
							$this->save_opturno_p2( $data_rec2);
							
							$data_rec2['operador'] = $opd['Mixto'] == '---' ? $ops['Mixto'] : $opd['Mixto'];
							$data_rec2['turno'] = 'Mixto';
							$this->save_opturno_p2( $data_rec2);
						
					}
					if($data->{'vie_prg'} != '' && $data->{'vie_prg'} != 'n') {
						$datarec['fecha'] = $this->semana2fecha($a,$sem,'vie');
						$datarec['cantidad'] = $data->{'vie_prg'};
						$datarec['min'] = $data->{'vie_min'};
						$datarec['setup'] = $data->{'vie_set'};
						$this->save($datarec);
						
						$ops = $this->traeOpSemanal($datarec['Maquina'],$datarec['fecha'],'Matutino',1);
						$opd = $this->traeOpDia($datarec['Maquina'],$datarec['fecha'],'Matutino',1);
						
						 // $data_rec2['minutos'] = $ops['Minutos'];
						 $data_rec2['minutos'] = $datarec['min'];
						// echo "ops: ";print_r($ops);
						$data_rec2['dia'] = $datarec['fecha']; 
						$data_rec2['cantidad_prog'] = $datarec['cantidad'];
						
							$data_rec2['operador'] =  $opd['Matutino'] == '---' ? $ops['Matutino'] :$opd['Matutino'];
							$data_rec2['turno'] = 'Matutino';
							$this->save_opturno_p2( $data_rec2);

							$data_rec2['operador'] = $opd['Vespertino'] == '---' ? $ops['Vespertino'] : $opd['Vespertino'] ;
							$data_rec2['turno'] = 'Vespertino';
							$this->save_opturno_p2( $data_rec2);
						
							$data_rec2['operador'] = $opd['Nocturno'] == '---'  ? $ops['Nocturno'] : $opd['Nocturno'];
							$data_rec2['turno'] = 'Nocturno';
							$this->save_opturno_p2( $data_rec2);
							
							$data_rec2['operador'] = $opd['Mixto'] == '---' ? $ops['Mixto'] : $opd['Mixto'];
							$data_rec2['turno'] = 'Mixto';
							$this->save_opturno_p2( $data_rec2);
						
						
					}
					if($data->{'sab_prg'} != '' && $data->{'sab_prg'} != 'n') {
						$datarec['fecha'] = $this->semana2fecha($a,$sem,'sab');
						$datarec['cantidad'] = $data->{'sab_prg'};
						$datarec['min'] = $data->{'sab_min'};
						$datarec['setup'] = $data->{'sab_set'};
						$this->save($datarec);
						
						$ops = $this->traeOpSemanal($datarec['Maquina'],$datarec['fecha'],'Matutino',1);
						$opd = $this->traeOpDia($datarec['Maquina'],$datarec['fecha'],'Matutino',1);
						
						 // $data_rec2['minutos'] = $ops['Minutos'];
						 $data_rec2['minutos'] = $datarec['min'];
						// echo "ops: ";print_r($ops);
						$data_rec2['dia'] = $datarec['fecha']; 
						$data_rec2['cantidad_prog'] = $datarec['cantidad'];
						
							$data_rec2['operador'] =  $opd['Matutino'] == '---' ? $ops['Matutino'] :$opd['Matutino'];
							$data_rec2['turno'] = 'Matutino';
							$this->save_opturno_p2( $data_rec2);

							$data_rec2['operador'] = $opd['Vespertino'] == '---' ? $ops['Vespertino'] : $opd['Vespertino'] ;
							$data_rec2['turno'] = 'Vespertino';
							$this->save_opturno_p2( $data_rec2);
						
							$data_rec2['operador'] = $opd['Nocturno'] == '---'  ? $ops['Nocturno'] : $opd['Nocturno'];
							$data_rec2['turno'] = 'Nocturno';
							$this->save_opturno_p2( $data_rec2);
							
							$data_rec2['operador'] = $opd['Mixto'] == '---' ? $ops['Mixto'] : $opd['Mixto'];
							$data_rec2['turno'] = 'Mixto';
							$this->save_opturno_p2( $data_rec2);
						
					}
					if($data->{'dom_prg'} != '' && $data->{'dom_prg'} != 'n') {
						$datarec['fecha'] = $this->semana2fecha($a,$sem,'dom');
						$datarec['cantidad']= $data->{'dom_prg'};
						$datarec['min'] = $data->{'dom_min'};
						$datarec['setup'] = $data->{'dom_set'};
						$this->save($datarec);
						
						$ops = $this->traeOpSemanal($datarec['Maquina'],$datarec['fecha'],'Matutino',1);
						$opd = $this->traeOpDia($datarec['Maquina'],$datarec['fecha'],'Matutino',1);
						
						 // $data_rec2['minutos'] = $ops['Minutos'];
						 $data_rec2['minutos'] = $datarec['min'];
						// echo "ops: ";print_r($ops);
						$data_rec2['dia'] = $datarec['fecha']; 
						$data_rec2['cantidad_prog'] = $datarec['cantidad'];
						
							$data_rec2['operador'] =  $opd['Matutino'] == '---' ? $ops['Matutino'] :$opd['Matutino'];
							$data_rec2['turno'] = 'Matutino';
							$this->save_opturno_p2( $data_rec2);

							$data_rec2['operador'] = $opd['Vespertino'] == '---' ? $ops['Vespertino'] : $opd['Vespertino'] ;
							$data_rec2['turno'] = 'Vespertino';
							$this->save_opturno_p2( $data_rec2);
						
							$data_rec2['operador'] = $opd['Nocturno'] == '---'  ? $ops['Nocturno'] : $opd['Nocturno'];
							$data_rec2['turno'] = 'Nocturno';
							$this->save_opturno_p2( $data_rec2);
							
							$data_rec2['operador'] = $opd['Mixto'] == '---' ? $ops['Mixto'] : $opd['Mixto'];
							$data_rec2['turno'] = 'Mixto';
							$this->save_opturno_p2( $data_rec2);
						
					}
					
			}
		
		}
   
		public function save($data) {
		$command = \Yii::$app->db_mysql;
		 echo "salvar"; 
		 print_r($data);
		 
		 	$usr = Yii::$app->user->identity; 
					$u  =$usr->role;
		 
		if (!$this->exist($data['fecha'],$data['Pieza'],$data['op'],$data['Maquina'] ) ){
			if ( $data['cantidad'] == 0) return ;
			if($u < 20) return false;
			$result =$command->createCommand()->insert('pdp_cta_dia',[
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
					if($u < 20) return false;
				$result =$command->createCommand()->delete('pdp_cta_dia',[
														'dia' => $data['fecha'],
														'op' => $data['op'],
														'pieza' => $data['Pieza'],
														'maquina' => $data['Maquina']
													])->execute();
												// ])->getRawSql();
												 // print_r($result);
										
				
					return true; //corta ejecucion y sale
				}
			  if($u < 20) return false;
			  $result =$command->createCommand()->update('pdp_cta_dia',[
										'maquina' => $data['Maquina'], 
										'semana' => $data['sem'],
										'cantidad' => $data['cantidad'],
										'min' => $data['min'],
										'setup' => $data['setup']
										], 	[
										'dia' => $data['fecha'],
										'op' => $data['op'],
										'pieza' => $data['Pieza'],
										'maquina' => $data['Maquina']
										]
									)->execute();
								// )->getRawSql();
								// print_r($result);
									
		  }
			
		}
		
		public function exist($dia,$pieza,$op,$maquina) {
			
				$command = \Yii::$app->db_mysql;
		
		$result =$command
					->createCommand("
					
					Select  count(Maquina) as m 
					from pdp_cta_dia 
					where 
					pieza ='$pieza'  
					and dia = '$dia'
					and op = '$op'
					and maquina = '$maquina'
					
					")->queryAll();
					
		
		return $result[0]['m'] >  0 ? true : false;
			
		}
        
		
		// diario
		public function GetInfo_op($fecha){
			$sql = "
				select d.*, 
					m.op as Matutino,
					v.op as Vespertino,
					n.op as Nocturno,
					x.op as Mixto
					 from 
						(select maquina,
										sum(min) as min,
										sum(min/60) as min_hrs,
										sum( (min/60)/9 ) as min_t9 
						from pdp_cta_dia 
						where dia = '$fecha' 
						GROUP BY maquina
						)as d
					LEFT JOIN  pdp_maquina_turno_dia as m on  d.maquina = m.maquina and   m.dia =  '$fecha' and m.turno = 'Matutino'  
					LEFT JOIN  pdp_maquina_turno_dia as v on  d.maquina = v.maquina and   v.dia =  '$fecha' and v.turno = 'Vespertino'  
					LEFT JOIN  pdp_maquina_turno_dia as n on  d.maquina = n.maquina and   n.dia =  '$fecha' and n.turno = 'Nocturno'  
					LEFT JOIN  pdp_maquina_turno_dia as x on  d.maquina = x.maquina and   x.dia =  '$fecha' and x.turno = 'Mixto'  
			";
			
			$command = \Yii::$app->db_mysql;
			$result =$command->createCommand($sql)->queryAll();
								// )->getRawSql();
			
			
			return $result;
		}
		
		
		public function calMaquinadia($maquina,$dia){
		
		$command = \Yii::$app->db_mysql;
		
		$sql = 
		"
		select sum(ct.cantidad*mp.Minutos)  as min 
		from pdp_cta_dia as ct 
		LEFT JOIN pdp_maquina_pieza as mp on mp.Pieza = ct.Pieza and mp.Maquina = ct.Maquina and ct.op = mp.OP
		where ct.maquina = '$maquina' and ct.dia = '$dia' 
			";
		
		$result =$command
					->createCommand($sql)
					->queryAll();
		
		// regresa mayor a 0 si existe			
		return $result[0]['min'];
		
	}
		
		public function save_opturno_p1($data,$dia){
			
			

			
			
			$guardar['maquina'] = $data->{'maquina'};
			$guardar['dia']     = $dia;
			
			$min = $this->calMaquinadia($guardar['maquina'],$guardar['dia']);
			$guardar['minutos'] = $min;
			// $guardar['minutos'] = $data->{'min'};
			
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
			
			if($data->{'Mixto'} != '' ) {
				$guardar['operador'] =  $data->{'Mixto'};
					
				$guardar['turno'] =  'Mixto';
				$this->save_opturno_p2($guardar);
			}
			
			
		}
		public function traeOpDia($maquina,$dia,$turno,$multiple=1){
			$maq = $maquina;
			echo "$maq -  $dia";
			$sql = "
			
			select d.*, 
					m.op as Matutino,
					v.op as Vespertino,
					n.op as Nocturno,
					x.op as Mixto
					
					 from 
						(select maquina,
										sum(min) as Minutos,
										dia
						from pdp_cta_dia 
						where dia = '$dia' 
						GROUP BY maquina,dia
						)as d
					LEFT JOIN  pdp_maquina_turno_dia as m on  d.maquina = m.maquina and   m.dia =  d.dia and m.turno = 'Matutino'  
					LEFT JOIN  pdp_maquina_turno_dia as v on  d.maquina = v.maquina and   v.dia =  d.dia and v.turno = 'Vespertino'  
					LEFT JOIN  pdp_maquina_turno_dia as n on  d.maquina = n.maquina and   n.dia =  d.dia and n.turno = 'Nocturno'  
					LEFT JOIN  pdp_maquina_turno_dia as x on  d.maquina = x.maquina and   x.dia =  d.dia and x.turno = 'Mixto'  
			where
			d.maquina = '$maq' and
			d.dia =  '$dia'
			
			";
			
			$command = \Yii::$app->db_mysql;
			
			$result =$command->createCommand($sql)->queryAll();
		
			$op = 0;
			$opa = [
			'Matutino'   => '---', 
			'Vespertino' => '---',
			'Nocturno'   => '---',
			'Mixto'   => '---'
			];
			
			
			$result[0]['Matutino'] = isset ($result[0]['Matutino']) ? $result[0]['Matutino'] : null; 
			$result[0]['Vespertino'] = isset ($result[0]['Vespertino']) ? $result[0]['Vespertino'] : null; 
			$result[0]['Nocturno'] = isset ($result[0]['Nocturno']) ? $result[0]['Nocturno'] : null; 
			$result[0]['Mixto'] = isset ($result[0]['Mixto']) ? $result[0]['Mixto'] : null; 
			
			//echo"traeopdia";print_r($result);
			
			
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
				if( $result[0]['Mixto'] != null ){ 
					$op = $result[0]['Mixto'];
					$opa['Mixto'] = $result[0]['Mixto'];
				} 
			$opa['Minutos']= $result[0]['Minutos'];
			//echo "OPA:-----------"; print_r($opa);
			if ($multiple == 0)
					return $op;
			else
					return $opa;
		}
		
		public function traeOpSemanal($maquina,$dia,$turno,$multiple=0){
			$maq = $maquina;
			echo "$maq -  $dia";
			$sql = "
			
			select Matutino,Vespertino,Nocturno,Mixto,Minutos
			from pdp_maquina_turnos
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
			'Nocturno'   => '---',
			'Mixto'   => '---'
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
				if( $result[0]['Mixto'] != null ){ 
					$op = $result[0]['Mixto'];
					$opa['Mixto'] = $result[0]['Mixto'];
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
					from pdp_maquina_turno_dia 
					where maquina ='$maquina'  and dia = '$dia' and turno = '$turno'
					
					")->queryAll();
					$tmp = $result[0]['m'];
		echo " existe ?  $tmp   \n ";
		return $result[0]['m'] >  0 ? true : false;
			
		}
		
		
		public function save_opturno_p2($data){
			
			 
			$command = \Yii::$app->db_mysql;
			 echo "         turno :              " ; print_r($data);
		
		// echo "save_opturno_p2";print_r($data);
			
		if (!$this->exist_turno($data['dia'],$data['maquina'],$data['turno'] ) ){
			if ( $data['turno'] == '---' || $data['operador'] == '---' ) return ;
			
			$result =$command->createCommand()->insert('pdp_maquina_turno_dia',[
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
					
				$result =$command->createCommand()->delete('pdp_maquina_turno_dia',[
														'dia' => $data['dia'],
														'maquina' => $data['maquina'],
														'turno' => $data['turno'],
													])->execute();
													// ])->getRawSql();
													// echo $result;
										
				
					return true; //corta ejecucion y sale
				}
			  
				if (  !$this->maquinaConPieza($data['maquina'],$data['dia'] ) ) {
					
				$result =$command->createCommand()->delete('pdp_maquina_turno_dia',[
														'dia' => $data['dia'],
														'maquina' => $data['maquina'],
													])->execute();
													// ])->getRawSql();
													// echo $result;
										
				
					return true; //corta ejecucion y sale
				}
			  
			  $result =$command->createCommand()->update('pdp_maquina_turno_dia',[
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
			
		public function maquinaConPieza($maquina,$dia){	
		
					$command = \Yii::$app->db_mysql;
		
		$result =$command
					->createCommand("
					
					Select  count(Maquina) as m 
					from pdp_cta_dia 
					where maquina ='$maquina'  and dia = '$dia' 
					
					")->queryAll();
					$tmp = $result[0]['m'];
		echo " existe ?  $tmp   \n ";
		return $result[0]['m'] >  0 ? true : false;
		
		
		}
		
		
		/// operadores
		public function GetInfo_diaop($fecha){
			$sql = "
				
				select   d.op, e.NOMBRECOMPLETO ,d.maquina,D.turno,d.minutos
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