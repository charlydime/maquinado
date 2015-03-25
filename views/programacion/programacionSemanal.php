<?php

use yii\helpers\Html;
use yii\helpers\URL;
use common\models\Grid;

$id = 'programacion_semanal';

$semana = $semanas["semana1"]['value'];

echo Html::beginTag('div',['id'=>'tbSemanal']);

    echo Html::a('Agregar',"javascript:void(0)",[
        'class'=>"easyui-linkbutton",
        'data-options'=>"iconCls:'icon-add',plain:true",
        'onclick'=>"append('#$id')"
    ]);
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
        location.href ='/Fimex/programacion/semanal?semana1=' + semana;
    });
");
$this->registerJS("
    var ProgramacionIndex = undefined;
    
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
            $.post('".URL::to('/Fimex/programacion/save_semanal')."',
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
    url:'/Fimex/programacion/data_semanal',
    queryParams:{
        semana1:'<?= $semanas["semana1"]['value']?>',
    },
    singleSelect:false,
    method:'post',
    collapsible:true,
    remoteSort:false,
    multiSort:true,
    showFooter:true,
    groupField:'Marca',
    loadMsg: 'Cargando datos',
    onAfterEdit: onAfterEdit,
    onClickRow: function(index){
        onClickRow(index);
    },
    toolbar: '#tbSemanal',
    groupFormatter: function(value,rows){
        return value + ' - ' + rows.length + ' Registro(s)';
    },
    view: groupview,
">
    <thead data-options="frozen:true">
        <tr>
            <th rowspan="2" data-options="field:'IdProgramacion',width:50,hidden:true,editor:{type:'numberbox',options:{precision:0,editable:false}}">Id</th>
            <th rowspan="2" data-options="field:'Producto',sortable:true,width:200">Producto</th>
            <th rowspan="2" data-options="field:'ProductoCasting',sortable:true,width:200">Casting</th>
            <th rowspan="2" data-options="field:'Descripcion',sortable:true,width:250">Descripcion</th>
            <th rowspan="2" data-options="field:'FechaEmbarque',align:'center',sortable:true,width:100">Embarque</th>
            <th rowspan="2" data-options="field:'Aleacion',sortable:true,hidden:true,width:100">Aleacion</th>
            <th rowspan="2" data-options="field:'Marca',align:'center',sortable:true,width:100">Cliente</th>
            <th rowspan="2" data-options="field:'Presentacion',sortable:true,hidden:true,width:100">Presentacion</th>
            <th rowspan="2" data-options="field:'Cantidad',sortable:true,width:65">Cantidad</th>
            <th rowspan="2" data-options="field:'Moldes',sortable:true,width:65">Moldes</th>
            <th colspan="6" data-options="field:'0',align:'center'">Existencias</th>
            <th rowspan="2" data-options="field:'ExitPTB',align:'center',width:50">Exit <br>PTB</th>
            <th rowspan="2" data-options="field:'FaltaPTB',align:'center',width:50">Falta <br>PTB</th>
            <th rowspan="2" data-options="field:'ExitCast',align:'center',width:50">Exit <br>Cast</th>
            <th rowspan="2" data-options="field:'FaltaCast',align:'center',width:50">Falta <br>Cast</th>
            <th rowspan="2" data-options="field:'TotalProgramado',align:'center',width:100">Total programado</th>
            <th rowspan="2" data-options="field:'IdProgramacionSemana1',hidden:true,align:'center',width:80">IdProgramacionSemana1</th>
            <th rowspan="2" data-options="field:'IdProgramacionSemana2',hidden:true,align:'center',width:80">IdProgramacionSemana2</th>
            <th rowspan="2" data-options="field:'IdProgramacionSemana3',hidden:true,align:'center',width:80">IdProgramacionSemana3</th>
            <th rowspan="2" data-options="field:'Anio1',align:'center',hidden:true,width:80">Anio1</th>
            <th rowspan="2" data-options="field:'Anio2',align:'center',hidden:true,width:80">Anio2</th>
            <th rowspan="2" data-options="field:'Anio3',align:'center',hidden:true,width:80">Anio3</th>
            <th rowspan="2" data-options="field:'Semana1',align:'center',hidden:true,width:80">Semana1</th>
            <th rowspan="2" data-options="field:'Semana2',align:'center',hidden:true,width:80">Semana2</th>
            <th rowspan="2" data-options="field:'Semana3',align:'center',hidden:true,width:80">Semana3</th>
        </tr>
        <tr>
            <th data-options="field:'PLB',align:'center',width:50">PLB</th>
            <th data-options="field:'PMB',align:'center',width:50">PMB</th>
            <th data-options="field:'PTB',align:'center',width:50">PTB</th>
            <th data-options="field:'TRB',align:'center',width:50">TRB</th>
            <th data-options="field:'PCC',align:'center',width:50">PCC</th>
            <th data-options="field:'CTB',align:'center',width:50">CTB</th>
        </tr>
    </thead>
    <thead>
        <tr>
            <th colspan="4" data-options="field:'0',align:'center'">
                <input id="semana1" type="week" value="<?= $semanas["semana1"]['value']?>">
            </th>
            <th colspan="4" data-options="field:'1',align:'center'">
                <input id="semana2" type="week" value="<?= $semanas["semana2"]['value']?>">
            </th>
            <th colspan="4" data-options="field:'2',align:'center'">
                <input id="semana3" type="week" value="<?= $semanas["semana3"]['value']?>">
            </th>
        </tr>
        <tr>
            <th data-options="field:'Prioridad1',width:80,align:'center',editor:{type:'numberspinner',options:{precision:0}}">Prioridad</th>
            <th data-options="field:'Programadas1',width:80,align:'center',editor:{type:'numberspinner',options:{precision:0}}">Programadas</th>
            <th data-options="field:'Hechas1',width:80,align:'center'">Hechas</th>
            <th data-options="field:'Horas1',width:80,align:'center'">Horas</th>
            <th data-options="field:'Prioridad2',width:80,align:'center',editor:{type:'numberspinner',options:{precision:0}}">Prioridad</th>
            <th data-options="field:'Programadas2',width:80,align:'center',editor:{type:'numberspinner',options:{precision:0}}">Programadas</th>
            <th data-options="field:'Hechas2',width:80,align:'center'">Hechas</th>
            <th data-options="field:'Horas2',width:80,align:'center'">Horas</th
            <th data-options="field:'Prioridad3',width:80,align:'center',editor:{type:'numberspinner',options:{precision:0}}">Prioridad</th>
            <th data-options="field:'Programadas3',width:80,align:'center',editor:{type:'numberspinner',options:{precision:0}}">Programadas</th>
            <th data-options="field:'Hechas3',width:80,align:'center'">Hechas</th>
            <th data-options="field:'Horas3',width:80,align:'center'">Horas</th>
        </tr>
    </thead
</table>

