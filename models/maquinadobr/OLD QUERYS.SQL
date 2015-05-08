select 
				producto,
				
				CASE 
						WHEN get_programacion_cta(producto,$se1) is null THEN CONCAT(pdp_maquina_pieza.Maquina,'-',pdp_maquina_pieza.Minutos)
						ELSE CONCAT(get_programacion_cta(producto,$se1) ,'-',pdp_maquina_pieza.Minutos)
				END 
				     as maquina1,
					 
				
				null as e1,
				null as e2,
				get_existencia_almacen(producto,'PLA') + get_existencia_almacen(producto,'PLA2') as  PLA,
				get_existencia_almacen(producto,'CTA') as  CTA,
				get_existencia_almacen(producto,'PMA') + get_existencia_almacen(producto,'PMA2')  as  PMA,
				get_existencia_almacen(producto,'PTA') as  PTA,
				
				get_producto_semana_entrega(producto,$se1)as sem1,
				get_producto_semana_entrega(producto,$se2)as sem2,
				
				get_programacion_cta_cantidad(producto,$se1 ) as s1,
				get_programacion_cta_cantidad(producto,$se2) as s2,
				get_programacion_cta_cantidad(producto,$se3) as s3,
				get_programacion_cta_cantidad(producto,$se4) as s4,
				maq_piezas.Hold
				from 
				(				select  distinct almprod.PRODUCTO 
								from almprod 
								where almprod.ALMACEN = 'CTA' and almprod.EXISTENCIA <> 0
								
								Union 
								
								select DISTINCT pdp_cta.Pieza 
								from pdp_cta
								where pdp_cta.Semana between $se1 and $se4  
				) as prod
				LEFT JOIN maq_piezas on producto = maq_piezas.IDENTIFICACION
					LEFT JOIN (
						SELECT *
						FROM 	pdp_maquina_pieza 
						WHERE OP = 10
						GROUP BY Pieza
						
					
								
				) AS	pdp_maquina_pieza  on pdp_maquina_pieza.Pieza = prod.PRODUCTO 
				ORDER BY Hold,producto