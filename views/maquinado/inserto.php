
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

<table id="<?php echo $id2 ?>" title="Insertos"  class="easyui-datagrid " style="width:100%;height:500px;"

        data-options="
			url:'lstinserto',
			method:'post',
		    singleSelect: true,
			onClickRow:function(inx,row){ controlpm.onClickRow2(inx,row); },
			
			view:groupview,
				groupField:'Parte',
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
						<!-- <a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-search',plain:true" onclick="getChanges()">GetChanges</a>
						-->
						
						
					</div>
   
    <thead>
		
	
        <tr>
 
		
			
			
			
			<th data-options="field:'Area',width:200,
										
											
				editor:{
					type:'combobox',
					options:{
					valueField:'Area',
					textField:'Area',
					panelWidth:300,
					url:'insertoarea',
					method:'get',
						}
				}
			">area</th>
			
			<th data-options="field:'Parte',width:200,
										
											
				editor:{
					type:'combobox',
					options:{
					valueField:'PRODUCTO',
					textField:'PRODUCTO',
					panelWidth:300,
					url:'insertoparte',
					method:'get',
						}
				}
			">Parte</th>
			
			<th data-options="field:'Herramienta',width:200,
										
											
				editor:{
					type:'combobox',
					options:{
					valueField:'PRODUCTO',
					textField:'PRODUCTO',
					panelWidth:300,
					url:'insertoherr',
					method:'get',
						}
				}
			">Herramienta</th>
			
			<th data-options="field:'inserto',width:200,
										
											
				editor:{
					type:'combobox',
					options:{
					valueField:'PRODUCTO',
					textField:'PRODUCTO',
					panelWidth:300,
					url:'insertoins',
					method:'get',
						}
				}
			">Inserto</th>
			
			
			
			<th data-options="field:'insxherr',width:60,editor:'numberbox'">Insertos</th>
			<th data-options="field:'filos',width:60,editor:'numberbox'">Filos</th>
			
			
						

			
            
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
			
			var area  = null;
			var parte   = null;
			var herramienta  = null;
			var inserto  = null;
			var insxherr  = null;
			var filos  = null;
			
			var data = []; 
			
			var ed_area = $(this.grid).datagrid('getEditor', {index:this.editIndex2,field:'Area'});
			var ed_parte = $(this.grid).datagrid('getEditor', {index:this.editIndex2,field:'Parte'});
			var ed_herramienta = $(this.grid).datagrid('getEditor', {index:this.editIndex2,field:'Herramienta'});
			var ed_inserto = $(this.grid).datagrid('getEditor', {index:this.editIndex2,field:'Inserto'});
			var ed_insxherr = $(this.grid).datagrid('getEditor', {index:this.editIndex2,field:'insxherr'});
			var ed_filos = $(this.grid).datagrid('getEditor', {index:this.editIndex2,field:'filos'});
			
			if (
				ed_area == null || 
					ed_parte == null || 
					ed_herramienta == null || 
					ed_inserto == null || 
					insxherr == null || 
					filos == null 
					)
					{return true;this.editIndex2 = undefined;}
			
			area  = $(ed_area.target).combobox('getText');
			parte  = $(ed_parte.target).combobox('getText');
			herramienta  = $(ed_herramienta.target).numberbox('getValue');
			inserto  =  $(ed_inserto.target).combobox('getValue');
			insxherr  = $(ed_insxherr.target).numberbox('getValue');
			filos  =    $(ed_filos.target).numberbox('getValue');
			
			
			$(this.grid).datagrid('getRows')[this.editIndex2]['Area'] = area;
			$(this.grid).datagrid('getRows')[this.editIndex2]['Parte'] = parte;
			$(this.grid).datagrid('getRows')[this.editIndex2]['Herramienta'] = herramienta;
			$(this.grid).datagrid('getRows')[this.editIndex2]['Inserto'] = inserto;
			$(this.grid).datagrid('getRows')[this.editIndex2]['insxherr'] = insxherr;
			$(this.grid).datagrid('getRows')[this.editIndex2]['filos'] = filos;
			
			
			data.push ( $(this.grid).datagrid('getRows')[this.editIndex2] );
			
				this.save(data,'insertosave');
						
				this.recargaSigGrid(grid);
				this.editIndex2 = undefined;
				$(this.grid).datagrid('endEdit', this.editIndex2);
				this.deshacerfila2();
			
		}
		
		this.add = function() {
			
		
			$(this.grid).datagrid('insertRow',{
				index:1,
				row:{
				Area:'',
				Parte:'',
				Herramienta:'',
				Inserto:'',
				insxherr:'0',
				filos:'0',
				
				}
			});
			
		rows = 	$(this.grid).datagrid('getRows');
		$(this.grid).datagrid('selectRow',rows.length);
		
		}
		
		this.del = function() {	
		
		var row  = $(this.grid).datagrid('getSelected');
		this.save(row,'insertodel');
			
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
