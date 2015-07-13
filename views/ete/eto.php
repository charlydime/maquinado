
<?php
/* @var $this yii\web\View */

use yii\bootstrap\Modal;

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\helpers\URL;
use common\models\Grid;


$url = "http://192.168.0.110:8080/birt/frameset?__report=eto.rptdesign&fecha=$fecha&fecha2=$fecha2&area=$area%25";
$url = htmlentities($url);
?>
Fecha ini : 
<input id="ini" type="text" class="easyui-datebox" required="required">
Fecha fin : 
<input id="fin" type="text" class="easyui-datebox" required="required">
Area :
<select id="area" class="easyui-combobox" name="area" style="width:200px;">
    <option value="MAA">Aeros</option>
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
			
			var ini = $('#ini').datebox('getValue');
			var fin = $('#fin').datebox('getValue');
			var area = $('#area').combobox('getValue');
			
			window.location.href = 'eto' + "?fecha=" + formatea_fecha(ini) +  "&fecha2=" + formatea_fecha(fin) +  "&area=" + area +"%";
			
		}
		
		function formatea_fecha(fecha){
			var date =  new Date(fecha);
			
			var y = date.getFullYear();
			var m = date.getMonth()+1;
			var d = date.getDate()+1;
			
			if (m< 10) var mes = '0'+m ; else mes = m;
			if (d< 10) var dia = '0'+d ; else dia = d;
			
			return y+''+mes+''+dia;
			
		}


</script>