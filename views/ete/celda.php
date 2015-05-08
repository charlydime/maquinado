
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

<table id="<?php echo $id2 ?>" title="Piezas  Maquinadas"  class="easyui-datagrid " style="width:100%;height:300px;"

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
						<!-- <a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-search',plain:true" onclick="getChanges()">GetChanges</a>
						-->
						
						
					</div>
   
    <thead>
		<tr> 
		<th colspan=2> Hora</th>
		<th colspan=3></th>
		<th colspan=2>Rechazo</th>
		<th colspan=2></th>
		
		</tr>
	
        <tr>

		<th data-options="field:'inicio',width:60,editor:'textbox'">inicio</th>
		<th data-options="field:'fin',width:60,editor:'textbox'">Fin</th>
			
			<th data-options="field:'maquina',width:200,
										
											
				editor:{
					type:'combobox',
					options:{
					valueField:'maq',
					textField:'descripcion',
					panelWidth:200,
					url:'loadmaq',
					method:'get'
						}
				}
			">maquina</th>

		
			<th data-options="field:'timestamp',width:300,editor:'textbox'">fecha</th>
			<th data-options="field:'id',width:50,editor:'numberbox'">id</th>
			<th data-options="field:'celda',width:50,editor:'numberbox'">celda</th>
			
            
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
			
			var inicio  = null;
			var fin  = null;
			var parte  = null;
			var op  = null;
			var maq  = null;
			var r_maq  = null;
			var r_fun  = null;
			var desc  = null;
			var data = []; 
			
			var ed_inicio = $(this.grid).datagrid('getEditor', {index:this.editIndex2,field:'inicio'});
			var ed_fin = $(this.grid).datagrid('getEditor', {index:this.editIndex2,field:'fin'});
			var ed_parte = $(this.grid).datagrid('getEditor', {index:this.editIndex2,field:'parte'});
			var ed_op = $(this.grid).datagrid('getEditor', {index:this.editIndex2,field:'op'});
			var ed_maq = $(this.grid).datagrid('getEditor', {index:this.editIndex2,field:'maq'});
			var ed_r_maq = $(this.grid).datagrid('getEditor', {index:this.editIndex2,field:'RMaq'});
			var ed_r_fun = $(this.grid).datagrid('getEditor', {index:this.editIndex2,field:'RFun'});
			var ed_desc = $(this.grid).datagrid('getEditor', {index:this.editIndex2,field:'desc'});
			
			if (
				ed_inicio == null || 
					ed_fin == null || 
					ed_parte == null || 
					ed_op == null || 
					
					ed_maq == null || 
					ed_r_maq == null || 
					ed_r_fun == null ||
					ed_desc == null 
					)
					{return true;this.editIndex2 = undefined;}
			
			inicio  = $(ed_inicio.target).textbox('getValue');
			fin  = $(ed_fin.target).textbox('getValue');
			parte  = $(ed_parte.target).combobox('getValue');
			op  = $(ed_op.target).combobox('getValue');
			maq  = $(ed_maq.target).numberbox('getValue');
			r_maq  = $(ed_r_maq.target).numberbox('getValue');
			r_fun  = $(ed_r_fun.target).numberbox('getValue');
			desc   = $(ed_desc.target).textbox('getValue');
			
			$(this.grid).datagrid('getRows')[this.editIndex2]['inicio'] = inicio;
			$(this.grid).datagrid('getRows')[this.editIndex2]['fin'] = fin;
			$(this.grid).datagrid('getRows')[this.editIndex2]['parte'] = parte;
			$(this.grid).datagrid('getRows')[this.editIndex2]['op'] = op;
			$(this.grid).datagrid('getRows')[this.editIndex2]['maq'] = maq;
			$(this.grid).datagrid('getRows')[this.editIndex2]['RMaq'] = r_maq;
			$(this.grid).datagrid('getRows')[this.editIndex2]['RFun'] = r_fun;
			$(this.grid).datagrid('getRows')[this.editIndex2]['desc'] = desc;
			$(this.grid).datagrid('getRows')[this.editIndex2]['ID'] = $('#id').val();
			$(this.grid).datagrid('getRows')[this.editIndex2]['fecha'] = $('#fecha').val();
			$(this.grid).datagrid('getRows')[this.editIndex2]['operador'] = $('#operador').val();
			
			data.push ( $(this.grid).datagrid('getRows')[this.editIndex2] );
			
				this.save(data,'savecap');
						
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
				parte:'',
				op:'',
				maq:'',
				RMaq:'0',
				RFun:'',
				desc:''
				}
			});
			
		rows = 	$(this.grid).datagrid('getRows');
		$(this.grid).datagrid('selectRow',rows.length-1);
		
		}
		
		this.del = function() {	
		
		var row  = $(this.grid).datagrid('getSelected');
		this.save(row,'borracap');
			
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
	
		var controlcap = new control('#<?php echo $id2 ?>'); 
		
		
		
	</script>
