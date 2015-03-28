
<?php
/* @var $this yii\web\View */

use yii\bootstrap\Modal;

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\helpers\URL;
use common\models\Grid;
use frontend\models\maquinado\maquinadocta4;

$id = "diariopanel";
$tmp = explode('W',$semana);

$se1 =  $tmp[1]; 
$aio = substr($tmp[0],0,4);

$sem1 = $tmp[1];
$sem2 = $tmp[1]+1;
$model = new MaquinadoCTA4;

		 $lun = $model->semana2fecha($aio,$se1,'lun');
		 $mar = $model->semana2fecha($aio,$se1,'mar');
		 $mie = $model->semana2fecha($aio,$se1,'mie');
		 $jue = $model->semana2fecha($aio,$se1,'jue');
		 $vie = $model->semana2fecha($aio,$se1,'vie');
		 $sab = $model->semana2fecha($aio,$se1,'sab');
		 $dom = $model->semana2fecha($aio,$se1,'dom');



?>

<style>

.datagrid-footer {
    background-color: Beige;
}

</style>

<div id="tb" style="height:auto">
		<a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-undo',plain:true" onclick="control<?php echo $sem1 ?>.deshacerfila2()">Escape</a>
</div>

<div class="easyui-panel" title='Sem <?=$sem1?>' style="width:100%;height:auto;padding:10px;">
	
	<table id="<?php echo $id ?>" class="easyui-datagrid " style="width:100%;height:400px;"

			data-options="
				url:'cta4data?fecha=<?=$semana?>',
				method:'post',
				singleSelect: true,
				onClickRow:function(inx,row){ control<?php echo $sem1 ?>.onClickRow2(inx,row); },
				rowStyler:formateo_dia,
				showFooter: true,
				
				
				collapsible:true,

				rownumbers:true,
								
				view:groupview,
				groupField:'Maquina',
				groupFormatter:function(value,rows){			
								   return value ;
										},				
				
				
				toolbar:tb,
				queryParams: {
					semana: '<?=$semana?>'
							}
				"
					>
			<thead>
	
				<tr>
				<th colspan= 2></th>
				<th colspan= 2>Opeacion</th>
				<th colspan= 1></th>
				<th colspan= 2>Embaques</th>
				<th colspan= 4>Almacenes</th>
				<th colspan= 2>Sem <?=$sem1?></th>
				<th colspan=3>Lunes</th>
				<th colspan=3>Martes</th>
				<th colspan=3>Miercoles</th>
				<th colspan=3>Jueves</th>
				<th colspan=3>Viernes</th>
				<th colspan=3>Sabado</th>
				<th colspan=3>Domingo</th>
				<th colspan=2>Tot Prod</th>
				<th colspan=2>Tot Min</th>
				<th colspan= 2>SetUp</th>
				</tr>
				<tr>
					
					<th data-options="field:'Pieza',width:170,sortable:true">Producto</th>
					<th data-options="field:'prio',width:40">Prio</th>
					<th data-options="field:'op',width:40">num</th>
					<th data-options="field:'minmaq',width:53">Min</th>
					<th data-options="field:'p_t',width:53">pz*dia</th>
					<th data-options="field:'e0',width:50">Sem<?=$sem1?></th>
					<th data-options="field:'e1',width:50">Sem<?=$sem2?></th>
					<th data-options="field:'PLA',width:50">PLAs</th>
					<th data-options="field:'CTA',width:50">CTAs</th>
					<th data-options="field:'PMA',width:50">PMAs</th>
					<th data-options="field:'PTA',width:50">PTA</th>
					<th data-options="field:'Cantidad',width:35">Prg</th>
					<th data-options="field:'Minutos',width:35">Min</th>
										
					<th id = "lun_prg" data-options="field:'lun_prg',width:35,editor:'numberbox'">Prg</th>
					<th data-options="field:'lun_min',width:40">Min</th>
					<th data-options="field:'lun_set',width:40">setup</th>
		
					<th id = "mar_prg" data-options="field:'mar_prg',width:40,editor:'numberbox'">Prg</th>
					<th data-options="field:'mar_min',width:40">Min</th>
					<th data-options="field:'mar_set',width:40">setup</th>

					<th  id = "mie_prg" data-options="field:'mie_prg',width:40,editor:'numberbox'">Prg</th>
					<th data-options="field:'mie_min',width:40">Min</th>
					<th data-options="field:'mie_set',width:40">setup</th>
		
					<th  id = "jue_prg" data-options="field:'jue_prg',width:40,editor:'numberbox'">Prg</th>
					<th data-options="field:'jue_min',width:40">Min</th>
					<th data-options="field:'jue_set',width:40">set</th>
	
					<th id = "vie_prg" data-options="field:'vie_prg',width:40,editor:'numberbox'">Prg</th>
					<th data-options="field:'vie_min',width:40">Min</th>
					<th data-options="field:'vie_set',width:40">setup</th>
					
					<th id = "sab_prg" data-options="field:'sab_prg',width:40,editor:'numberbox'">Prg</th>
					<th data-options="field:'sab_min',width:40">Min</th>
					<th data-options="field:'sab_set',width:40">setup</th>
					
					<th id = "dom_prg" data-options="field:'dom_prg',width:40,editor:'numberbox'">Prg</th>
					<th data-options="field:'dom_min',width:40">Min</th>
					<th data-options="field:'dom_set',width:40">setup</th>
					
					<th data-options="field:'sum',width:40">Sum</th>
					<th data-options="field:'rest',width:40">Rest</th>
					
					<th data-options="field:'sum_min',width:40">Sum</th>
					<th data-options="field:'rest_min',width:40">Rest</th>
					
					<th data-options="field:'setup',width:40,hidden:0">min</th>
					<th id = "maq1" data-options="field:'maq1',width:40,editor:'numberbox'">Prg</th>
					<th data-options="field:'Maquina',width:80,hidden:1">Maquina</th>
				</tr>
			</thead>
	</table>

</div>

<style>
	.hbox{
			display: inline-block;
			width:19%;
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
					
<div class="easyui-panel" title="Operador" style="width:100%;height:auto;padding:10px;"
data-options="
                tools:[{
                    iconCls:'icon-reload',
                    handler:function(){
                        
                    }
                }]"

>

		<div class="contenido">
			 
			<div class="hbox" >
				<?= $this->render('cta5',[
								'dia'=> 'Lunes',
								'fecha' => $lun,
								'idOpMaq'  => '1',
								'idOP'  => '2'
							]);?>
			</div>
			
			<div class="hbox" >
				<?= $this->render('cta5',[
								'dia'=> 'Martes',
								'fecha' => $mar,
								'idOpMaq'  => '2',
								'idOP'  => '3'
							]);?>
			</div>
			
			<div class="hbox" >
				<?= $this->render('cta5',[
								'dia'=> 'Miercoles',
								'fecha' => $mie,
								'idOpMaq'  => '3',
								'idOP'  => '4'
							]);?>
			</div>
			<div class="hbox" >
				<?= $this->render('cta5',[
								'dia'=> 'Jueves',
								'fecha' => $jue,
								'idOpMaq'  => '4',
								'idOP'  => '5'
							]);?>
			</div>
			<div class="hbox" >
				<?= $this->render('cta5',[
								'dia'=> 'Viernes',
								'fecha' => $vie,
								'idOpMaq'  => '5',
								'idOP'  => '6'
							]);?>
			</div>
			<div class="hbox" >
				<?= $this->render('cta5',[
								'dia'=> 'Sabado',
								'fecha' => $sab,
								'idOpMaq'  => '6',
								'idOP'  => '7'
							]);?>
			</div>
			<div class="hbox" >
				<?= $this->render('cta5',[
								'dia'=> 'Domingo',
								'fecha' => $dom,
								'idOpMaq'  => '7',
								'idOP'  => '8'
							]);?>
			</div>
		 </div>



</div>

<script type="text/javascript">
	
		//class control
	function control(grid){
		 this.editIndex2 = undefined;
		 this.grid = grid;
		 this.semana = <?php echo $id?> ? <?php echo $id ?> : 0;
		 this.url = 'cta4salva';
		 
		this.llena = function(){
				var row =  $(this.grid).datagrid('getRows')[this.editIndex2];
			
				if (row.lun_prg > 0 
					|| row.mar_prg > 0 
					|| row.mier_prg > 0 
					|| row.jue_prg > 0 
					|| row.vie_prg > 0 
					|| row.sab_prg > 0 
					|| row.dom_prg > 0 
					)  {       return true;   }			
				var ed_lun = $(this.grid).datagrid('getEditor', {index:this.editIndex2,field:'lun_prg'});
				var ed_mar = $(this.grid).datagrid('getEditor', {index:this.editIndex2,field:'mar_prg'});
				var ed_mie = $(this.grid).datagrid('getEditor', {index:this.editIndex2,field:'mie_prg'});
				var ed_jue = $(this.grid).datagrid('getEditor', {index:this.editIndex2,field:'jue_prg'});
				var ed_vie = $(this.grid).datagrid('getEditor', {index:this.editIndex2,field:'vie_prg'});
				var ed_sab = $(this.grid).datagrid('getEditor', {index:this.editIndex2,field:'sab_prg'});
				var ed_dom = $(this.grid).datagrid('getEditor', {index:this.editIndex2,field:'dom_prg'});
										
				
				var repartir = row.Cantidad;
				var dif = 0;
				
				var vals = [];
				var dia = 1 
				while(	repartir >= row.p_t 
						 	// repartir > 0 
						&&	dia <5 
						){
				
					if ( (repartir - row.p_t) >= 0){
						repartir = repartir - row.p_t;
					
					}else {
						vals.push(repartir) ;
						break;
					}
					
					vals.push(row.p_t);
					dia++;
				}
				
				if (repartir <= row.p_t ){
					vals.push(repartir);
				}
				
				$(ed_lun.target).numberbox('setValue',vals[0]);
				$(ed_mar.target).numberbox('setValue',vals[1]);
				$(ed_mie.target).numberbox('setValue',vals[2]);
				$(ed_jue.target).numberbox('setValue',vals[3]);
				$(ed_vie.target).numberbox('setValue',vals[4]);
				$(ed_sab.target).numberbox('setValue',vals[5]);
				$(ed_dom.target).numberbox('setValue',vals[6]);
		}
		 					
		this.teclas = function(e) {
						 // Escape 
                		if (e.keyCode === 27) {this.deshacerfila2();}
						// Enter 
                		if (e.keyCode === 13 ) {this.guarda();}
						 // flecha der
                		// if (e.keyCode === 39) {alert("--->");}
						// flecha izq
                		// if (e.keyCode === 37) {alert("<-----")}
						// flecha abajo
                		if (e.keyCode === 40) {
							var col = editIndex;
								//guarda(); 
								$(this.grid).datagrid('selectRow',this.editIndex2+1).trigger('click');
								}
						// flecha arriba
                		if (e.keyCode === 38) {alert("arriba")} 
						// f5
                		//if (e.keyCode === 116) {reloadcta3(true);} 
					 }
		 
		 this.guarda = function(){
			
				var lun_prg = null;
				var mar_prg = null;
				var mie_prg = null;
				var jue_prg = null;
				var vie_prg = null;
				var sab_prg = null;
				var dom_prg = null;
				
				
				var ed_lun = $(this.grid).datagrid('getEditor', {index:this.editIndex2,field:'lun_prg'});
				var ed_mar = $(this.grid).datagrid('getEditor', {index:this.editIndex2,field:'mar_prg'});
				var ed_mie = $(this.grid).datagrid('getEditor', {index:this.editIndex2,field:'mie_prg'});
				var ed_jue = $(this.grid).datagrid('getEditor', {index:this.editIndex2,field:'jue_prg'});
				var ed_vie = $(this.grid).datagrid('getEditor', {index:this.editIndex2,field:'vie_prg'});
				var ed_sab = $(this.grid).datagrid('getEditor', {index:this.editIndex2,field:'sab_prg'});
				var ed_dom = $(this.grid).datagrid('getEditor', {index:this.editIndex2,field:'dom_prg'});
				var ed_maq1 = $(this.grid).datagrid('getEditor', {index:this.editIndex2,field:'maq1'});
										
				if (
				ed_lun == null || 
					ed_mar == null || 
					ed_mie == null || 
					ed_jue == null || 
					ed_vie == null || 
					ed_sab == null || 
					ed_dom == null || 
					ed_jue == null 
					)
					{return true;this.editIndex2 = undefined;}
				
				
				lun_prg = $(ed_lun.target).numberbox('getValue');
				mar_prg = $(ed_mar.target).numberbox('getValue');
				mie_prg = $(ed_mie.target).numberbox('getValue');
				jue_prg = $(ed_jue.target).numberbox('getValue');
				vie_prg = $(ed_vie.target).numberbox('getValue');
				sab_prg = $(ed_sab.target).numberbox('getValue');
				dom_prg = $(ed_dom.target).numberbox('getValue');
				maq1    = $(ed_maq1.target).numberbox('getValue');
				
				var row = $(this.grid).datagrid('getRows')[this.editIndex2]; 
				
				
					if( $(this.grid).datagrid('getRows')[this.editIndex2]['lun_prg'] >  0 && lun_prg == '' ) lun_prg = "0";
					if( $(this.grid).datagrid('getRows')[this.editIndex2]['mar_prg'] >  0 && mar_prg == '' ) mar_prg = "0";
					if( $(this.grid).datagrid('getRows')[this.editIndex2]['mie_prg'] >  0 && mie_prg == '' ) mie_prg = "0";
					if( $(this.grid).datagrid('getRows')[this.editIndex2]['jue_prg'] >  0 && jue_prg == '' ) jue_prg = "0";
					if( $(this.grid).datagrid('getRows')[this.editIndex2]['vie_prg'] >  0 && vie_prg == '' ) vie_prg = "0";
					if( $(this.grid).datagrid('getRows')[this.editIndex2]['sab_prg'] >  0 && sab_prg == '' ) sab_prg = "0";
					if( $(this.grid).datagrid('getRows')[this.editIndex2]['dom_prg'] >  0 && dom_prg == '' ) dom_prg = "0";
				
				
				var i = 0;
				
				var data = []; 
				
			
					$(this.grid).datagrid('getRows')[this.editIndex2]['lun_prg'] = lun_prg;
					$(this.grid).datagrid('getRows')[this.editIndex2]['mar_prg'] = mar_prg;
					$(this.grid).datagrid('getRows')[this.editIndex2]['mie_prg'] = mie_prg;
					$(this.grid).datagrid('getRows')[this.editIndex2]['jue_prg'] = jue_prg;
					$(this.grid).datagrid('getRows')[this.editIndex2]['vie_prg'] = vie_prg;
					$(this.grid).datagrid('getRows')[this.editIndex2]['sab_prg'] = sab_prg;
					$(this.grid).datagrid('getRows')[this.editIndex2]['dom_prg'] = dom_prg;
					
				
				
				r = $(this.grid).datagrid('getRows')[this.editIndex2];
				can = parseInt(r.Cantidad) ? parseInt(r.Cantidad) : 0 ;
				min = parseInt(r.Minutos) ? parseInt(r.Minutos) : 0 ;
				
				lun = parseInt(lun_prg) ? parseInt(lun_prg) : 0; 
				mar = parseInt(mar_prg) ? parseInt(mar_prg) : 0; 
				mie = parseInt(mie_prg) ? parseInt(mie_prg) : 0; 
				jue = parseInt(jue_prg) ? parseInt(jue_prg) : 0; 
				vie = parseInt(vie_prg) ? parseInt(vie_prg) : 0; 
				sab = parseInt(sab_prg) ? parseInt(sab_prg) : 0; 
				dom = parseInt(dom_prg) ? parseInt(dom_prg) : 0;
				
				var setup_poner = 0 ;
				
				if (maq1 == 1){
					if(lun != 0                    ){$(this.grid).datagrid('getRows')[this.editIndex2]['lun_set'] = 1;setup_poner = 1 ;}
					if(mar != 0&& setup_poner == 0){$(this.grid).datagrid('getRows')[this.editIndex2]['mar_set'] = 1;setup_poner = 1 ;}
					if(mie != 0&& setup_poner == 0){$(this.grid).datagrid('getRows')[this.editIndex2]['mie_set'] = 1;setup_poner = 1 ;}
					if(jue != 0&& setup_poner == 0){$(this.grid).datagrid('getRows')[this.editIndex2]['jue_set'] = 1;setup_poner = 1 ;}
					if(vie != 0&& setup_poner == 0){$(this.grid).datagrid('getRows')[this.editIndex2]['vie_set'] = 1;setup_poner = 1 ;}
					if(sab != 0&& setup_poner == 0){$(this.grid).datagrid('getRows')[this.editIndex2]['sab_set'] = 1;setup_poner = 1 ;}
					if(dom != 0&& setup_poner == 0){$(this.grid).datagrid('getRows')[this.editIndex2]['dom_set'] = 1;setup_poner = 1 ;}
				}
				
				
				$(this.grid).datagrid('getRows')[this.editIndex2]['lun_min'] = (lun * min) / can;
				$(this.grid).datagrid('getRows')[this.editIndex2]['mar_min'] = (mar * min) / can;
				$(this.grid).datagrid('getRows')[this.editIndex2]['mie_min'] = (mie * min) / can;
				$(this.grid).datagrid('getRows')[this.editIndex2]['jue_min'] = (jue * min) / can;
				$(this.grid).datagrid('getRows')[this.editIndex2]['vie_min'] = (vie * min) / can;
				$(this.grid).datagrid('getRows')[this.editIndex2]['sab_min'] = (sab * min) / can;
				$(this.grid).datagrid('getRows')[this.editIndex2]['dom_min'] = (dom * min) / can;
				
				sum = lun+mar+mie+jue+vie+sab+dom
				
				$(this.grid).datagrid('getRows')[this.editIndex2+i]['sum'] = sum ;
				$(this.grid).datagrid('getRows')[this.editIndex2+i]['rest'] = can-sum;
				
				
					
					data.push ( $(this.grid).datagrid('getRows')[this.editIndex2] );

		
				
				$(this.grid).datagrid('refreshRow',this.editIndex2);
				
				
					$.post(this.url,
						{Data: JSON.stringify(data),semana: <?php echo $sem1 ?>},
						function(data,status){
							if(status == 'success' ){
								$(grid).datagrid('load');
								
								console.log(data);
								$var = $(grid).datagrid('getChanges');
							}else{
								reject('#$id');
								alert('Error al guardar los datos');
							}
						}
					);
				this.recargaSigGrid(grid);
				this.editIndex2 = undefined;
				$(this.grid).datagrid('endEdit', this.editIndex2);
				this.deshacerfila2();
			 
		 }
		
		this.endEditing2 = function (){
						
				
			if (this.editIndex2 == undefined){return true}
			if ($(this.grid).datagrid('validateRow', this.editIndex2)){
				
				
				this.guarda();
				
				return true;
			} else {
				return false;
			}
		}
		
		
		this.deshacerfila2 = function (){
					var sel = $(this.grid).datagrid('getSelected');
					var row=  $(this.grid).datagrid('getRowIndex',sel);
					$(this.grid).datagrid('cancelEdit',row);
					this.editIndex2 = undefined;
					$(this.grid).datagrid('clearSelections');
					
					$(this.grid)
					 .datagrid('getPanel')
					 .unbind('keydown')
					
		}
	
		
		this.onClickRow2 = function (inx,row){

					var ed = null
			if (this.editIndex != inx){
				if (this.endEditing2()){
					$(this.grid).datagrid('selectRow', inx)
							.datagrid('beginEdit', inx);
					
					var instancia = this;
					$(this.grid).datagrid('getPanel').bind('keydown', function(e) { instancia.teclas(e);} );
					
					this.editIndex2 = inx;
					this.llena();
				
				} else {
					$(this.grid).datagrid('selectRow', this.editIndex2);
				}
			}
		}
		
		this.getSemana = function(){
			
			if (this.semana==2){
				s1 = $('#semana1').val();
			}
			if (this.semana==4){
					var tmp = $('#semana1').val().split('W');
					 var tmp1 = parseInt(tmp[1]) + 1 ;		
					 var s1 = '';  
					 s1 = s1.concat( tmp[0].toString() ,'W', tmp1.toString()); 
			} 
			if (this.semana==6){
					var tmp = $('#semana1').val().split('W');
					 var tmp1 = parseInt(tmp[1]) + 2;		
					 var s1 = '';  
					 s1 = s1.concat( tmp[0].toString() ,'W', tmp1.toString()); 
			} 
			if (this.semana==8){
					var tmp = $('#semana1').val().split('W');
					 var tmp1 = parseInt(tmp[1]) + 3;		
					 var s1 = '';  
					 s1 = s1.concat( tmp[0].toString() ,'W', tmp1.toString()); 
			} 
			
			return s1;
			
		}
		
		this.recargaSigGrid = function(grid){
			var nextgrid = null; 
			var tablas = [];
			
		$('.easyui-datagrid').each(  
			function(){ 
			       var i = 0;
				   var t =  '#' + $(this).attr('id')
					tablas[i] = '#' + $(this).attr('id');
					if (nextgrid == 0){
						nextgrid =  t;
					}
					if (grid ==  t ){
						nextgrid =  0;
					}
					
					 i++;
						} );
			$(nextgrid).datagrid('reload');
			
			
			
		}
		

	}	//class control

var control<?php echo $sem1 ?> = new control('#<?php echo $id ?>'); 




function formateo_dia(index,row){
		var m = row.sum;
		var o = row.Cantidad;
		
		
		var minutos_m =  parseInt(m) ? parseInt(m) : 0;
		var minutos_o =  parseInt(o) ? parseInt(o) : 0;
				
		if (row.lun_prg == null
		&& row.mar_prg == null
		&& row.mie_prg == null
		&& row.jue_prg == null
		&& row.vie_prg == null
		&& row.sab_prg == null
		&& row.dom_prg == null
		
		) return;
			if (  minutos_m  == 0  )
				 return ;
			if ( minutos_o == minutos_m )
				return 'background-color:lightgreen;font-weight:bold;';
			if ( minutos_o <= minutos_m )
				return 'background-color:IndianRed;font-weight:bold;';
			if ( minutos_o >= minutos_m )
				return 'background-color: lightblue;font-weight:bold;';
		
		}
		
	</script>