
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
    
    $id = 'programacion'; // nombre de el div 
	
echo Html::a('Actualizar',"javascript:void(0)",[
        'class'=>"easyui-linkbutton",
        'data-options'=>"iconCls:'icon-reload',plain:true",
        'onclick'=>"cambia('#$id')"
    ]);


		

	
	
$this->registerJS("
    function cambia(grid){
	
		// var tmp = $('#semana1').val().split('W');
		// var tmp1 = parseInt(tmp[1]) + 1);		
		// var s1 = '';  
		// s1 = s1.concat( tmp[0].toString() ,'W', tmp1.toString()); 
							
		 var opt = $('#$id').datagrid('options');
		 opt.url = 'ctaprogd?fecha='+fecha;
		 opt.queryParams.semana = fecha;
       
    }
",$this::POS_END);

//$this->registerJsFile("/fimex/frontend/assets/js/datagrid-editors.js",['position' => $this::POS_END]);

?>



							<table id="<?php echo $id ?>" class="easyui-datagrid " style="width:100%;height:400px;"

									data-options="
										url:'ctaprogd?fecha=<?=$semana?>',
										method:'post',
										singleSelect: true,
										showFooter: true,
										
										toolbar:tb,
										queryParams: {
											semana: '<?=$semana?>'
														}
										"
								 
								toolbar: "'#tb'">
								
								<div id="tb" style="height:auto">
								<!--
								<a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-save',plain:true" onclick="accept()">Accept</a>
								<a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-undo',plain:true" onclick="reject()">Reject</a>
								<a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-search',plain:true" onclick="getChanges()">GetChanges</a>
								-->
								<a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-undo',plain:true" onclick="deshacerfila()">Deshacer fila</a>
						
					</div>
								
								<thead>
									<tr>
										
										
									   
										<th data-options="field:'maquina',width:80">maquina</th>
										<th data-options="field:'prioridad',width:50">prioridad</th>
										<th data-options="field:'pieza',width:200">pieza</th>
										<th data-options="field:'cantidad',width:50" style="font-weight:bold;">Cantidad</th>
										<th data-options="field:'buenas',width:50">Buenas</th>
										<th data-options="field:'malas',width:50">Malas</th>
										<th data-options="field:'minutos',width:50">Minutos</th>
										<th data-options="field:'horas',width:50">Horas</th>
										<th data-options="field:'t8',width:50">t8</th>
										<th data-options="field:'t9',width:50">t9</th>
										
										
								
									</tr>
								</thead>
							</table>
						
						
	
	
			
							
							
							
							
						
						
					
				
				
				
				
				
</div> <!-- panel -->


	<script type="text/javascript">
		
	
		
		
	</script>
