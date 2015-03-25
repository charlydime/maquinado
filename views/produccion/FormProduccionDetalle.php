<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use\common\models\Grid;

$id = "detalle";

echo Html::beginTag('div',['id'=>'tbProduccion']);

    echo Html::a('Agregar',"javascript:void(0)",[
        'id'=>'add',
        'class'=>"easyui-linkbutton",
        'data-options'=>"iconCls:'icon-add',plain:true",
        'onclick'=>"append('$id')"
    ]);
    Html::a('Guardar',"javascript:void(0)",[
        'id'=>'save',
        'class'=>"easyui-linkbutton",
        'data-options'=>"iconCls:'icon-save',plain:true",
        'onclick'=>"accept('$id')"
    ]);
    echo Html::a('Actualizar',"javascript:void(0)",[
        'id'=>'refresh',
        'class'=>"easyui-linkbutton",
        'data-options'=>"iconCls:'icon-reload',plain:true",
        'onclick'=>"getChanges('$id')"
    ]);
echo Html::endTag('div');

$this->registerJS("
    $('#$id').datagrid({
        singleSelect:true,
        method:'post',
        remoteSort:false,
        multiSort:true,
        striped:true,
        queryParams:{
            produccion:$('#IdProduccion').val(),
        },
        url:'/Fimex/produccion/detalle?produccion='+$('#IdProduccion').val(),
        loadMsg: 'Cargando datos',
        onLoadSuccess:function(data){
        },
        onClickRow: function(index,row){
            //gridIndex['$id']=index
            //onClickRow(index,'$id')
            $('#rechazo').datagrid('load',{detalle:row.IdProduccionDetalle});
        },
        onDblClickRow: function(index){onClickRow(index,'$id')},
        toolbar: '#tbProduccion',
        columns:[[
            {title:'IdProduccionDetalle',field:'IdProduccionDetalle',hidden:true,editor:{type:'textbox'}},
            {title:'IdProduccion',field:'IdProduccion',hidden:true,editor:{type:'textbox'}},
            {title:'IdProgramacion',field:'IdProgramacion',hidden:true,editor:{type:'textbox'}},
            {
                title:'Producto',
                field:'IdProductos',
                width:150,
                align:'center',
                editor:{
                    type:'combogrid',
                    options:{
                        url:'/Fimex/programacion/data_diaria2?proceso=$proceso',
                        valueField:'IdProducto',
                        textField:'Producto',
                        panelWidth:265,
                        queryParams:{
                            Dia:'$fecha'
                        },
                        columns:[[
                            {field:'IdProgramacion',title:'IdProgramacion',width:100},
                            {field:'IdProducto',title:'IdProducto',hidden:true},
                            {field:'Producto',title:'Producto',width:100},
                            {field:'Programadas',title:'Cantidad',width:60},
                        ]],
                        onSelect:function(record){
                            var ed = $('#$id').datagrid('getEditor', {index:gridIndex['$id'],field:'IdProductos'}).target;
                            Productos = $(ed).combogrid('grid').datagrid('getSelected');

                            $('#$id').datagrid('getRows')[gridIndex['$id']]['Producto'] = Productos.Producto;
                            var producto = $('#$id').datagrid('getEditor', {index:gridIndex['$id'],field:'IdProductos'}).target;

                            //$('#$id').datagrid('getEditor', {index:gridIndex['$id'],field:'IdProductos'}).target.combogrid('setValue',Productos.IdProducto);
                            $('#$id').datagrid('getEditor', {index:gridIndex['$id'],field:'IdProgramacion'}).target.textbox('setValue',Productos.IdProgramacion);
                            $('#$id').datagrid('getEditor', {index:gridIndex['$id'],field:'CiclosMolde'}).target.numberspinner('setValue',Productos.CiclosMolde);
                            $('#$id').datagrid('getEditor', {index:gridIndex['$id'],field:'PiezasMolde'}).target.numberspinner('setValue',Productos.PiezasMolde);
                            $('#$id').datagrid('getEditor', {index:gridIndex['$id'],field:'Programadas'}).target.numberspinner('setValue',Productos.Programadas);
                        },
                    }
                },
                formatter:function(value,row,index){
                    return row.Producto;
                },
            },
            {title:'Inicio',field:'Inicio',width:80,align:'center',editor:{type:'timespinner'}},
            {title:'Fin',field:'Fin',width:80,align:'center',editor:{type:'timespinner'}},
            {title:'Hechas',field:'Hechas',width:80,align:'center',editor:{type:'numberspinner',options:{precision:0}}},
            {title:'Rech',field:'Rechazadas',width:80,align:'center',editor:{type:'numberspinner',options:{precision:0}}},
            {title:'Prog',field:'Programadas',width:80,align:'center',editor:{type:'numberspinner',options:{precision:0}}},
            {title:'Cic x Mol',field:'CiclosMolde',width:80,align:'center',editor:{type:'numberspinner',options:{precision:0}}},
            {title:'Pzas x Mol',field:'PiezasMolde',width:80,align:'center',editor:{type:'numberspinner',options:{precision:0}}},
            {field:'action',title:'',width:80,align:'center',
                formatter:function(value,row,index){
                    if (row.editing){
                        return '<a href=\"#\" class=\"easyui-linkbutton\" onclick=\"saverow(this,\'$id\')\">Guardar</a>';
                    }else{
                        return '<a href=\"#\" class=\"easyui-linkbutton\" onclick=\"editrow(this,\'$id\')\">Editar</a>';
                    }
                }
            },
            {field:'action2',title:'',width:80,align:'center',
                formatter:function(value,row,index){
                    if (row.editing){
                        return '<a href=\"#\" class=\"easyui-linkbutton\" onclick=\"cancelrow(this,\'$id\')\">Cancelar</a>';
                    }else{
                        return '<a href=\"#\" class=\"easyui-linkbutton\" onclick=\"deleterow(this,\'$id\')\">Eliminar</a>';
                    }
                    
                }
            },
        ]],
        onBeforeEdit:function(index,row){
            row.editing = true;
            updateActions(index,'$id');
        },
        onAfterEdit:function(index,row){
            row.editing = false;
            updateActions(index,'$id');
        },
        onCancelEdit:function(index,row){
            row.editing = false;
            updateActions(index,'$id');
        },
    });
",$this::POS_END);
?>

<table id="<?=$id?>" style='height:50%' class="easyui-datagrid datagrid-f" title="Registro de Produccion" ></table>