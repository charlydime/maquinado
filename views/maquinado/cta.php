
<?php
/* @var $this yii\web\View */

use yii\bootstrap\Modal;

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\helpers\URL;
use common\models\Grid;


//var_dump($Paroen);


    
    $id = 'programacion_diaria'; // nombre de el div 



$this->registerJS("
function cambia(grid){
        $(grid).datagrid('reload');
    }
");


?>
<table class="easyui-datagrid datagrid-f" style="width:900px;height:auto"
        data-options="url:'ctad',
					  rowStyler:rowStyler"
     id="<?php echo $id ?>"
    toolbar: "'#tbDiaria'">
    <thead>
        <tr>
            <th data-options="field:'PRODUCTO',width:200">Parte</th>
            <th data-options="field:'existencia_cta',width:100">Product. CTA</th>
            <th data-options="field:'Maquina',width:100">maquina</th>
            <th data-options="field:'min_setup',width:100">SetUp (min)</th>
            <th data-options="field:'min_prod',width:100">tiempo prod (min)</th>
            <th data-options="field:'horaprod',width:100">tiempo prod (h)</th>
            <th data-options="field:'turno8',width:100">en turno 8h</th>
            <th data-options="field:'turno9',width:100">en turno 9h</th>
            
            
        </tr>
    </thead>
</table>

<script>
	function rowStyler(index,row){
		
		if(row.Maquina == '' || row.Maquina == null){
			return 'background-color:#FFCC00;';
		}
	}

</script>
