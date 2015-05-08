
<?php
/* @var $this yii\web\View */

use yii\bootstrap\Modal;

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\helpers\URL;
use common\models\Grid;
	$tb = "barraherramtm";
	$id2 = "tm";
?>

<table id="<?php echo $id2 ?>" title="Tiempo Muerto"  class="easyui-datagrid " style="width:100%;height:300px;"

        data-options="
			url:'lsttm',
			method:'post',
		    singleSelect: true,
			onClickRow:function(inx,row){ controltm.onClickRow2(inx,row); },
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
						
						<a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-add',plain:true" onclick="controltm.add()">Agregar</a>
						<a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-remove',plain:true" onclick="controltm.del()">Borrar </a>
						<a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-undo',plain:true" onclick="controltm.deshacerfila2()">Escape </a>
						<!-- <a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-search',plain:true" onclick="getChanges()">GetChanges</a>
						-->
						
						
					</div>
   
    <thead>
		<tr> 
		
		<th colspan=2> Hora</th>
		<th colspan=2></th>
		<th colspan=4></th>
		
		
		</tr>
	
        <tr>

		<th data-options="field:'inicio',width:60,editor:'textbox'">inicio</th>
		<th data-options="field:'fin',width:60,editor:'textbox'">Fin</th>
			
			<th data-options="field:'tm',width:200,
										
											
				editor:{
					type:'combobox',
					options:{
					valueField:'tm',
					textField:'obs',
					panelWidth:200,
					url:'loadtm',
					method:'get'
						}
				}
			">Tiempo muerto</th>

			
	
	
			<th data-options="field:'desc',width:300,editor:'textbox'">Observacion</th>
			<th data-options="field:'omtto',width:50,editor:'textbox'">O Mtto</th>
			<th data-options="field:'osetup',width:50,editor:'textbox'">O Setup</th>
			<th data-options="field:'herram',width:100,editor:'textbox'">herramienta</th>
			<th data-options="field:'idtm',width:50,editor:'textbox'">id</th>
			
            
        </tr>
    </thead>
</table>

<script type="text/javascript">
	
		//class control
	function control(grid){
		 this.editIndex2 = undefined;
		 this.grid = grid;
		 
		 

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
			
			var inicio  = null;
			var fin  = null;
			var tm  = null;
			var o_mtto  = null;
			var o_setup  = null;
			var herram  = null;
			
			
			
			var data = []; 
			
			var ed_inicio = $(this.grid).datagrid('getEditor', {index:this.editIndex2,field:'inicio'});
			var ed_fin = $(this.grid).datagrid('getEditor', {index:this.editIndex2,field:'fin'});
			var ed_tm = $(this.grid).datagrid('getEditor', {index:this.editIndex2,field:'tm'});
			var ed_o_mtto = $(this.grid).datagrid('getEditor', {index:this.editIndex2,field:'omtto'});
			var ed_o_setup = $(this.grid).datagrid('getEditor', {index:this.editIndex2,field:'osetup'});
			var ed_desc = $(this.grid).datagrid('getEditor', {index:this.editIndex2,field:'desc'});
			var ed_herram = $(this.grid).datagrid('getEditor', {index:this.editIndex2,field:'herram'});
			
			if (
					ed_inicio == null || 
					ed_fin == null || 
					ed_tm == null || 
					
					
					ed_o_mtto == null || 
					ed_o_setup == null || 
					ed_desc == null ||
					ed_herram == null 
					)
					{return true;this.editIndex2 = undefined;}
			
			inicio  = $(ed_inicio.target).textbox('getValue');
			fin  = $(ed_fin.target).textbox('getValue');
			tm = $(ed_tm.target).combobox('getValue');

			o_mtto  = $(ed_o_mtto.target).numberbox('getValue');
			o_setup  = $(ed_o_setup.target).numberbox('getValue');
			desc   = $(ed_desc.target).textbox('getValue');
			herram   = $(ed_desc.target).textbox('getValue');
			
			$(this.grid).datagrid('getRows')[this.editIndex2]['inicio'] = inicio;
			$(this.grid).datagrid('getRows')[this.editIndex2]['fin'] = fin;
			$(this.grid).datagrid('getRows')[this.editIndex2]['tm'] = tm;
			$(this.grid).datagrid('getRows')[this.editIndex2]['omtto'] = o_mtto;
			$(this.grid).datagrid('getRows')[this.editIndex2]['osetup'] = o_setup;
			$(this.grid).datagrid('getRows')[this.editIndex2]['desc'] = desc;
			$(this.grid).datagrid('getRows')[this.editIndex2]['herram'] = herram;
			$(this.grid).datagrid('getRows')[this.editIndex2]['ID'] = $('#id').val();
			
			data.push ( $(this.grid).datagrid('getRows')[this.editIndex2] );
			
				this.save(data,'savetm');
						
				this.recargaSigGrid(grid);
				this.editIndex2 = undefined;
				$(this.grid).datagrid('endEdit', this.editIndex2);
				this.deshacerfila2();
			
		}
		
		this.add = function() {
			
		
			$(this.grid).datagrid('insertRow',{
				index:1,
				row:{
					inicio:'',
					fin:'',
					omtto:'',
					osetup:'',
					desc:'',
					herram:''
				}
			});
			
		rows = 	$(this.grid).datagrid('getRows');
		$(this.grid).datagrid('selectRow',rows.length);
		
		}
		
		this.del = function() {	
		
		var row  = $(this.grid).datagrid('getSelected');
		this.save(row,'borratm');
			
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
	
		var controltm = new control('#<?php echo $id2 ?>'); 
		
	</script>
