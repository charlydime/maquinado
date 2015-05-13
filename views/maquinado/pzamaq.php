
<?php
/* @var $this yii\web\View */

use yii\bootstrap\Modal;

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\helpers\URL;
use common\models\Grid;
	$tb = "barraherram";
	$id2 = "maquinapza";
?>

<table id="<?php echo $id2 ?>" title="Pieza Maquina"  class="easyui-datagrid " style="width:100%;height:500px;"

        data-options="
			url:'lstpzamaq',
			method:'post',
		    singleSelect: true,
			onClickRow:function(inx,row){ controlpm.onClickRow2(inx,row); },
			
			view:groupview,
				groupField:'pieza',
				groupFormatter:function(value,rows){			
								  return value ;
								 
										},	
			
	
								
				
		    
			toolbar:tb<?php echo $tb ?>
			
		"
   >

  

   <div id="tb<?php echo $tb ?>"  style="height:auto">
						
						<a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-add',plain:true" onclick="controlpm.add()">Agregar</a>
						<a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-remove',plain:true" onclick="controlpm.del()">Borrar </a>
						<a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-undo',plain:true" onclick="controlpm.deshacerfila2()">Escape </a>
						<a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-sum',plain:true" onclick="controlpm.celda()">asignar celda</a>
						<!-- <a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-search',plain:true" onclick="getChanges()">GetChanges</a>
						-->
						
						
					</div>
   
    <thead>
		
	
        <tr>
 
		
			
			<th data-options="field:'id',width:60,editor:'numberbox'">ID</th>
			
			<th data-options="field:'pieza',width:200,
										
											
				editor:{
					type:'combobox',
					options:{
					valueField:'pieza',
					textField:'pieza',
					panelWidth:300,
					url:'pmpza',
					method:'get',
						}
				}
			">Pieza</th>
			
			<th data-options="field:'maquina',width:100,
										
											
				editor:{
					type:'combobox',
					options:{
					valueField:'maquina',
					textField:'maquina',
					panelWidth:300,
					url:'pmmaq',
					method:'get',
						}
				}
			">Maquina</th>
			
			<th data-options="field:'OP',width:100,
										
											
				editor:{
					type:'combobox',
					options:{
					valueField:'op',
					textField:'op',
					panelWidth:50,
					url:'pmop',
					method:'get',
						}
				}
			">OP</th>
			
			
			
			
			<th data-options="field:'minutos',width:60,editor:'numberbox'">Min</th>
			<th data-options="field:'minutos1maquinado',width:50,editor:'numberbox'">Setup</th>
			<th data-options="field:'celda',width:50,editor:'numberbox'">Celda</th>
			
			
			<th data-options="field:'siguiente',width:40,
										
											
				editor:{
					type:'combobox',
					options:{
					valueField:'id',
					textField:'id',
					panelWidth:50,
					url:'pmsig',
					method:'get',
						}
				}
			">SIG</th>
			
			
			

			
            
        </tr>
    </thead>
</table>

<script type="text/javascript">
	
		//class control
	function control(grid){
		 this.editIndex2 = undefined;
		 this.grid = grid;
		 
		 this.url = 'pzamaqsalva';

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
			
			var pieza  = null;
			var maquina  = null;
			var sig  = null;
			var op  = null;
			var minutos  = null;
			var minutos1maquinado  = null;
			var celda  = null;
			var siguiente  = null;
			var data = []; 
			
			var ed_pieza = $(this.grid).datagrid('getEditor', {index:this.editIndex2,field:'pieza'});
			var ed_maquina = $(this.grid).datagrid('getEditor', {index:this.editIndex2,field:'maquina'});
			var ed_sig = $(this.grid).datagrid('getEditor', {index:this.editIndex2,field:'siguiente'});
			var ed_op = $(this.grid).datagrid('getEditor', {index:this.editIndex2,field:'OP'});
			var ed_minutos = $(this.grid).datagrid('getEditor', {index:this.editIndex2,field:'minutos'});
			var ed_minutos1maquinado = $(this.grid).datagrid('getEditor', {index:this.editIndex2,field:'minutos1maquinado'});
			var ed_celda = $(this.grid).datagrid('getEditor', {index:this.editIndex2,field:'celda'});
			var ed_siguiente = $(this.grid).datagrid('getEditor', {index:this.editIndex2,field:'siguiente'});
			
			if (
				ed_pieza == null || 
					ed_maquina == null || 
					ed_sig == null || 
					ed_op == null || 
					
					ed_minutos == null || 
					ed_minutos1maquinado == null || 
					ed_celda == null ||
					ed_siguiente == null 
					)
					{return true;this.editIndex2 = undefined;}
			
			pieza  = $(ed_pieza.target).combobox('getText');
			maquina  = $(ed_maquina.target).combobox('getText');
			sig  = $(ed_sig.target).numberbox('getValue');
			
			op  = $(ed_op.target).combobox('getValue');
			minutos  = $(ed_minutos.target).numberbox('getValue');
			minutos1maquinado  = $(ed_minutos1maquinado.target).numberbox('getValue');
			celda  = $(ed_celda.target).numberbox('getValue');
			siguiente  = $(ed_siguiente.target).numberbox('getValue');
			
			$(this.grid).datagrid('getRows')[this.editIndex2]['pieza'] = pieza;
			$(this.grid).datagrid('getRows')[this.editIndex2]['maquina'] = maquina;
			$(this.grid).datagrid('getRows')[this.editIndex2]['siguiente'] = siguiente;
			$(this.grid).datagrid('getRows')[this.editIndex2]['OP'] = op;
			$(this.grid).datagrid('getRows')[this.editIndex2]['minutos'] = minutos;
			$(this.grid).datagrid('getRows')[this.editIndex2]['minutos1maquinado'] = minutos1maquinado;
			$(this.grid).datagrid('getRows')[this.editIndex2]['celda'] = celda;
			$(this.grid).datagrid('getRows')[this.editIndex2]['siguiente'] = siguiente;
			
			data.push ( $(this.grid).datagrid('getRows')[this.editIndex2] );
			
				this.save(data,'pmsave');
						
				this.recargaSigGrid(grid);
				this.editIndex2 = undefined;
				$(this.grid).datagrid('endEdit', this.editIndex2);
				this.deshacerfila2();
			
		}
		
		this.add = function() {
			
		
			$(this.grid).datagrid('insertRow',{
				index:1,
				row:{
				pieza:'',
				maquina:'',
				OP:'',
				minutos:'',
				minutos1maquinado:'',
				celda:'0',
				siguiente:''
				}
			});
			
		rows = 	$(this.grid).datagrid('getRows');
		$(this.grid).datagrid('selectRow',rows.length);
		
		}
		
		this.del = function() {	
		
		var row  = $(this.grid).datagrid('getSelected');
		this.save(row,'pmdel');
			
		}
		
		this.save = function(data,url) {
			
				$.post(url,
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
	}
	
		var controlpm = new control('#<?php echo $id2 ?>'); 
		
	</script>
