<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use common\models\Grid;
use yii\helpers\URL;

//var_dump($model->getMarcas());exit;
$id = 'productos';
echo Html::beginTag('div',['id'=>'tbProductos']);
    echo "Seleccionar Cliente:".Html::activeDropDownList($model, 'IdMarca', ArrayHelper::map($marcas,'IdMarca','Marca'));
    echo Html::a('Actualizar',"javascript:void(0)",[
        'class'=>"easyui-linkbutton",
        'data-options'=>"iconCls:'icon-reload',plain:true",
        'onclick'=>"getChanges('#$id')"
    ]);
echo Html::endTag('div');

$grid = new Grid([
    'id'=>$id,
    'style'=>'width:100%;height:200px',
    'onClickRow'=> "
        function (index,row){
            var div = $('#parametros');
            var tt = div.tabs('tabs');
            
            $.each(tt,function(index,value){
                div.tabs('enableTab',index);
            });
            
            var tab = div.tabs('getTab','Moldeo');
            div.tabs('update',{
                tab:tab,
                options:{
                    href:'/Fimex/productos/moldeo?id='+row.IdProductoCasting,
                }
            });
            
            var tab = div.tabs('getTab','Almas');
            div.tabs('update',{
                tab:tab,
                options:{
                    href:'/Fimex/productos/almas?id='+row.IdProductoCasting,
                }
            });
        }
    ",
    'onLoadSuccess'=>"
        function(data){
            var div = $('#parametros');
            div.tabs({
                border:false,
                tabPosition:'left',
            });
            var title = ['Moldeo','Almas','Camisas','Filtros','Maquinado','Tratamientos termicos'];
            
            $.each(title,function(index,value){
                if (div.tabs('exists', value)==false){
                    div.tabs('add',{
                        title: value,
                    });
                }
                div.tabs('disableTab',index);
                div.tabs('select','Moldeo');
            });
        }
    ",
    'toolbar'=> "'#tbProductos'",
    'dataOptions' => [
        
        'url'=> '/Fimex/productos/data_productos',
        'singleSelect'=> true,
        'method'=> 'post',
        'remoteSort'=>false,
        'multiSort'=>true,
    ],
    'columns' => [
        [
            'IdProductoCasting'=>[
                'label'=>'IdCasting',
                'data-options'=>[]
            ],
            'Identificacion'=>[
                'label'=>'Producto',
                'data-options'=>[]
            ],
            'ProductoCasting'=>[
                'label'=>'Casting',
                'data-options'=>[]
            ],
            'Descripcion'=>[
                'label'=>'Descripcion',
                'data-options'=>[]
            ],
            'ProductoCasting'=>[
                'label'=>'Casting',
                'data-options'=>[]
            ],
            'Aleacion'=>[
                'data-options'=>[]
            ]
        ]
    ]
]);

$grid->display();

$this->registerJS("
    $('#vmarcas-idmarca').change(function(){
        $('#$id').datagrid('load',{
            marca:$(this).val()
        });
        $('#$id').datagrid('enableFilter');
    });
");
$this->registerJS("
    var ProductosIndex = undefined;
    
    function getChanges(grid){
        $(grid).datagrid('load');
    }
    function submitForm(){
        $('#param-moldeo').form('submit',{
            success:function(data){
                if(data == true){
                    $.messager.alert('Info', 'Datos actualizados', 'info');
                }
                return false;
            }
        });
    }
",$this::POS_END);
?>
<hr />
<h2>Parametros</h2>
<div id="parametros" style="width:100%;height:450px">
</div>