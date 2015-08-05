
<?php
/* @var $this yii\web\View */

use yii\bootstrap\Modal;

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\helpers\URL;
use common\models\Grid;

$url = "http://192.168.0.4:8080/birt/frameset?__report=ete_ch.rptdesign&ini=$ini&fin=$fin&area=$area";
	
?>
<h1>ETE</h1>
Fecha ini : 
<input id="ini" type="text" class="easyui-datebox" required="required" value="<?= $ini ?>">
Fecha fin : 
<input id="fin" type="text" class="easyui-datebox" required="required" value="<?= $fin ?>">
Area :
<select id="area" class="easyui-combobox" name="area" style="width:200px;">
    <option value="AC">Aceros</option>
    <option value="BR">Bronces</option>
</select>

<?php
echo Html::a('Actualizar',"javascript:void(0)",[
        'class'=>"easyui-linkbutton",
        'data-options'=>"iconCls:'icon-reload',plain:true",
        'onclick'=>"recargaPagina()"
    ]);

	//echo $url ;
	?>

<br>
<embed id= "rep" width="1300px " height="768" src="<?= $url ?>">

<script type="text/javascript">

		function recargaPagina(){
			
			var ini = $('#ini').datebox('getValue');
			var fin = $('#fin').datebox('getValue');
			var area = $('#area').combobox('getValue');
			
			window.location.href = 'etech' + "?ini=" + ini +  "&fin=" + fin +  "&area=" + area ;
			
		}

</script>