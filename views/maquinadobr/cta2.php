
<?php
/* @var $this yii\web\View */

use yii\bootstrap\Modal;

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\helpers\URL;
use common\models\Grid;




//var_dump($Paroen);

echo Html::beginTag('div',['id'=>'tbDiaria']);
    echo "Ver: ".Html::tag("input","",[
        'id'=>'semana1',
        'type'=>'week',
        'value'=> $semana
    ]);
    
    $id = 'pre_programacion_maquinado'; // nombre de el div 
	$id2 = 'maquinasop';
	$id3 = 'maquinasop2';
echo Html::a('Actualizar',"javascript:void(0)",[
        'class'=>"easyui-linkbutton",
        'data-options'=>"iconCls:'icon-reload',plain:true",
        'onclick'=>"recargaPagina()"
    ]);


		$tmp = explode('W',$semana);
	
		
		$sem1 = $tmp[0].'W'.($tmp[1]+0);
		$sem2 = $tmp[0].'W'.($tmp[1]+1);
		$sem3 = $tmp[0].'W'.($tmp[1]+2);
		$sem4 = $tmp[0].'W'.($tmp[1]+3);
		
		$s1 = ($tmp[1]+0);
		$s2 = ($tmp[1]+1);
		$s3 = ($tmp[1]+2);
		$s4 = ($tmp[1]+3);
		
		
		

	
	
$this->registerJS("
    function cambia(grid){
	
		 var tmp = $('#semana1').val().split('W');
		 var tmp1 = parseInt(tmp[1]) + 0;		
		 var s1 = '';  
		 s1 = s1.concat( 'Semana ', tmp1.toString()); 
		 
		 var tmp2 = parseInt(tmp[1]) + 1;		
		 var s2 = '';  
		 s2 = s2.concat( 'Semana ', tmp2.toString()); 
		 
		 var tmp3 = parseInt(tmp[1]) + 2;		
		 var s3 = '';  
		 s3 = s3.concat( 'Semana ', tmp3.toString()); 
		 
		 var tmp4 = parseInt(tmp[1]) + 3;		
		 var s4 = '';  
		 s4 = s4.concat( 'Semana ', tmp4.toString()); 
							
		// var opt = $('#$id').datagrid('options');
		// opt.url = 'cta2d?fecha='+fecha;
		// opt.queryParams.semana = fecha;
		
        // $('#$id').datagrid('reload');
    	$('#thsem1').text('sem'); 
    	$('#thsem2').text('sem'); 
    	$('#thsem3').text('sem'); 
    	$('#thsem4').text('sem'); 
    	
		reloadcta3(false);
		tituloSemana('#$id','s1',s1);
		tituloSemana('#$id','s2',s2);
		tituloSemana('#$id','s3',s3);
		tituloSemana('#$id','s4',s4);
       
    }
",$this::POS_END);

//$this->registerJsFile("/fimex/frontend/assets/js/datagrid-editors.js",['position' => $this::POS_END]);

?>
<style>

.datagrid-footer {
    background-color: Beige;
}

</style>
<div class="easyui-panel" title="Maquina-pieza" style="width:100%;height:auto;padding:10px;">

							<table id="<?php echo $id ?>" class="easyui-datagrid " style="width:100%;height:560px;"

									data-options="
										url:'cta2d?fecha=<?=$semana?>',
										method:'post',
										singleSelect: true,
										showFooter: true,
										rowStyler:formateo,
										
										view:groupview,
										remoteSort:false,
										collapsible:true,
										pagination:true,
										rownumbers:true,
										
										view:groupview,
										
										groupField:'casting',
										groupFormatter:function(value,rows){
											
										   return value ;
											},																
										
										onClickCell:celda,
										toolbar:tb,
										queryParams: {
											semana: '<?=$semana?>'
														}
										"
								 
								toolbar: "'#tb'">
								
								<div id="tb" style="height:auto">
								<a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-search',plain:true" onclick="vistas(1)">pieza</a>
								<a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-search',plain:true" onclick="vistas(2)">maquina</a>
								<a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-undo',plain:true" onclick="ocultacolumnas()">Muetrsa/oculta ALM molde perm</a>
								<a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-add',plain:true" onclick="dosmaq()">En dos maq</a>
								

								<a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-undo',plain:true" onclick="deshacerfila()">Escape</a>
								<a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-sum',plain:true" onclick="cel()">configura celda</a>
						
					</div>
								
								<thead>
									<tr>
										<th colspan ="6"></th>
										<th colspan ="2">Operacion</th>
										<th colspan ="4">Embarques</th>
										<th colspan ="4">Almacenes</th>
										<th colspan ="4">Sem <?=$s1 ?> </th>
										<th colspan ="4">Sem <?=$s2 ?> </th>
										<th colspan ="4">Sem <?=$s3 ?> </th>
										<th colspan ="4">Sem <?=$s4 ?> </th>
										<th colspan ="8">Total</th>
										
									</tr>
									
									<tr>
										
										<th data-options="
										field:'Hold',
										width:50,
										align:'center',
										formatter:cellStyler,
										sortable:true,
										
										editor:{type:'checkbox',options:{on:'1',off:'0'}}
										">Hold</th>
										<th data-options="field:'casting',width:100,sortable:true,hidden:0">Casting</th>
										<th data-options="field:'producto',width:100,sortable:true,hidden:0">Parte</th>
										<th data-options="field:'descripcion',width:50,sortable:true,hidden:0">desc</th>
										<th data-options="field:'prioridad',width:30,editor:'numberbox',sortable:true">prioridad</th>
										<th data-options="field:'maquina1',width:100,sortable:true,
													
													styler:formatmaq,
													editor:{
														type:'combobox',
														options:{
															valueField:'maquina',
															textField:'maquina',
															url:'cta2',
															
															method:'get'
	
														}
													}
										
										">Maquina</th>
										
									
										<th data-options="field:'opx',width:50, styler:formateo_celda_faltantes,sortable:true">num</th>
										<th data-options="field:'Minutos',width:50, styler:formateo_celda_faltantes">min</th>
										
									   
										<th data-options="field:'sem1',width:50,sortable:true">sem<?=$s1 ?></th>
										<th data-options="field:'sem2',width:50,sortable:true">sem<?=$s2 ?></th>
										<th data-options="field:'sem3',width:50,sortable:true">sem<?=$s3 ?></th>
										<th data-options="field:'sem4',width:50,sortable:true">sem<?=$s4 ?></th>
										<th data-options="field:'PLB',width:50,sortable:true">PLBs</th>
										<th data-options="field:'CTB',width:50,sortable:true">CTBs</th>
										<th data-options="field:'PMB',width:50,sortable:true">PMBs</th>
										<th data-options="field:'PTB',width:50,sortable:true">PTB</th>
										
										<th data-options="field:'GPC',sortable:true,width:50,hidden:1">GPC</th>
										<th data-options="field:'GPCB',sortable:true,width:50,hidden:1">GPCB</th>
										<th data-options="field:'GPL',sortable:true,width:50,hidden:1">GPL</th>
										<th data-options="field:'GPM',sortable:true,width:50,hidden:1">GPM</th>
										<th data-options="field:'GPP',sortable:true,width:50,hidden:1">GPP</th>
										<th data-options="field:'GPT',sortable:true,width:50,hidden:1">GPT</th>
										
										
										<th id= "thsem1" data-options="field:'s1',width:60, styler:formateo_sem_celda_pza,editor:'numberbox',sortable:true">pza</th>
										<th data-options="field:'s1_min',width:50, styler:formateo_sem_celda,sortable:true">min</th>
										<th data-options="field:'hechas1',width:50,sortable:true">Buenas</th>
										<th data-options="field:'rechazadas1',width:50,sortable:true">Malas</th>
										
										<th id= "thsem2" data-options="field:'s2',width:60,styler:formateo_sem_celda_pza,editor:'numberbox',sortable:true">pza</th>
										<th data-options="field:'s2_min',width:50, styler:formateo_sem_celda,sortable:true">min</th>
										<th data-options="field:'hechas2',width:50,sortable:true">Buenas</th>
										<th data-options="field:'rechazadas2',width:50,sortable:true">Malas</th>

										<th id= "thsem3" data-options="field:'s3',width:60,styler:formateo_sem_celda_pza,editor:'numberbox',sortable:true">pza</th>
										<th data-options="field:'s3_min',width:50, styler:formateo_sem_celda,sortable:true">min</th>
										<th data-options="field:'hechas3',width:50,sortable:true">Buenas</th>
										<th data-options="field:'rechazadas3',width:50,sortable:true">Malas</th>
										
										<th id= "thsem4" data-options="field:'s4',width:60,styler:formateo_sem_celda_pza,editor:'numberbox',sortable:true">pza</th>
										<th data-options="field:'s4_min',width:50, styler:formateo_sem_celda,sortable:true">min</th>
										<th data-options="field:'hechas4',width:50,sortable:true">Buenas</th>
										<th data-options="field:'rechazadas4',width:50,sortable:true">Malas</th>


										<th data-options="field:'tot_pza',width:50,styler:formateo_sem_celda_pza,sortable:true">pza</th>
										<th data-options="field:'tot_min',width:50, styler:formateo_sem_celda,sortable:true">min</th>
									
										
										<th data-options="field:'op',width:50,hidden:1,sortable:true">op</th>
										<th data-options="field:'maq_ocup',width:50,hidden:1,sortable:true">maq_ocup</th>
										
										
										
										<th data-options="field:'pl',width:50,hidden:1">pl</th>
										<th data-options="field:'ct',width:50,hidden:1">ct</th>
										<th data-options="field:'pm',width:50,hidden:1">pm</th>
										<th data-options="field:'s1_min2',width:50,hidden:1">s1</th>
										<th data-options="field:'s2_min2',width:50,hidden:1">s2</th>
										<th data-options="field:'s3_min2',width:50,hidden:1">s3</th>
										
										
										<th data-options="field:'op',width:50,hidden:1">op</th>
										
										<!--
										<th data-options="field:'s2',width:50,editor:'numberbox'">sem2</th>
										<th data-options="field:'maquina2',width:100,
										
											
													editor:{
														type:'combobox',
														options:{
															valueField:'maquina2',
															textField:'maquina',
															url:'cta2',
															method:'get',
															
														}
													}
										
										">Maquina2</th>
										
										<th data-options="field:'s3',width:50,editor:'numberbox'">sem3</th>
										<th data-options="field:'maquina3',width:100,
										
											
													editor:{
														type:'combobox',
														options:{
															valueField:'maquina3',
															textField:'maquina',
															url:'cta2',
															method:'get',
															
														}
													}
										
										">Maquina3</th>
										
										<th data-options="field:'s4',width:50,editor:'numberbox'">sem4</th>
										<th data-options="field:'maquina4',width:100,
										
											
													editor:{
														type:'combobox',
														options:{
															valueField:'maquina4',
															textField:'maquina',
															url:'cta2',
															method:'get',
															
														}
													}
										
										">Maquina4</th> -->
										
									</tr>
								</thead>
							</table>
						
						
	</div> <!--fin panel pieza maquina -->
	
	
					
   <style>
		.hbox{
			display: inline-block;
			width:24.8%;
			height:100%;
			vertical-align: text-top;
			
		}
		.contenido{
			width:100%;
			height:auto;
			
		}
		
		.sem{
			
			
		}
	</style>
					
<div class="easyui-panel" title="Maquina-Operador" style="width:100%;height:auto;padding:10px;"
data-options="
                tools:[{
                    iconCls:'icon-reload',
                    handler:function(){
                        reloadcta3(true)
                    }
                }]"

>

		 <div class="contenido">
			 
			<div class="hbox" >
				<?= $this->render('cta3',[
								'semana'=>$sem1,
								'sem'=>$s1,
								'idOpMaq'  => '2',
								'idOP'  => '3'
							]);?>
			</div>
			<div class="hbox" >
			
				<?= $this->render('cta3',[
								'semana'=>$sem2,
								'sem'=>$s2,
								'idOpMaq'  => '4',
								'idOP'  => '5'
							]);?>
			</div>
			<div class="hbox" >
			
				<?= $this->render('cta3',[
								'semana'=>$sem3,
								'sem'=>$s3,
								'idOpMaq'  => '6',
								'idOP'  => '7'
							]);?>
			</div>
			<div class="hbox" >
			
				<?= $this->render('cta3',[
								'semana'=>$sem4,
								'sem'=>$s4,
								'idOpMaq'  => '8',
								'idOP'  => '9'
							]);?>
			</div>
			
		 </div>
		
</div>			

<?= $this->render('celdaprg',[
								
								's1'=>$s1,
								's2'=>$s2,
								's3'=>$s3,
								's4'=>$s4,
								'idOpMaq'  => '8',
								'idOP'  => '9'
							]);?>						
							
							
							
							
						
						
					
				
				
				
				
				
</div> <!-- panel -->


	<script type="text/javascript">
		
		var editIndex = undefined;
		
		function mismaMaq(row){
			//recolectar maquinas
			var inicioParte = 0; 
			
			if(row.opx != 10 ){
				inicioIndex = editIndex - (row.opx -10) / 10  
			}else{
				inicioIndex = editIndex ;
			}
			
			var maq = $('#<?php echo $id ?>').datagrid('getRows')[inicioIndex]['maquina1'];
			var maq_next = maq;
			var parte = $('#<?php echo $id ?>').datagrid('getRows')[inicioIndex]['producto'];
			var parte_next = parte;
				var i = 0; 
				while( (maq == maq_next) && parte == parte_next  ){
														
					i++;
					maq_next = $('#<?php echo $id ?>').datagrid('getRows')[inicioIndex+i]['maquina1'];
					parte_next = $('#<?php echo $id ?>').datagrid('getRows')[inicioIndex+i]['producto'];
					
					if (maq != maq_next ){return false;}
					
				}
			return true;
		}
		
		
		
		function dosmaq(){
			 var sel = $('#<?php echo $id ?>').datagrid('getSelected');
			 var inx=  $('#<?php echo $id ?>').datagrid('getRowIndex',sel);
				
				 // var maquina = $('#<?php echo $id ?>').datagrid('getRows')[inx]['maquina1'];
				 var maquina = $('#<?php echo $id ?>').datagrid('getEditor', {index:inx,field:'maquina1'});
				var	maqs = maquina.target.combobox('getData');
				
				if(maqs.length > 1 ){
						for(i= 0; i<= maqs.length; i++){
							var m= maqs[i].Maquina;
							
							 if  (m != sel.maquina1){  sel.maquina1 = m ; break;	 }
						}
				}else{
					alert("La pieza solo se trabaja en una Maquina"); exit;
				}
				
				sel.sem_actual = $('#semana1').val();
				sel.diario =  'n'; // diferencia entre alctualizacion desde mensual 
				sel.otramaq = 1 ;
				var data = [];
				data.push(sel);
				
				
				$.post('ctap1',
						{Data: JSON.stringify(data)},
						function(data,status){
							if(status == 'success' ){
								reloadcta3(false);
								console.log(data);
								$var = $(grid).datagrid('getChanges');
							}else{
								reject('#$id');
								alert('Error al guardar los datos');
							}
						}
					);
				
		}
		
		function guarda(){
			
			var maq1 = null;
				var s1 = null;
				var s2 = null;
				var s3 = null;
				var s4 = null;
				var prioridad = null;
				var sem_actual = null;
				var hold = null;
				 
				var ed1 = $('#<?php echo $id ?>').datagrid('getEditor', {index:editIndex,field:'maquina1'});
				var ed_s1 = $('#<?php echo $id ?>').datagrid('getEditor', {index:editIndex,field:'s1'});
				var ed_s2 = $('#<?php echo $id ?>').datagrid('getEditor', {index:editIndex,field:'s2'});
				var ed_s3 = $('#<?php echo $id ?>').datagrid('getEditor', {index:editIndex,field:'s3'});
				var ed_s4 = $('#<?php echo $id ?>').datagrid('getEditor', {index:editIndex,field:'s4'});
				var ed_prio = $('#<?php echo $id ?>').datagrid('getEditor', {index:editIndex,field:'prioridad'});
				var ed_hld = $('#<?php echo $id ?>').datagrid('getEditor', {index:editIndex,field:'Hold'});
				
				 if ((ed1 == null || ed_s1 == null || ed_s2 == null || ed_s3 == null || ed_s4 == null ) )
				 {return true;editIndex = undefined;}
				 
				 maq1 = $(ed1.target).combobox('getText');
				 s1 = $(ed_s1.target).numberbox('getValue');
				 s2 = $(ed_s2.target).numberbox('getValue');
				 s3 = $(ed_s3.target).numberbox('getValue');
				 s4 = $(ed_s4.target).numberbox('getValue');
				 hold = $(ed_hld.target).is(':checked');
				 sem_actual = $('#semana1').val();
				 
				
				
					// sales si es igual el dato capturado salgo para que no guarde de nuevo
					// el controlador recibe  n para saber que no guarde 
					if (row.opx == 10 ){
					 if($('#<?php echo $id ?>').datagrid('getRows')[editIndex]['s1'] == s1  ) s1 = 'n' ;
					 if($('#<?php echo $id ?>').datagrid('getRows')[editIndex]['s2'] == s2  ) s2 = 'n' ;
					 if($('#<?php echo $id ?>').datagrid('getRows')[editIndex]['s3'] == s3  ) s3 = 'n';
					 if($('#<?php echo $id ?>').datagrid('getRows')[editIndex]['s4'] == s4  ) s4 = 'n';
					}
					
					
					 //proceso para cuando borro el numero el sistema lo interprete como 0 para el borrado
					 if($('#<?php echo $id ?>').datagrid('getRows')[editIndex]['s1'] >  0 && s1 == '' ) s1 = "0";
					 if($('#<?php echo $id ?>').datagrid('getRows')[editIndex]['s2'] >  0 && s2 == '' ) s2 = "0";
					 if($('#<?php echo $id ?>').datagrid('getRows')[editIndex]['s3'] >  0 && s3 == '' ) s3 = "0";
					 if($('#<?php echo $id ?>').datagrid('getRows')[editIndex]['s4'] >  0 && s4 == '' ) s4 = "0";
				 
				 var oldmaq = $('#<?php echo $id ?>').datagrid('getRows')[editIndex]['maquina1'];
				 prioridad = $(ed_prio.target).numberbox('getValue');
				 $('#<?php echo $id ?>').datagrid('getRows')[editIndex]['Hold'] = hold;
				 $('#<?php echo $id ?>').datagrid('getRows')[editIndex]['maquina1'] = maq1;
				 $('#<?php echo $id ?>').datagrid('getRows')[editIndex]['prioridad'] = prioridad;
				 
				// $('#<?php echo $id ?>').datagrid('getRows')[editIndex]['Minutos'] = mismaMaq(row);
				
				var op = $('#<?php echo $id ?>').datagrid('getRows')[editIndex]['opx'];
				var i = 0;
				
		
				var data = []; 
				var pro = $('#<?php echo $id ?>').datagrid('getRows')[editIndex]['producto'];
				var pro_next = pro;
				
				while(pro == pro_next){
						
						if(row.opx > 10 && mismaMaq(row) ){
							s1 = $('#<?php echo $id ?>').datagrid('getRows')[editIndex+i]['s1'] ;
							s2 = $('#<?php echo $id ?>').datagrid('getRows')[editIndex+i]['s2'] ;
							s3 = $('#<?php echo $id ?>').datagrid('getRows')[editIndex+i]['s3'] ;
							s4 = $('#<?php echo $id ?>').datagrid('getRows')[editIndex+i]['s4'] ;
						}else{			
							 $('#<?php echo $id ?>').datagrid('getRows')[editIndex+i]['s1'] = s1;
							 $('#<?php echo $id ?>').datagrid('getRows')[editIndex+i]['s2'] = s2;
							 $('#<?php echo $id ?>').datagrid('getRows')[editIndex+i]['s3'] = s3;
							 $('#<?php echo $id ?>').datagrid('getRows')[editIndex+i]['s4'] = s4;
						}
					 // var tmp = $('#semana1').val().split('W');
					 // var tmp1 = parseInt(tmp[1]) + i;
					 // var sem = '';  
					 // sem = sem.concat( tmp[0].toString() ,'W', tmp1.toString()); 
					
					 $('#<?php echo $id ?>').datagrid('getRows')[editIndex+i]['sem_actual'] = sem_actual;
					 $('#<?php echo $id ?>').datagrid('getRows')[editIndex+i]['diario'] = 'n'; // diferencia entre alctualizacion desde mensual 
					 $('#<?php echo $id ?>').datagrid('getRows')[editIndex+i]['otramaq'] = 0; // diferencia entre alctualizacion desde mensual 
					 $('#<?php echo $id ?>').datagrid('getRows')[editIndex+i]['oldmaq'] = oldmaq; // diferencia entre alctualizacion desde mensual 
					data.push ( $('#<?php echo $id ?>').datagrid('getRows')[editIndex+i] );

					i++;
					pro_next = $('#<?php echo $id ?>').datagrid('getRows')[editIndex+i]['producto'];
				}
				
				$('#<?php echo $id ?>').datagrid('refreshRow',editIndex);
				
				
				var grid = '#<?php echo $id ?>';
					//$.post('".URL::to('/Fimex/programacion/save_semanal')."',
					$.post('ctap1',
						{Data: JSON.stringify(data)},
						function(data,status){
							if(status == 'success' ){
								reloadcta3(false);
								console.log(data);
								$var = $(grid).datagrid('getChanges');
							}else{
								reject('#$id');
								alert('Error al guardar los datos');
							}
						}
					);
				
				editIndex = undefined;
				$('#<?php echo $id ?>').datagrid('endEdit', editIndex);
				deshacerfila();
			
		}
		
		function endEditing(){
				
			if (editIndex == undefined){return true}
			if ($('#<?php echo $id ?>').datagrid('validateRow', editIndex)){
				
				guarda();
				// ed2 = $('#<?php echo $id ?>').datagrid('getEditor', {index:editIndex,field:'maquina2'});
				// maq2 = $(ed2.target).combobox('getText');
				// $('#<?php echo $id ?>').datagrid('getRows')[editIndex]
				
				// ed3 = $('#<?php echo $id ?>').datagrid('getEditor', {index:editIndex,field:'maquina3'});
				// maq3 = $(ed3.target).combobox('getText');
				// $('#<?php echo $id ?>').datagrid('getRows')[editIndex]['maquina3'] = maq3;
				
				// ed4 = $('#<?php echo $id ?>').datagrid('getEditor', {index:editIndex,field:'maquina4'});
				// maq4 = $(ed4.target).combobox('getText');
				// $('#<?php echo $id ?>').datagrid('getRows')[editIndex]['maquina4'] = maq4;
				
				
				return true;
			} else {
				return false;
			}
		}
		
		
		function deshacerfila(){
					var sel = $('#<?php echo $id ?>').datagrid('getSelected');
					var row=  $('#<?php echo $id ?>').datagrid('getRowIndex',sel);
					$('#<?php echo $id ?>').datagrid('cancelEdit',row);
					editIndex = undefined;
					$('#<?php echo $id ?>').datagrid('clearSelections');
					$('#<?php echo $id ?>')
					 .datagrid('getPanel')
					 .unbind('keydown')
					
		}
		function celda(index,field,value){
				
			if (field == 'Hold'){
			   
			   if(value == 1){
					$('#<?php echo $id ?>').datagrid('getRows')[index]['Hold'] = 0;
				}else{
					$('#<?php echo $id ?>').datagrid('getRows')[index]['Hold'] = 1;
				}
				row = $('#<?php echo $id ?>').datagrid('getRows')[index];
			    $.post('ctap1hold',
						{Data: JSON.stringify(row)},
							function(data,status){
								if(status == 'success' ){
									console.log(row);
									$var = $(grid).datagrid('getChanges');
								}else{
									reject('#$id');
									alert('Error al guardar los datos');
								}
							}
						);
			   
			}else{
				row = $('#<?php echo $id ?>').datagrid('getRows')[index];
				onClickRow(index,row);
			}
			
			 if (field == 'maquina1'){
				ed = $('#<?php echo $id ?>')
						.datagrid('getEditor', {index:index,field:'maquina1'});
				
				//$(ed.target).combobox().toggle();
		
			 }
			
		}
		
		
		function onClickRow(inx,row){
		
		var prod = row.producto;
		var op = row.opx;
		var maquina1  = $('#<?php echo $id ?>').datagrid('getColumnOption','maquina1');
		    maquina1.editor.options.url = 'cta2p1maquina?pieza='+prod+'&op='+op;
		// var maquina2  = $('#<?php echo $id ?>').datagrid('getColumnOption','maquina2');
		    // maquina2.editor.options.url = 'cta2p1maquina?pieza='+prod;
		// var maquina3  = $('#<?php echo $id ?>').datagrid('getColumnOption','maquina3');
		    // maquina3.editor.options.url = 'cta2p1maquina?pieza='+prod;
		// var maquina4  = $('#<?php echo $id ?>').datagrid('getColumnOption','maquina4');
		    // maquina4.editor.options.url = 'cta2p1maquina?pieza='+prod;
			
			//deshabilita para edicion los casting 
			 if(row.cast==1){
				 editIndex = undefined;
				
				deshacerfila();
				 return true;
				 }
			
		var ed = null;
			if (editIndex != inx){
				if (endEditing()){
					$('#<?php echo $id ?>').datagrid('selectRow', inx)
							.datagrid('beginEdit', inx);
					 ed = $('#<?php echo $id ?>')
						.datagrid('getEditor', {index:inx,field:'maquina1'});
					ed.target.combobox('reload');
					 // ed = $('#<?php echo $id ?>')
						// .datagrid('getEditor', {index:inx,field:'maquina2'});
					// ed.target.combobox('reload');
					// ed = $('#<?php echo $id ?>')
						// .datagrid('getEditor', {index:inx,field:'maquina3'});
					// ed.target.combobox('reload');
					// ed = $('#<?php echo $id ?>')
						// .datagrid('getEditor', {index:inx,field:'maquina4'});
					// ed.target.combobox('reload');
					
					 $('#<?php echo $id ?>')
					 .datagrid('getPanel')
					 .bind('keydown', function(e) {
						 // Escape 
                		if (e.keyCode === 27) {deshacerfila();}
						// Enter 
                		if (e.keyCode === 13 ) {guarda();}
						 // flecha der
                		if (e.keyCode === 39) {alert("--->")}
						// flecha izq
                		if (e.keyCode === 37) {alert("<-----")}
						// flecha abajo
                		if (e.keyCode === 40) {
							var col = editIndex;
								//guarda(); 
								$('#<?php echo $id ?>').datagrid('selectRow',inx+1).trigger('click');}
						// flecha arriba
                		if (e.keyCode === 38) {alert("arriba")} 
						// f5
                		if (e.keyCode === 116) {reloadcta3(true);} 
					 });
					
					var maq = ed.getValue;
					editIndex = inx;
				} else {
					$('#<?php echo $id ?>').datagrid('selectRow', editIndex);
				}
			}
		}
		
		var s1 = 0;
		var s2 = 0;
		var s3 = 0;
		var s4 = 0;
		var plb = 0;
		var ctb = 0;
		var sum = 0;
		function formateo(index,row){
			
			if (row.cast == 1){
				
					return 'color:darkgrey;';
			}		
			
			if (row.Hold == 1){
				
					return 'background-color:lightgrey;';
			}		
			if (row.opx == 10){		
					pmb =  parseInt(row.pm) ? parseInt(row.pm) : 0;
					ctb =  parseInt(row.ct) ? parseInt(row.ct) : 0;
					plb =  parseInt(row.pl) ? parseInt(row.pl) : 0;
					s1 =  parseInt(row.s1) ? parseInt(row.s1) : 0;
					s2 =  parseInt(row.s2) ? parseInt(row.s2) : 0;
					s3 =  parseInt(row.s3) ? parseInt(row.s3) : 0;
					s4 =  parseInt(row.s4) ? parseInt(row.s4) : 0;
					 sum = s1 + s2 + s3 +s4;		
			}
			if(row.opx == null) return;
			
				if (  sum  == 0  )
					 return ;
				if ( ctb == sum )
					return 'background-color:lightgreen;';
				
				if ( ctb < sum ){
					if (  plb + ctb + pmb >= sum  )
						return 'background-color:DarkOrange  ;';
				return 'background-color:IndianRed ;'
				};
				
				if ( ctb > sum )
					return 'background-color:lightblue ;';
		
		}
		function cellStyler(value,row,index){
			
			if(parseInt(row.Hold) > 0 ){
				var style = '<input type="checkbox" checked >';
					return style;
			}else{
				var style = '<input type="checkbox" >';
					return style;	
			}
			
			
		}
		function formatmaq(val,row,inx){
			// var op =  parseInt(row.op) ? parseInt(row.op) : 0;
			// if (op == 0 )
			   // return 'font-weight:bold;background-color: Yellow ;';
			if(row.maq_ocup == 1 )
			    return 'font-weight:bold;color: red ;';
			if (row.maquina1 == 0 && row.cast == 0)
			   return 'font-weight:bold;background-color: #FFFF66 ;';
			
			
			
		}
		
		function accept($data){
		  var grid = '#<?php echo $id ?>';
			if (endEditing()){
				//$(grid).datagrid('acceptChanges');
				
					var data = $(grid).datagrid('getChanges');
					//$.post('".URL::to('/Fimex/programacion/save_semanal')."',
					$.post('ctap1',
						{Data: JSON.stringify(data)},
						function(data,status){
							if(status == 'success' ){
								reloadcta3(true);
								//$(grid).datagrid('load');
								console.log(data);
								$var = $(grid).datagrid('getChanges');
							}else{
								reject('#$id');
								alert('Error al guardar los datos');
							}
						}
					);
				
				
			}
		}
		
		function reloadcta3(salta){
			
			var i = 0 ; 
			var tablas = [];
			$('.easyui-datagrid').each(  
				function(){ 
						tablas[i] = '#' + $(this).attr('id');
						 i++;
							} );

			for( i = 0 ; i <= 12; i++){
					
					if(salta == true){ 
						salta=false; i++;
					}
				$(tablas[i]).datagrid('reload');

			}
			deshacerfila();
		}
		function reject(){
		alert("reject!!");
			$('#<?php echo $id ?>').datagrid('rejectChanges');
			editIndex = undefined;
		}
		function getChanges(){
			var rows = $('#<?php echo $id ?>').datagrid('getChanges');
			alert(rows.length+' rows are changed!');
		}
		function tituloSemana(grid,field,sem){
			 //$(grid).datagrid('getPanel').find('.datagrid-header-row td[field="'+field+'"] span:first-child').text(sem);
			// $('#pre_programacion_maquinado').datagrid('selectRow',4).trigger('click');
						//$(grid).datagrid('getPanel').find('.datagrid-header-row td[field="s1"] span');
				
		}
		
		function formateo_sem_celda(val,row,inx){
	 
				return 'color:#474747;font-weight: bold;';

		}
		
		function formateo_sem_celda_pza(val,row,inx){
			 
				return 'font-weight: bold;';

		}
		
		function recargaPagina(){
			
			var sem = $('#semana1').val();
			window.location.href = 'cta2' + "?fecha=" + sem ;
			
		}
		
		function formateo_celda_faltantes(val,row,inx){
			
			// amarillo no tan grosero
		   if (row.opx == null && row.cast == 0)
			   return 'font-weight:bold;background-color: #FFFF66 ;';
		   
		   if (row.Minutos == null && row.cast == 0)

			   return 'font-weight:bold;background-color: #FFFF66 ;';
			
		}
		
		var toggle = 0;
		function  ocultacolumnas(){
			if (!toggle){
			//setParentColspan("PLB",1,this.grid);
			$('#<?php echo $id ?>').datagrid('hideColumn',"PLB");
			$('#<?php echo $id ?>').datagrid('hideColumn',"CTB");
			$('#<?php echo $id ?>').datagrid('hideColumn',"PMB");
			$('#<?php echo $id ?>').datagrid('hideColumn',"PTB");
			
			$('#<?php echo $id ?>').datagrid('showColumn',"GPL");
			$('#<?php echo $id ?>').datagrid('showColumn',"GPCB");
			$('#<?php echo $id ?>').datagrid('showColumn',"GPM");
			$('#<?php echo $id ?>').datagrid('showColumn',"GPT");
			this.toggle = 1; 
			}else{
			//setParentColspan("PLB",4,this.grid);
			$('#<?php echo $id ?>').datagrid('showColumn',"PLB");
			$('#<?php echo $id ?>').datagrid('showColumn',"CTB");
			$('#<?php echo $id ?>').datagrid('showColumn',"PMB");
			$('#<?php echo $id ?>').datagrid('showColumn',"PTB");
			
			$('#<?php echo $id ?>').datagrid('hideColumn',"GPL");
			$('#<?php echo $id ?>').datagrid('hideColumn',"GPCB");
			$('#<?php echo $id ?>').datagrid('hideColumn',"GPM");
			$('#<?php echo $id ?>').datagrid('hideColumn',"GPT");
			this.toggle = 0; 
			}
		}
		
function vistas(view){
			var defaultOpt =   $('#<?php echo $id ?>').datagrid('options');
			var opt = $.extend(true, {}, defaultOpt);
	
			if (view == 0) {//default list view

			} else if (view == 1) {// pieza
				opt.view = groupview;
				opt.groupField = 'producto';
				opt.groupFormatter = function(value, rows){
					return value;
				};
			} else if (view == 2) {//maquina
				opt.view = groupview;
				opt.groupField = 'maquina1';
				opt.groupFormatter = function(value, rows){
					return value;
				};
			} else if (view == 3){ //normal
				$('#<?php echo $id ?>').datagrid({
						view: $.fn.datagrid.defaults.view
						});
			}

			$('#<?php echo $id ?>').datagrid(opt);//re-render datagrid
			
		}
		
		function cel(){
				var sel = $('#<?php echo $id ?>').datagrid('getSelected');
				var inx=  $('#<?php echo $id ?>').datagrid('getRowIndex',sel);
				
				var maquina = $('#<?php echo $id ?>').datagrid('getRows')[inx]['maquina1'];
			
			
			
			if ( es_celda(maquina) ){
					//$('#id').val(maquina);
					//$('#id').textbox('setText',maquina);
					
					
					getCelId(maquina);
					
					
					$('#win_cel').window('open');
					$('#captura').datagrid('reload');
					$('#captura').datagrid('reload');
					
			}else{
				alert("Esta es una maquina , No es una  celda");
				
			}
			
			
		}
		
		function es_celda (maquina){
			
			if (maquina.length > 7) 
				return true
			else 
				return false;
		}
		
		function getCelId(cel){
			var data = { maquina: cel};
			$.ajax({
                data:  data,
                url:   'getcelid',
                type:  'get',
                beforeSend: function () {
                        // $("#resultado").html("Creando registro");
                },
                success:  function (response) {
                        response.replace(/\"/g, "");
						console.log(response);
						getCelName(response);
						$('#id').textbox('setValue',response);
						
				},
				error:  function (response) {
                        
						console.log(response);
						alert("No  se pudo obtener ID");
                }
        });
			
		}
		
		function getCelName(cel){
			var data = { idcelda: cel};
			$.ajax({
                data:  data,
                url:   'getcelname',
                type:  'get',
                beforeSend: function () {
                        // $("#resultado").html("Creando registro");
                },
                success:  function (response) {
                        response.replace(/\"/g, "");
						console.log(response);
						$('#descripcion').textbox('setValue',response);
				},
				error:  function (response) {
                        
						console.log(response);
						alert("No  se pudo obtener nombre");
                }
        });
			
		}

	</script>
