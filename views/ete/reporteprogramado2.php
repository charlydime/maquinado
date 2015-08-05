<?php
/* @var $this yii\web\View */

use yii\bootstrap\Modal;

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\helpers\URL;
use common\models\Grid;

$url = "http://192.168.0.4:8080/birt/frameset?__report=maquinado_semana2.rptdesign&ini=$ini&fin=$fin&grupo=$grupo";
//$url = "http://192.168.0.4:8080/birt/frameset?__report=maquinado_semana2.rptdesign";
?>

<h1>Programacion Semana Bronce</h1>

Fecha ini : 
<input id="ini" type="text" class="easyui-datebox" required="required" value = "<?=  $ini   ?>">
Fecha fin : 
<input id="fin" type="text" class="easyui-datebox" required="required" value = "<?=  $fin   ?>">
Agrupar por :
<select id="grupo" class="easyui-combobox" name="grupo" style="width:200px;">
    <option value="nomina_1">Operador</option>
    <option value="maquina">Maquina</option>
    <option value="pieza">Pieza</option>
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
			var grupo = $('#grupo').combobox('getValue');
			
			window.location.href = 'reporteprogramado2' + "?ini=" +  formatea_fecha(ini) +  "&fin=" +  formatea_fecha(fin) +  "&grupo =" + grupo ;
			
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