
<?php
/* @var $this yii\web\View */

use yii\bootstrap\Modal;

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\helpers\URL;
use common\models\Grid;


$url = "http://192.168.0.4:8080/birt/frameset?__report=resumenturnodiario.rptdesign&FECHA=$fecha";
$url = htmlentities($url);
?>
<h1>Diario de turnos</h1>
semana  : <?= $semana ?>
<?php

echo Html::beginTag('div',['id'=>'tbDiaria']);
    echo "Ver: ".Html::tag("input","",[
        'id'=>'ini',
        'type'=>'date',
        'value'=> $fecha
    ]);

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
			
			var ini = $('#ini').value;
			
			
			window.location.href = 'resumenturnodiarioac' + "?FECHA=" + formatea_fecha(ini);
			
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