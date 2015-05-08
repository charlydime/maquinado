
<?php
/* @var $this yii\web\View */

use yii\bootstrap\Modal;

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\helpers\URL;
use common\models\Grid;
	$id2 = 'maquinasop'.$idOpMaq;
	$id3 = 'maquinasop'.$idOP;
	$tb = $idOpMaq
?>

<table id="<?php echo $id2 ?>" title="Semana <?=$sem?> - Turnos"  class="easyui-datagrid " style="width:100%;height:auto;"

        data-options="
			url:'cta3d?fecha=<?=$semana?>',
			method:'post',
		    singleSelect: true,
			showFooter: true,
			rowStyler:formateo_op,
			collapsible:true,
		    onClickRow:function(inx,row){ controlsemana<?php echo $idOpMaq ?>.onClickRow2(inx,row); } ,
			toolbar:tb<?php echo $tb ?>,
			queryParams: {
				semana: '<?=$semana?>'
							}
		"
   >

  

   <div id="tb<?php echo $tb ?>"  style="height:auto">
						<!--
						<a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-save',plain:true" onclick="accept()">Accept</a>
						<a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-undo',plain:true" onclick="reject()">Reject</a>
						<a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-search',plain:true" onclick="getChanges()">GetChanges</a>
						-->
						<a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-undo',plain:true" onclick="controlsemana<?php echo $idOpMaq ?>.deshacerfila2()">Escape</a>
						<a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-search',plain:true" onclick="controlsemana<?php echo $idOpMaq ?>.diario()">Ver L-M</a>
						
					</div>
   
    <thead>
		<tr>
			<th rowspan='2' data-options="field:'maquina',width:100">Maquina</th>
			<th colspan="3">Turnos</th>
			<th colspan="3">Tiempo a Maquinar</th>
			
		</tr>
	
        <tr>
 
		
			
			
			<th data-options="field:'Matutino',width:40,
										
											
				editor:{
					type:'combobox',
					options:{
					valueField:'operador',
					textField:'NOMBRECOMPLETO',
					panelWidth:300,
					url:'cta2',
					method:'get',
						}
				}
			">M</th>
			
			<th data-options="field:'Vespertino',width:40,
										
											
				editor:{
					type:'combobox',
					options:{
					valueField:'operador',
					textField:'NOMBRECOMPLETO',
					panelWidth:300,
					url:'cta2',
					method:'get',
						}
				}
			">V</th>
			
			<th data-options="field:'Nocturno',width:40,
										
											
				editor:{
					type:'combobox',
					options:{
					valueField:'operador',
					textField:'NOMBRECOMPLETO',
					panelWidth:300,
					url:'cta2',
					method:'get',
						}
				}
			">N</th>
			
			<th data-options="field:'minutos_m',width:60">Min</th>
			<th data-options="field:'horas_m',width:40">Hrs</th>
			<th data-options="field:'t9_m',width:40">T9</th>
	
			
			

			
            
        </tr>
    </thead>
</table>

<table id="<?php echo $id3 ?>" title="Semana <?=$sem?> - Operador" class="easyui-datagrid " style="width:100%;height:auto;"
		
        data-options="
			url:'cta4d?fecha=<?=$semana?>',
			method:'post',
		    singleSelect: true,
			showFooter: true,
		    
			rowStyler:formateo,
										
			collapsible:true,

			rownumbers:true,
			
			view:groupview,
			groupField:'nombre',
			groupFormatter:function(value,rows){
				
			   return value ;
				},				

			queryParams: {
				semana: '<?=$semana?>'
							}
		"
   >
   
   
   
    <thead>
			
        <tr>
 
			<th data-options="field:'turno',width:40">Cod</th>
			<th data-options="field:'nombre',width:200,hidden:1">Operador</th>		
			<th data-options="field:'titulo',width:50">Turno</th>
			<th data-options="field:'maquina',width:90">Maquina</th>
			<th data-options="field:'minutos',width:45">Min</th>
			<th data-options="field:'horas',width:45">Hrs</th>
			<th data-options="field:'t8',width:45">T8</th>
			
			
            
        </tr>
    </thead>
</table>


<!--
<table id="<?php echo $id3."c" ?>" title="Semana <?=$sem?> - Parte" class="easyui-datagrid " style="width:100%;height:auto;"
		
        data-options="
			url:'cta4cd?fecha=<?=$semana?>',
			method:'post',
		    singleSelect: true,
			showFooter: true,
		    
			rowStyler:formateo,
										
			collapsible:true,

			rownumbers:true,
			
			view:groupview,
			groupField:'Pieza',
			groupFormatter:function(value,rows){
				
			   return value ;
				},				

			queryParams: {
				semana: '<?=$semana?>'
							}
		"
   >
   
   
   
    <thead>
			
        <tr>
 
			<th data-options="field:'op',width:40">OP</th>
			<th data-options="field:'Cantidad',width:50">Prog</th>
			<th data-options="field:'m',width:100">m</th>
			<th data-options="field:'v',width:100">v</th>
			<th data-options="field:'n',width:100">n</th>
			<th data-options="field:'Pieza',width:90,hidden:1">Pieza</th>
			
			
            
        </tr>
    </thead>
</table>

-->


	<script type="text/javascript">
	
		//class control
	function control(grid){
		 this.editIndex2 = undefined;
		 this.grid = grid;
		 this.semana = <?php echo $idOpMaq ?> ? <?php echo $idOpMaq ?> : 0;
		 this.sem = '<?=$semana?>';
		this.endEditing2 = function (){
				
				var mat = null;
				var ves = null;
				var noc = null;
				var semana = null ;
			if (this.editIndex2 == undefined){return true}
			if ($(this.grid).datagrid('validateRow', this.editIndex2)){
				
				var ed_mat = $(this.grid).datagrid('getEditor', {index:this.editIndex2,field:'Matutino'});
				var ed_ves = $(this.grid).datagrid('getEditor', {index:this.editIndex2,field:'Vespertino'});
				var ed_noc = $(this.grid).datagrid('getEditor', {index:this.editIndex2,field:'Nocturno'});
				 
				  if (ed_mat == null || ed_ves == null || ed_noc == null  )
				  {return true;this.editIndex2 = undefined;}
				 
				 mat = $(ed_mat.target).combobox('getValue');
				 ves = $(ed_ves.target).combobox('getValue');
				 noc = $(ed_noc.target).combobox('getValue');
				//semana = $('#semana1').val(); directo de campo
				semana = this.getSemana();
				 $(this.grid).datagrid('getRows')[this.editIndex2]['Matutino'] = mat;
				 $(this.grid).datagrid('getRows')[this.editIndex2]['Vespertino'] = ves;
				 $(this.grid).datagrid('getRows')[this.editIndex2]['Nocturno'] = noc;
				 $(this.grid).datagrid('getRows')[this.editIndex2]['semana'] = semana;
				 
				$(this.grid).datagrid('refreshRow',this.editIndex2);
				
				
				var data = $(this.grid).datagrid('getRows')[this.editIndex2];
					$.post('ctap2',
						{Data: JSON.stringify(data)},
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
					
		}
	
		
		this.onClickRow2 = function (inx,row){

					var ed = null
			if (this.editIndex != inx){
				if (this.endEditing2()){
								
								var maquina = row.maquina;
					var mat  = $(this.grid).datagrid('getColumnOption','Matutino');
						mat.editor.options.url = 'cta3p2operador?maquina='+maquina;
					
					var ves  = $(this.grid).datagrid('getColumnOption','Vespertino');
						ves.editor.options.url = 'cta3p2operador?maquina='+maquina;
						
					var noc  = $(this.grid).datagrid('getColumnOption','Nocturno');
						noc.editor.options.url = 'cta3p2operador?maquina='+maquina;
								
								$(this.grid).datagrid('selectRow', inx)
										.datagrid('beginEdit', inx);
							
					this.editIndex2 = inx;
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
		
		this.diario = function(){
			
			alert('cta4?semana='+this.sem);
			window.open('cta4?semana='+this.sem);
			
		}
		

	}	//class control

var controlsemana<?php echo $idOpMaq ?> = new control('#<?php echo $id2 ?>'); 

function formateo_op(index,row){
		var m = row.horas_m;
		
		m = m.replace(/,/g, "");
		
		var horas_m =  parseInt(m) ? parseInt(m) : 0;
		var i = 0 ;
		if ( parseInt(row.Matutino) > 0 ) i++;
		if ( parseInt(row.Vespertino) > 0 ) i++;
		if ( parseInt(row.Nocturno) > 0 ) i++;
		
		var turnos = i*8;
		
		if (row.Matutino == null
		&& row.Vespertino == null
		&& row.Nocturno == null
		) return;
			if (  horas_m  == 0  )
				 return ;
			if ( turnos == horas_m )
				return 'background-color:lightgreen;font-weight:bold;';
			if ( turnos >= horas_m )
				return 'background-color:IndianRed;font-weight:bold;';
			if ( turnos <= horas_m )
				return 'background-color: lightblue;font-weight:bold;';
		
		}
		
	</script>
