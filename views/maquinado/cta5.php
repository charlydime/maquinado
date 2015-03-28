
<?php
/* @var $this yii\web\View */

use yii\bootstrap\Modal;

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\helpers\URL;
use common\models\Grid;
	
	
	
	$id2 = 'maquinasdia'.$idOpMaq;
	$id3 = 'maquinasdiaop'.$idOP;
	$tb = $idOpMaq;
	
?>

<table id="<?php echo $id2 ?>" title="<?=$dia?> <?=$fecha?>"  class="easyui-datagrid " style="width:100%;height:auto;"

        data-options="
			url:'cta5d?fecha=<?=$fecha?>',
			method:'post',
		    singleSelect: true,
			showFooter: true,
			rowStyler:formateo_diaop,
		    onClickRow:function(inx,row){ controldia<?php echo $idOpMaq ?>.onClickRow2(inx,row); } ,
			toolbar:tb<?php echo $tb ?>,
			queryParams: {
				fecha: '<?=$fecha?>'
							}
		"
   >

  

   <div id="tb<?php echo $tb ?>" style="height:auto">
						<!--
						<a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-save',plain:true" onclick="accept()">Accept</a>
						<a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-undo',plain:true" onclick="reject()">Reject</a>
						<a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-search',plain:true" onclick="getChanges()">GetChanges</a>
						-->
						<a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-undo',plain:true" onclick="controldia<?php echo $idOpMaq ?>.deshacerfila2()">Escape</a>
						
					</div>
   
    <thead>
		<tr>
			<th colspan='1'></th>
			<th colspan="3">Turnos</th>
			<th colspan="3">Tiempos</th>

		</tr>
	
        <tr>
 
		<th data-options="field:'maquina',width:80">Maquina</th>
		
			
			
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
			
			<th data-options="field:'min',width:60">Min</th>
			<th data-options="field:'min_hrs',width:60">Hrs</th>
			<th data-options="field:'min_t8',width:60">T8</th>
			
			
            
        </tr>
    </thead>
</table>


<table id="<?php echo $id3 ?>"   class="easyui-datagrid " style="width:100%;height:auto;"

        data-options="
			url:'lstop?fecha=<?=$fecha?>',
			method:'post',
			
			collapsible:true,
            rownumbers:true,
            
            view:groupview,
            groupField:'maquina',
            groupFormatter:function(value,rows){
				if (rows.length ==1 ){
					return value;
				}
               return value + ' - ' + rows.length + ' operador(es)';
                },
			
			queryParams: {
				fecha: '<?=$fecha?>'
							}
		"
   >
   <thead>
		<tr>
			<th data-options="field:'op',width:40">OP</th>
			<th data-options="field:'NOMBRECOMPLETO',width:140">Nombre</th>
			<th data-options="field:'maquina',width:70">Maquina</th>
			<th data-options="field:'minutos',width:60">Min</th>
			<th data-options="field:'turno',width:60">turno</th>
		</tr>
		
   </thead>
   
 </table>

	<script type="text/javascript">
	
	//class control
	function control(grid){
		 this.editIndex2 = undefined;
		 this.grid = grid;
		 this.dia = '<?=$fecha?>';
		 
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
				
				 $(this.grid).datagrid('getRows')[this.editIndex2]['Matutino'] = mat;
				 $(this.grid).datagrid('getRows')[this.editIndex2]['Vespertino'] = ves;
				 $(this.grid).datagrid('getRows')[this.editIndex2]['Nocturno'] = noc;
			
				 
				$(this.grid).datagrid('refreshRow',this.editIndex2);
				
				
				var data = $(this.grid).datagrid('getRows')[this.editIndex2];
					$.post('opsave',
						{Data: JSON.stringify(data),dia: this.dia },
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

var controldia<?php echo $idOpMaq ?> = new control('#<?php echo $id2 ?>'); 

function formateo_diaop(index,row){
		
				
		
		
		}
		
		
	</script>
