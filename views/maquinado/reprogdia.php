<?php
/* @var $this yii\web\View */

use yii\bootstrap\Modal;

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\helpers\URL;
use common\models\Grid;

?>

 
<div id="win_rep" class="easyui-window" data-options="modal:true,closed:true,title:'Reprogramacion de restantes del dia',inline:true" style="width:500px;height:250px;padding:10px">
           
<form id="myForm">

Origen:  
<table>
<tr><td>
	<input type="radio" name="do" value="lun" checked>lun <?=$lun ?> 
    <input type="radio" name="do" value="mar" >mar <?=$mar ?> 
    <input type="radio" name="do" value="mie" >mie <?=$mie ?>  
</td></tr>
<tr><td>
    <input type="radio" name="do" value="jue" >jue <?=$jue ?>  
    <input type="radio" name="do" value="vie" >vie <?=$vie ?>  
    <input type="radio" name="do" value="sab" >sab <?=$sab ?>  
    
</td></tr>
<tr><td>
<input type="radio" name="do" value="dom" >dom <?=$dom ?>  
</td></tr>
</table>

Destino:
<table>
<tr><td>
	<input type="radio" name="dd" value="<?=$lun ?>" checked>lun <?=$lun ?> 
    <input type="radio" name="dd" value="<?=$mar ?>" >mar <?=$mar ?> 
    <input type="radio" name="dd" value="<?=$mie ?>" >mie <?=$mie ?>  
</td></tr>
<tr><td>
    <input type="radio" name="dd" value="<?=$jue ?>" >jue <?=$jue ?>  
    <input type="radio" name="dd" value="<?=$vie ?>" >vie <?=$vie ?>  
    <input type="radio" name="dd" value="<?=$sab ?>" >sab <?=$sab ?>  
    
</td></tr>
<tr><td>
<input type="radio" name="dd" value="<?=$dom ?>" >dom <?=$dom ?>  
</td></tr>
</table>

</form>

<a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-undo',plain:true" onclick="reprograma2()">Reprograma </a>
</div>

<script type="text/javascript">

 function reprograma2(){
					dde = $('input[name="dd"]:checked', '#myForm').val()
					dor = $('input[name="do"]:checked', '#myForm').val()
					g = $('#<?=$grid ?>').datagrid('getRows');
					var data = [];
					var datalun = [];
					var datamar = [];
					var datamie = [];
					var datajue = [];
					var datavie = [];
					var datasab = [];
					var datadom = [];
					
					var r = 0;
					
					for(var i= 0; i <= g.length-1; i++){
					  r= g[i];
					  if (  (r.lun_prg - r.hechaslun) > 0 )	datalun.push(r);
					  if (  (r.mar_prg - r.hechasmar) > 0 )	datamar.push(r);
					  if (  (r.mie_prg - r.hechasmie) > 0 )	datamie.push(r);
					  if (  (r.jue_prg - r.hechasjue) > 0 )	datajue.push(r);
					  if (  (r.vie_prg - r.hechasvie) > 0 )	datavie.push(r);
					  if (  (r.sab_prg - r.hechassab) > 0 )	datasab.push(r);
					  if (  (r.dom_prg - r.hechasdom) > 0 )	datadom.push(r);	  
					  
					}
					
					
					switch(dor) {
						case 'lun':
							data = datalun
							break;
						case 'mar':
							data = datamar
							break;
						case 'mie':
							data = datamie
							break;
						case 'jue':
							data = datajue
							break;
						case 'vie':
							data = datavie
							break;
						case 'sab':
							data = datasab
							break;
						case 'dom':
							data = datadom
							break;
						
					}
					
					
					$.post('reprogdia',
							{Data: JSON.stringify(data),
								dde:dde,
								dor:dor,
								semana: <?=$sem ?>
								},
							function(data,status){
								if(status == 'success' ){
										alert("reprogramado");
										 console.log(data);
									// $var = $(grid).datagrid('getChanges');
								}else{
									alert('Error al guardar los datos');
								}
							}
					);
					
					
		}

</script>