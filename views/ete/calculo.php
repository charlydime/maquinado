
<?php
/* @var $this yii\web\View */

use yii\bootstrap\Modal;

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\helpers\URL;
use common\models\Grid;

$url = "http://192.168.0.110:8080/birt/frameset?__report=ete.rptdesign&ini=$ini&fin=$fin&area=$area";
	
?>
Fecha ini : 
<input id="ini" type="text" class="easyui-datebox" required="required">
Fecha fin : 
<input id="fin" type="text" class="easyui-datebox" required="required">
Area :
<select id="area" class="easyui-combobox" name="area" style="width:200px;">
    <option value="AC">Aeros</option>
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
<embed id= "rep" width="1000" height="768" src="<?= $url ?>">

<script type="text/javascript">

		function recargaPagina(){
			
			var ini = $('#ini').datebox('getValue');
			var fin = $('#fin').datebox('getValue');
			var area = $('#area').combobox('getValue');
			
			window.location.href = 'ete' + "?ini=" + ini +  "&fin=" + fin +  "&area=" + area ;
			
		}

</script>