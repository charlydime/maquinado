<?php

use yii\helpers\Html;
use yii\helpers\URL;
use common\models\Grid;

$id = 'pedidos';

echo Html::beginTag('div',['id'=>'tbPedidos']);

    echo Html::a('Agregar Pedidos',"javascript:void(0)",[
        'class'=>"easyui-linkbutton",
        'data-options'=>"iconCls:'icon-add',plain:true",
        'onclick'=>"addPedidos('#$id')"
    ]);
echo Html::endTag('div');

$this->registerJS("
    $('#$id').datagrid('enableFilter',[{
        field:'Marca',
        type:'combobox',
        options:{
            panelHeight:'150',
            panelWidth:'250',
            url:'/Fimex/programacion/marcas',
            onChange:function(value){
                if (value == ''){
                    $('#$id').datagrid('removeFilterRule', 'Marca');
                } else {
                    $('#$id').datagrid('addFilterRule', {
                        field: 'Marca',
                        op: 'equal',
                        value: value
                    });
                }
                $('#$id').datagrid('doFilter');
            }
        }
    }]);
");

$this->registerJS("
    
    var PedidosIndex = undefined;
    
    function addPedidos(grid){
        var data = $(grid).datagrid('getChecked');
        $.get('".URL::to('/Fimex/programacion/save_pedidos')."',
                {Data: JSON.stringify(data)},
                function(data,status){
                    console.log(data);
                    if(status == 'success' ){
                        $(grid).datagrid('load');
                        $('#programacion_semanal').datagrid('load');
                    }else{
                        alert('Error al guardar los datos');
                    }
                }
            );
    }
",$this::POS_END);
?>
<table id="<?= $id ?>" class="easyui-datagrid datagrid-f" title="Agregar Pedidos" style="height:400px;" data-options="
    url:'/Fimex/programacion/pedidos',
    singleSelect:false,
    method:'post',
    collapsible:true,
    remoteSort:false,
    multiSort:true,
    loadMsg: 'Cargando datos',
    toolbar: '#tbPedidos',
">
    <thead>
        <tr>
            <th data-options="field:'ok',checkbox:true">ok</th>
            <th data-options="field:'IdPedido',width:50,hidden:true">Id</th>
            <th data-options="field:'Identificacion',sortable:true,width:200">Identificacion</th>
            <th data-options="field:'ProductoCasting',sortable:true,width:200">Casting</th>
            <th data-options="field:'Producto',sortable:true,width:250">Producto</th>
            <th data-options="field:'FechaEmbarque',sortable:true,width:100">Embarque</th>
            <th data-options="field:'Aleacion',sortable:true,hidden:true,width:100">Aleacion</th>
            <th data-options="field:'Marca',sortable:true,width:100">Cliente</th>
            <th data-options="field:'Presentacion',sortable:true,hidden:true,width:100">Presentacion</th>
            <th data-options="field:'Cantidad',sortable:true,width:65">Cantidad</th>
        </tr>
    </thead>
</table>