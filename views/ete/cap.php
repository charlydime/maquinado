
<?php
/* @var $this yii\web\View */

use yii\bootstrap\Modal;

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\helpers\URL;
use common\models\Grid;
	$tb = "barraherram";
	$id2 = "cap";
	
?>

<div class "easyui-panel" title"Reposrte de Maquinado" style="width:100%;height:500px;">

	<form id="ff" class="easyui-form" method="post" data-options="novalidate:true">
				
				ID:
				<input id="id" class="easyui-textbox" type="text" name="id"  ></input></td>
				<br/><br/>
				Maquina:
				 <input id = 'maquina'class="easyui-combobox" style="width:250px" data-options="
                url: 'loadmaquina?fecha='+$('#fecha').val()+'&op='+$('#operador').val(),
                method:'get',
                valueField: 'clave',
				onSelect:setmaquina,
                textField: 'Descripcion'
				"> 
			
				Operador:
				<input id="operador" class="easyui-textbox" type="text" name="operador" value = "<?php echo $usuario;?>" disabled></input></td>
				
				Fecha:
				<input id="fecha" class="easyui-textbox" type="text" name="fecha" value = "<?php echo $fecha;?>" disabled></input>
				
				<?php 
				
				echo Html::a('Nueva captura',"javascript:void(0)",[
					'class'=>"easyui-linkbutton",
					'data-options'=>"iconCls:'icon-reload',plain:true",
					'onclick'=>"recargaPagina()"
				]);
				
				?>
				
				
				<br/>
				hora inicio:
				<input id="hini" class="easyui-textbox" type="text" name="hini" value = "7:00"></input>

				hora fin:
				<input id="hfin" class="easyui-textbox" type="text" name="hfin" value = "17:00"></input>
	

				
				<div id = 'resultado'><div>
	</form>
	
	
				<?= $this->render('captura',[]);?>
				<?= $this->render('tm',[]);?>
	

</div>
<script type="text/javascript">
	var mensaje = ''	
    function setmaquina(record){
		var hini =$('#hini').val();
		var hfin =$('#hfin').val();
		
		if (hini == '' || hfin == ''){
			alert("debe capturar hora inicio fin en formato 24 horas ");exit;
		}
		
		var data= {
		  operador : $('#operador').val(),
		  fecha : $('#fecha').val(),
		  id: $('#id').val(),
		  hini: $('#hini').val(),
		  hfin: $('#hfin').val(),
		  maquina: record.id
		};
		
		// $.post('nuevoete',
							// {Data: JSON.stringify(data)},
							// function(data,status){
								// if(status == 'success' ){
									
									
									// console.log(data);
									
								// }else{
									// reject('#$id');
									// alert('Error al guardar los datos');
								// }
							// }
						// );
			
		$.ajax({
                data:  data,
                url:   'nuevoete',
                type:  'post',
                beforeSend: function () {
                        // $("#resultado").html("Creando registro");
                },
                success:  function (response) {
                        response.replace(/\"/g, "");
						console.log(response);
						if(response == 0 )//ya existe
						{
							 mensaje = "no se puede capturar de nuevo al operador "+$('#operador').val()+" en esa maquina/celda" ;
						}else{
							mensaje = "Puede iniciar su captura de la maquina/celda ";
						}
						alert(mensaje);
						$('#id').textbox('setValue',response);
				},
				error:  function (response) {
                        
						console.log(response);
						alert("MAL");
                }
        });

		
		
		
	}
	
	function getid(){
			 return $('#id').val(); 
			
			
		}
		
	function recargaPagina(){
			
			
			window.location.href = 'captura' ;
			
		}
	</script>
