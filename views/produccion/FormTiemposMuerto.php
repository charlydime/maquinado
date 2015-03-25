<?php

use yii\helpers\Html;
use yii\helpers\URL;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use common\models\datos\Causas;
use frontend\models\produccion\TiemposMuerto;

$id = 'tiempo_muerto';
$TM = new TiemposMuerto();

echo Html::beginTag('div',['id'=>'tbTM']);

    echo Html::a('Agregar',"javascript:void(0)",[
        'id'=>'add',
        'class'=>"easyui-linkbutton",
        'data-options'=>"iconCls:'icon-add',plain:true",
        'onclick'=>"append('$id')"
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
        url:'/Fimex/produccion/data_tiempos_muertos',
        queryParams:{
            IdProduccion:'$model->IdProduccion'
        },
        method:'get',
        collapsible:false,
        remoteSort:false,
        multiSort:true,
        showFooter:true,
        loadMsg: 'Cargando datos',
        toolbar: '#tbTM',
        columns:[[
            {field:'IdTiempoMuerto',hidden:true,align:'center',editor:{type:'numberspinner',options:{precision:0}}},
            {field:'IdProduccion',hidden:true,align:'center',editor:{type:'numberspinner',options:{precision:0}}},
            {
                field:'IdCausa',
                title:'Causa',
                width:300,
                editor:{
                    type:'combobox',
                    options:{
                        url:'/Fimex/produccion/data_causas?IdProceso=$proceso',
                        method:'get',
                        valueField:'IdCausa',
                        textField:'Descripcion',
                        groupField:'Tipo',
                        onSelect:function(record){
                        },
                    }
                },
                formatter:function(value,row,index){
                    return row.Causa;
                }
            },
            {field:'Inicio',title:'Inicio',width:80,align:'center',editor:{type:'timespinner',options:{precision:0}}},
            {field:'Fin',title:'Fin',width:80,align:'center',editor:{type:'timespinner',options:{precision:0}}},
            {field:'Descripcion',title:'Descripcion',width:400,editor:{type:'textbox',options:{precision:0}}},
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
<table id="<?=$id?>" style="height:50%" title="Registro Tiempos Muertos"></table>
