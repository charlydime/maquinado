
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
        'value'=>$semana
    ]);
    
    $id = 'programacion_diaria'; // nombre de el div 
echo Html::a('Actualizar',"javascript:void(0)",[
        'class'=>"easyui-linkbutton",
        'data-options'=>"iconCls:'icon-reload',plain:true",
        'onclick'=>"cambia('#$id')"
    ]);

$this->registerJS("
    function cambia(grid){
 
        $(grid).datagrid('reload');
       
    }
",$this::POS_END);



?>

<script type="text/javascript">

</script>


<table class="easyui-datagrid datagrid-f" style="width:500px;height:250px"
        data-options="url:'ctad?fecha=<?=$semana?>',"
     id="<?php echo $id ?>"
    toolbar: "'#tbDiaria'">
    <thead>
        <tr>
            <th data-options="field:'producto',width:200">Parte</th>
            <th data-options="field:'sem1',width:100">semana1</th>
            <th data-options="field:'sem2',width:100">semana2</th>
            <th data-options="field:'sem3',width:100">semana3</th>
            
        </tr>
    </thead>
</table>
