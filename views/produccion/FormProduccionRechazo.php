<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use common\models\Grid;

$id = "rechazo";

echo Html::beginTag('div',['id'=>'tbRechazo']);
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
        url:'/Fimex/produccion/rechazo',
        singleSelect:true,
        method:'post',
        striped:true,
        remoteSort:false,
        multiSort:true, 
        loadMsg: 'Cargando rechazo', 
        onLoadSuccess: 
            function(data){
            },
        onClickRow: function(index){},
        toolbar: '#tbRechazo',
        columns:[[
            {title:'IdProduccionDefecto',field:'IdProduccionDefecto',hidden:true,editor:{type:'textbox'}},
            {title:'IdProduccionDetalle',field:'IdProduccionDetalle',hidden:true,editor:{type:'textbox'}},
            {
                title:'Defecto',
                field:'IdDefecto',width:200,align:'center',editor:{
                type:'combobox',options:{url:'/Fimex/produccion/data_rechazo',valueField:'IdDefecto',textField:'nombreDefecto'}
                },
                formatter:function(value,row,index){
                    return row.Defecto;
                }
            },
            {title:'Cantidad',field:'Rechazadas',width:80,align:'center',editor:{type:'numberspinner',options:{precision:0}}},
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
<table id="<?=$id?>" class="easyui-datagrid datagrid-f" title="Control de defectos" style="height:50%" ></table>