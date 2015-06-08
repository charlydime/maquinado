
<?php
/* @var $this yii\web\View */

use yii\bootstrap\Modal;

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\helpers\URL;
use common\models\Grid;
	$tb = "barraherramcap";
	$id2 = "captura";
?>

 
<div id="win_cel" class="easyui-window" data-options="modal:true,closed:true,title:'configuracion de Celda',inline:true" style="width:800px;height:400px;padding:10px">
           
        
    

<table>
<tr>
<td>ID :</td>
<td>				<input id="id" class="easyui-textbox" type="text" name="id"  ></input></td>
</td>
</tr>		
<tr>
<td>Descripcion :</td>
<td>				<input id="descripcion" class="easyui-textbox" type="text" name="descripcion" value = "" ></input></td>
</td>
</tr>	
<tr>
<td>semana   </td>   <td>  <input type="radio" name="sem" value="<?=$s1 ?>" checked> <?=$s1 ?> 
    <input type="radio" name="sem" value="<?=$s2 ?>" > <?=$s2 ?> 
    <input type="radio" name="sem" value="<?=$s3 ?>" > <?=$s3 ?>  
    <input type="radio" name="sem" value="<?=$s4 ?>" > <?=$s4 ?>  </td>

</tr>	
</table>

<table id="<?php echo $id2 ?>" title="Maquinas de nueva Celda"  class="easyui-datagrid " style="width:90%;height:300px;"

        data-options="
			url:'lstcelda',
			method:'post',
		    singleSelect: true,
			onClickRow:function(inx,row){ controlcap.onClickRow2(inx,row); },

			toolbar:tb<?php echo $tb ?>,
			queryParams: {
				id: getid 
				},
			
			tools:[{
                    iconCls:'icon-reload',
                    handler:function(){
                        $('#<?php echo $id2 ?>').datagrid('reload');
                    }
                }]
		"
   >

  

   <div id="tb<?php echo $tb ?>"  style="height:auto">
						
						<a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-add',plain:true" onclick="controlcap.add()">Agregar</a>
						<a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-remove',plain:true" onclick="controlcap.del()">Borrar </a>
						<a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-undo',plain:true" onclick="controlcap.deshacerfila2()">Escape </a>
						<a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-save',plain:true" onclick="controlcap.guarda()">guardar </a>
						<!-- <a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-search',plain:true" onclick="getChanges()">GetChanges</a>
						-->
						
						
					</div>
   
    <thead>

	
        <tr>

			<th data-options="
										field:'Hold',
										width:50,
										align:'center',
										formatter:cellStyler,
										sortable:true,
										editor:{type:'checkbox',options:{on:'1',off:'0'}}
			">Hold</th>
			
			<th data-options="field:'maquina',width:50,
										
											
				editor:{
					type:'combobox',
					options:{
					valueField:'id',
					textField:'Descripcion',
					panelWidth:300,
					url:'loadmaquina',
					method:'get'
						}
				}
			">maquina</th>

		
			<th data-options="field:'clave',width:100">clave</th>
			<th data-options="field:'Descripcion',width:300">desc</th>
			<th data-options="field:'razon',editor:'numberbox',width:300">razon</th>
			
			
			
            
        </tr>
    </thead>
</table>
</div>
<script type="text/javascript">
	
		//class control
	function control(grid){
		 this.editIndex2 = undefined;
		 this.grid = grid;
		 this.s = 'salvacelda'
		 this.rows = [];
		 
		 
		

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
							var col = this.editIndex2;
								//guarda(); 
								$(this.grid).datagrid('selectRow',this.editIndex2+1).trigger('click');
								}
						// flecha arriba
                		if (e.keyCode === 38) {alert("arriba")} 
						// f5
                		//if (e.keyCode === 116) {reloadcta3(true);} 
					 }
		
		this.guarda = function(){
				var data=[];
				desc = $('#descripcion').val();
				grid = $(this.grid).datagrid('getRows');
				idcel = getid(); 
				var data = { descripcion : desc,
							 maquinas : grid,
							id: idcel};
				
				this.save(data,this.s);
				this.recargaSigGrid(grid);
				this.editIndex2 = undefined;
				$(this.grid).datagrid('endEdit', this.editIndex2);
				this.deshacerfila2();
			
		}
		
		this.add = function() {
			
		rows = 	$(this.grid).datagrid('getRows');
			$(this.grid).datagrid('insertRow',{
				index:rows.length,
				row:{
				
				maquina:''
				}
			});
			
		rows = 	$(this.grid).datagrid('getRows');
		$(this.grid).datagrid('selectRow',rows.length-1);
		
		}
		
		this.del = function() {	
		var sel = $(this.grid).datagrid('getSelected');
					var row=  $(this.grid).datagrid('getRowIndex',sel);
		
		var row  = $(this.grid).datagrid('deleteRow',row);
		
			
		}
		
		this.save = function(data,url) {
			
				$.post(url,
							{Data: JSON.stringify(data)},
							function(data,status){
								if(status == 'success' ){
									$(grid).datagrid('load');
									$('#id').textbox('setValue','un id');
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
				
				var maquina  = null;
				var ed_maquina = $(this.grid).datagrid('getEditor', {index:this.editIndex2,field:'maquina'});
				if ( ed_maquina  == null )
					{return true;this.editIndex2 = undefined;}
				
				maquina = $(ed_maquina.target).combobox('getValue');
				$(this.grid).datagrid('getRows')[this.editIndex2]['maquina'] = maquina;
				
				$(this.grid).datagrid('endEdit', this.editIndex2);
				
				
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
		
		this.es_celda = function ($maquina){
			
			if (maquina.length > 7) 
				return true
			else 
				return false;
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
	
		var controlcap = new control('#<?php echo $id2 ?>'); 
		
		function getid(){
			 return $('#id').val(); 
			
			
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
		
	</script>