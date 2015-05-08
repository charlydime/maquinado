
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
				Maquina:
				 <input class="easyui-combobox" style="width:250px" data-options="
                url: 'loadmaquina',
                method:'get',
                valueField: 'clave',
				onSelect:setmaquina,
                textField: 'Descripcion'
            "> 
			
				ID:
				<input id="id" class="easyui-textbox" type="text" name="id"  ></input></td>
		
			
				Operador:
				<input id="operador" class="easyui-textbox" type="text" name="operador" value = "<?php echo $usuario;?>" ></input></td>
			
			
				Fecha:
				<input id="fecha" class="easyui-textbox" type="text" name="fecha" value = "<?php echo $fecha;?>"></input>

				
				<div id = 'resultado'><div>
	</form>
	
	
				<?= $this->render('captura',[]);?>
				<?= $this->render('tm',[]);?>
	

</div>
<script type="text/javascript">
	
    function setmaquina(record){
		
		
		var data= {
		  operador : $('#operador').val(),
		  fecha : $('#fecha').val(),
		  id: $('#id').val()
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
						$('#id').textbox('setValue',response);
				},
				error:  function (response) {
                        
						console.log(response);
						alert("MAL");
                }
        });

		
		
		alert("Puede iniciar su captura de la maquina ");
	}
	
	function getid(){
			 return $('#id').val(); 
			
			
		}
	</script>
