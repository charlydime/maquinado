
<?php
/* @var $this yii\web\View */

use yii\bootstrap\Modal;

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\helpers\URL;
use common\models\Grid;

$per = '%25';
$url = "http://192.168.0.4:8080/birt/frameset?__report=EtoPorSemana.rptdesign&FechaInicio=$fecha&FechaFinal=$fecha2&Area=$area";
$url = htmlentities($url);
$per = htmlentities($per);
?>
Fecha ini : 
<input id="ini" type="text" class="easyui-datebox" required="required" value="formatea_fecha($fecha)">
Fecha fin : 
<input id="fin" type="text" class="easyui-datebox" required="required" value="formatea_fecha($fecha2)">
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
<embed id= "rep" width="100%" height="768" src="<?= $url ?>">

<script type="text/javascript">

		function recargaPagina(){
			
			var ini = $('#ini').datebox('getValue');
			var fin = $('#fin').datebox('getValue');
			var area = $('#area').combobox('getValue');
			
			window.location.href = 'etoa' + "?fecha=" + formatea_fecha(ini) +  "&fecha2=" + formatea_fecha(fin) +  "&Area=" + area ;
			
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