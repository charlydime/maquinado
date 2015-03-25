<?php

use yii\helpers\Html;
use yii\helpers\URL;
use common\models\Grid;

$id = 'programacion_diario';

$semana = $semanas["semana1"]['value'];

echo Html::beginTag('div',['id'=>'tbSemanal']);
    echo Html::a('Guardar',"javascript:void(0)",[
        'class'=>"easyui-linkbutton",
        'data-options'=>"iconCls:'icon-save',plain:true",
        'onclick'=>"accept('#$id')"
    ]);
    echo Html::a('Actualizar',"javascript:void(0)",[
        'class'=>"easyui-linkbutton",
        'data-options'=>"iconCls:'icon-reload',plain:true",
        'onclick'=>"getChanges('#$id')"
    ]);
echo Html::endTag('div');
        
$this->registerJS("
    $('#semana1').change(function(){
        var semana = $(this).val();
        location.href ='/Fimex/almas/semanal?semana1=' + semana;
    });
");
$this->registerJS("
    var ProgramacionIndex = undefined;
    
    $.extend($.fn.datagrid.defaults.editors, {
        week: {
            init: function(container, options){
                var input = $('<input type=\"week\" class=\"datagrid-editable-input\">').appendTo(container);
                return input;
            },
            destroy: function(target){
                $(target).remove();
            },
            getValue: function(target){
                return $(target).val();
            },
            setValue: function(target, value){
                $(target).val(value);
            },
            resize: function(target, width){
                $(target)._outerWidth(width);
            }
        }
    });
    
    function onClickRow (index){
        if (ProgramacionIndex != index){
            if (endEditing('#$id')){
                $('#$id').datagrid('selectRow', index);
                $('#$id').datagrid('beginEdit', index);
                ProgramacionIndex = index;
            } else {
                $('#$id').datagrid('selectRow', $id);
            }
        }
    }
        
    function endEditing(grid){
        if (ProgramacionIndex == undefined){return true}
        if ($(grid).datagrid('validateRow', ProgramacionIndex)){
            $(grid).datagrid('endEdit', ProgramacionIndex);
            ProgramacionIndex = undefined;
            return true;
        } else {
            return false;
        }
    }
    
    function accept(grid){
        if (endEditing(grid)){
            var data = $(grid).datagrid('getChanges');
            $.post('".URL::to('/Fimex/almas/save_semanal')."',
                {Data: JSON.stringify(data)},
                function(data,status){
                    if(status == 'success' ){
                        $(grid).datagrid('load');
                        console.log(data);
                        \$var = $(grid).datagrid('getChanges');
                    }else{
                        reject('#$id');
                        alert('Error al guardar los datos');
                    }
                }
            );
        }
    }
    
    function getChanges(grid){
        $(grid).datagrid('load');
    }
    
    function onAfterEdit(grid){
        accept('#$id');
    }
    function onUnselect(){
        onAfterEdit('#$id');
    }
",$this::POS_END);
?>
<table id="<?= $id ?>" class="easyui-datagrid datagrid-f" title="" style="height:600px" data-options="
    url:'/Fimex/almas/data_semanal',
    queryParams:{
        semana1:'<?= $semanas["semana1"]['value']?>',
    },
    singleSelect:false,
    method:'post',
    collapsible:true,
    remoteSort:false,
    multiSort:true,
    showFooter:true,
    loadMsg: 'Cargando datos',
    onAfterEdit: onAfterEdit,
    onClickRow: function(index){
        onClickRow(index);
    },
    toolbar: '#tbSemanal',
">
    <thead data-options="frozen:true">
        <tr>
            <th rowspan="2" data-options="field:'IdProgramacion',width:50,hidden:true,editor:{type:'numberbox',options:{precision:0,editable:false}}">Id</th>
            <th rowspan="2" data-options="field:'ProductoCasting',sortable:true,width:200">Casting</th>
            <th rowspan="2" data-options="field:'Alma',align:'center',sortable:true,width:50">Alma</th>
            <th rowspan="2" data-options="field:'Descripcion',sortable:true,width:250">Descripcion</th>
            <th rowspan="2" data-options="field:'FechaEmbarque',align:'center',sortable:true,width:100">Embarque</th>
            <th rowspan="2" data-options="field:'Aleacion',sortable:true,hidden:true,width:100">Aleacion</th>
            <th rowspan="2" data-options="field:'Marca',align:'center',sortable:true,width:100">Cliente</th>
            <th rowspan="2" data-options="field:'Presentacion',sortable:true,hidden:true,width:100">Presentacion</th>
            <th rowspan="2" data-options="field:'IdProgramacionSemana1',hidden:true,align:'center',width:80">IdProgramacionSemana1</th>
            <th rowspan="2" data-options="field:'IdProgramacionSemana2',hidden:true,align:'center',width:80">IdProgramacionSemana2</th>
            <th rowspan="2" data-options="field:'IdProgramacionSemana3',hidden:true,align:'center',width:80">IdProgramacionSemana3</th>
            <th rowspan="2" data-options="field:'Anio1',align:'center',hidden:true,width:80">Anio1</th>
            <th rowspan="2" data-options="field:'Anio2',align:'center',hidden:true,width:80">Anio2</th>
            <th rowspan="2" data-options="field:'Anio3',align:'center',hidden:true,width:80">Anio3</th>
            <th rowspan="2" data-options="field:'Semana1',hidden:true">Semana1</th>
            <th rowspan="2" data-options="field:'Semana2',hidden:true">Semana2</th>
            <th rowspan="2" data-options="field:'Semana3',hidden:true">Semana3</th>
            <th colspan="3" data-options="align:'center'">Requerimientos de Almas</th>
        </tr>
        <tr>
            <th data-options="field:'Programadas1',align:'center'"><input id="semana1" type="week" value="<?= $semanas["semana1"]['value']?>"></th>
            <th data-options="field:'Programadas2',align:'center'"><input id="semana2" type="week" value="<?= $semanas["semana2"]['value']?>" disabled=""></th>
            <th data-options="field:'Programadas3',align:'center'"><input id="semana3" type="week" value="<?= $semanas["semana3"]['value']?>" disabled=""></th>
        </tr>
    </thead>
        <tr>
            <th colspan="3" data-options="align:'center'">Programacion de Almas</th>
        </tr>
    <thead>
        <tr>
            <th data-options="field:'0',width:80,align:'center',editor:{type:'textbox',options:{precision:0}}">Prioridad</th>
            <th data-options="field:'1',width:80,align:'center',editor:{type:'textbox',options:{precision:0}}">Cantidad</th>
            <th data-options="field:'2',width:150,align:'center',editor:{type:'week',options:{precision:0}}">Semana</th>
        </tr>
    </thead>
</table>