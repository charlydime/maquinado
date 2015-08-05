
<?php
/* @var $this yii\web\View */

use yii\bootstrap\Modal;

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\helpers\URL;
use common\models\Grid;

$per = '%25';
$url = "http://192.168.0.4:8080/birt/frameset?__report=eto_m.rptdesign&ini=$fecha&fin=$fecha2&area=$area%25";
$url = htmlentities($url);
$per = htmlentities($per);
?>
Fecha ini : 
<input id="fecha" type="text" class="easyui-datebox" required="required" value="formatea_fecha($fecha)">
Fecha fin : 
<input id="fecha2" type="text" class="easyui-datebox" required="required" value="formatea_fecha($fecha2)">
Area :
<select id="area" class="easyui-combobox" name="area" style="width:200px;">
    <option value="MAA">Aceros</option>
    <option value="MAB">Bronces</option>
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
<embed id= "rep" width="100%" height="768" src="<?= $url ?>">

<script type="text/javascript">

		function recargaPagina(){
			
			var ini = $('#fecha').datebox('getValue');
			var fin = $('#fecha2').datebox('getValue');
			var area = $('#area').combobox('getValue');
			
			window.location.href = 'etom' + "?fecha=" + formatea_fecha(ini) +  "&fecha2=" + formatea_fecha(fin) +  "&area=" + area ;
			
		}
		
		function formatea_fecha(fecha){
			var date =  new Date(fecha);
			
			var y = date.getFullYear();
			var m = date.getMonth()+1;
			var d = date.getDate();
			
			if (m< 10) var mes = '0'+m ; else mes = m;
			if (d< 10) var dia = '0'+d ; else dia = d;
			
			return y+''+mes+''+dia;
			
		}


</script>