<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\helpers\URL;
use common\models\Grid;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\programacion\ProgramacionesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = $title;
$semana = $semanas['semana1']['value'];
$this->params['breadcrumbs'][] = $this->title;

$year = $semanas['semana1']['año'];
$week = $semanas['semana1']['semana'];
$fecha = strtotime($year."W".$week."1");
$fecha = date('Y-m-d',$fecha);
$dia = ['Lunes','Martes','Miercoles','Jueves','Viernes','Sabado','Domingo'];

$id = 'programacion_diaria';
echo Html::beginTag('div',['id'=>'tbDiaria']);
    echo "Ver: ".Html::tag("input","",[
        'id'=>'semana1',
        'type'=>'week',
        'value'=>$semana
    ]);
    echo " Turno: ".Html::activeDropDownList($turnos, 'IdTurno', ArrayHelper::map($turnos->find()->all(),'IdTurno','Descripcion'));
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
        load_grid($(this).val(),$('#turnos-idturno').val());
    });
    
    $('#vmarcas-idmarca').change(function(){
        
        $('#$id').datagrid('enableFilter');
    });
    $('#turnos-idturno').change(function(){
        $('#$id').datagrid('load',{
            turno:$(this).val(),
            
        });
    });
");
$this->registerJS("
    var ProgramacionIndex = undefined;
    
    function load_grid(semana,turno){
        location.href ='/Fimex/programacion/diaria?&proceso=$proceso&semana=' + semana;
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
    
    function append(grid){
        if (endEditing(grid)){
            $(grid).datagrid('appendRow');
            ProgramacionIndex = $(grid).datagrid('getRows').length-1;
            $(grid).datagrid('selectRow', ProgramacionIndex)
                .datagrid('beginEdit', ProgramacionIndex);
        }
    }
    function accept(grid){
        if (endEditing(grid)){
            var data = $(grid).datagrid('getChanges');
            $.post('".URL::to('/Fimex/programacion/save_diario')."',
                {Data: JSON.stringify(data)},
                function(data,status){
                    if(status == 'success' ){
                        $(grid).datagrid('load',{turno:$('#turnos-idturno').val()});
                        $('#resultado').html(data);
                        \$var = $(grid).datagrid('getChanges');
                    }else{
                        reject('#$id');
                        alert('Error al guardar los datos');
                    }
                }
            );
        }
    }
    
    function del(id){
        $.post('".URL::to('/Fimex/programacion/delete_diario')."',
            {IdProgramacionDia: id},
            function(data,status){
                $('#$id').datagrid('load',{turno:$('#turnos-idturno').val()});
            }
        );
    }
    
    function getChanges(grid){
        $(grid).datagrid('load',{turno:$('#turnos-idturno').val()});
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
    url:'/Fimex/programacion/data_diaria?proceso=<?=$proceso?>&semana=<?=$semana?>',
    singleSelect:false,
    method:'post',
    collapsible:true,
    remoteSort:false,
    multiSort:true,
    showFooter:true,
    loadMsg: 'Cargando datos',
    onAfterEdit: onAfterEdit,
    onDblClickRow: 
        function (index){
            if (ProgramacionIndex != index){
                if (endEditing('#programacion_diaria')){
                    $('#programacion_diaria').datagrid('selectRow', index);
                    $('#programacion_diaria').datagrid('beginEdit', index);
                    ProgramacionIndex = index;
                } else {
                    $('#programacion_diaria').datagrid('selectRow', ProgramacionIndex);
                }
            }
    },
    toolbar: '#tbDiaria'
">
    <thead data-options="frozen:true">
        <tr>
            <th data-options="field:'IdProgramacion',width:50,hidden:true,editor:{type:'numberbox',options:{precision:0,editable:false}}">Id</th>
            <th data-options="field:'IdTurno',hidden:true">Turno</th>
            <th data-options="field:'Producto',sortable:true,width:200">Producto</th>
            <th data-options="field:'ProductoCasting',sortable:true,width:200">Casting</th>
            <th data-options="field:'Descripcion',sortable:true,width:250">Descripcion</th>
            <th data-options="field:'FechaEmbarque',sortable:true,width:100">Embarque</th>
            <th data-options="field:'Aleacion',sortable:true,hidden:true,width:100">Aleacion</th>
            <th data-options="field:'Marca',sortable:true,width:100">Cliente</th>
            <th data-options="field:'Presentacion',sortable:true,hidden:true,width:100">Presentacion</th>
            <th data-options="field:'Cantidad',sortable:true,width:65">Cantidad</th>
            <th data-options="field:'TotalProgramado',align:'center',width:50">Total programado</th>
            <th data-options="field:'IdProgramacionSemana',hidden:true,align:'center',width:80">IdProgramacionSemana</th>
            <th data-options="field:'IdProceso',hidden:true,align:'center',width:80">IdProceso</th>
            <th data-options="field:'Anio',align:'center',hidden:true,width:80">Anio</th>
            <th data-options="field:'Semana',align:'center',hidden:true,width:80">Semana</th>
            <th data-options="field:'Prioridad',width:80,align:'center'">Prioridad</th>
            <th data-options="field:'Programadas',width:80,align:'center'">Programadas</th>
            <th data-options="field:'Hechas',width:80,align:'center'">Hechas</th>
            <th data-options="field:'Dia1',width:80,hidden:true,align:'center'">Dia1</th>
            <th data-options="field:'Dia2',width:80,hidden:true,align:'center'">Dia2</th>
            <th data-options="field:'Dia3',width:80,hidden:true,align:'center'">Dia3</th>
            <th data-options="field:'Dia4',width:80,hidden:true,align:'center'">Dia4</th>
            <th data-options="field:'Dia5',width:80,hidden:true,align:'center'">Dia5</th>
            <th data-options="field:'Dia6',width:80,hidden:true,align:'center'">Dia6</th>
            <th data-options="field:'Dia7',width:80,hidden:true,align:'center'">Dia7</th>
            <th data-options="field:'PiezasMolde',width:80,align:'center',
                formatter:function(value,row,index){
                    return parseInt(row.Cantidad) / parseInt(row.PiezasMolde);
                },
            ">Moldes</th>
        </tr>
    </thead>
    <thead>
        <tr>
            <th colspan="5" data-options="field:'0'">Lunes <?= date('d-m-Y',strtotime("$fecha +0 Day"))?></th>
            <th colspan="4" data-options="field:'1'">Martes <?= date('d-m-Y',strtotime("$fecha +1 Day"))?></th>
            <th colspan="4" data-options="field:'2'">Miercoles <?= date('d-m-Y',strtotime("$fecha +2 Day"))?></th>
            <th colspan="4" data-options="field:'3'">Jueves <?= date('d-m-Y',strtotime("$fecha +3 Day"))?></th>
            <th colspan="4" data-options="field:'4'">Viernes <?= date('d-m-Y',strtotime("$fecha +4 Day"))?></th>
            <th colspan="4" data-options="field:'5'">Sabado <?= date('d-m-Y',strtotime("$fecha +5 Day"))?></th>
            <th colspan="4" data-options="field:'6'">Domingo <?= date('d-m-Y',strtotime("$fecha +6 Day"))?></th>
        </tr>
        <tr>
            <th data-options="field:'Prioridad1',width:80,align:'center',editor:{type:'numberspinner',options:{precision:0}}">Prioridad</th>
            <th data-options="field:'Maquina1',width:80,align:'center',editor:{type:'combobox',options:{
                url:'/Fimex/programacion/data_maquina?proceso=<?=$proceso?>',
                valueField:'IdMaquina',
                textField:'Identificador',
                panelWidth:265,
                queryParams:{
                    proceso:2
                },
            }}">Maquina</th>
            <th data-options="field:'Programadas1',width:80,align:'center',editor:{type:'numberspinner',options:{precision:0}}">Programadas</th>
            <th data-options="field:'Hechas1',width:80,align:'center'">Hechas</th>
            <th data-options="
                field:'action1',width:70,align:'center',
                formatter:function(value,row,index){
                    if(row.IdProgramacionDia1 != null){
                        return '<a href=\'#\' onclick=\'del(' + row.IdProgramacionDia1 + ')\'>Eliminar</a>';
                    }
                    return '';
                }
            "></th>
            <th data-options="field:'Prioridad2',width:80,align:'center',editor:{type:'numberspinner',options:{precision:0}}">Prioridad</th>
            <th data-options="field:'Maquina2',width:80,align:'center',editor:{type:'combobox',options:{
                url:'/Fimex/programacion/data_maquina?proceso=<?=$proceso?>',
                valueField:'IdMaquina',
                textField:'Identificador',
                panelWidth:265,
                queryParams:{
                    proceso:2
                },
            }}">Maquina</th>
            <th data-options="field:'Programadas2',width:80,align:'center',editor:{type:'numberspinner',options:{precision:0}}">Programadas</th>
            <th data-options="field:'Hechas2',width:80,align:'center'">Hechas</th>
            <th data-options="
                field:'action2',width:70,align:'center',
                formatter:function(value,row,index){
                    if(row.IdProgramacionDia2 != null){
                        return '<a href=\'#\' onclick=\'del(' + row.IdProgramacionDia2 + ')\'>Eliminar</a>';
                    }
                    return '';
                }
            "></th>
            <th data-options="field:'Prioridad3',width:80,align:'center',editor:{type:'numberspinner',options:{precision:0}}">Prioridad</th>
            <th data-options="field:'Maquina3',width:80,align:'center',editor:{type:'combobox',options:{
                url:'/Fimex/programacion/data_maquina?proceso=<?=$proceso?>',
                valueField:'IdMaquina',
                textField:'Identificador',
                panelWidth:265,
                queryParams:{
                    proceso:2
                },
            }}">Maquina</th>
            <th data-options="field:'Programadas3',width:80,align:'center',editor:{type:'numberspinner',options:{precision:0}}">Programadas</th>
            <th data-options="field:'Hechas3',width:80,align:'center'">Hechas</th>
            <th data-options="
                field:'action3',width:70,align:'center',
                formatter:function(value,row,index){
                    if(row.IdProgramacionDia3 != null){
                        return '<a href=\'#\' onclick=\'del(' + row.IdProgramacionDia3 + ')\'>Eliminar</a>';
                    }
                    return '';
                }
            "></th>
            <th data-options="field:'Prioridad4',width:80,align:'center',editor:{type:'numberspinner',options:{precision:0}}">Prioridad</th>
            <th data-options="field:'Maquina4',width:80,align:'center',editor:{type:'combobox',options:{
                url:'/Fimex/programacion/data_maquina?proceso=<?=$proceso?>',
                valueField:'IdMaquina',
                textField:'Identificador',
                panelWidth:265,
                queryParams:{
                    proceso:2
                },
            }}">Maquina</th>
            <th data-options="field:'Programadas4',width:80,align:'center',editor:{type:'numberspinner',options:{precision:0}}">Programadas</th>
            <th data-options="field:'Hechas4',width:80,align:'center'">Hechas</th>
            <th data-options="
                field:'action4',width:70,align:'center',
                formatter:function(value,row,index){
                    if(row.IdProgramacionDia4 != null){
                        return '<a href=\'#\' onclick=\'del(' + row.IdProgramacionDia4 + ')\'>Eliminar</a>';
                    }
                    return '';
                }
            "></th>
            <th data-options="field:'Prioridad5',width:80,align:'center',editor:{type:'numberspinner',options:{precision:0}}">Prioridad</th>
            <th data-options="field:'Maquina5',width:80,align:'center',editor:{type:'combobox',options:{
                url:'/Fimex/programacion/data_maquina?proceso=<?=$proceso?>',
                valueField:'IdMaquina',
                textField:'Identificador',
                panelWidth:265,
                queryParams:{
                    proceso:2
                },
            }}">Maquina</th>
            <th data-options="field:'Programadas5',width:80,align:'center',editor:{type:'numberspinner',options:{precision:0}}">Programadas</th>
            <th data-options="field:'Hechas5',width:80,align:'center'">Hechas</th>
            <th data-options="
                field:'action5',width:70,align:'center',
                formatter:function(value,row,index){
                    if(row.IdProgramacionDia5 != null){
                        return '<a href=\'#\' onclick=\'del(' + row.IdProgramacionDia5 + ')\'>Eliminar</a>';
                    }
                    return '';
                }
            "></th>
            <th data-options="field:'Prioridad6',width:80,align:'center',editor:{type:'numberspinner',options:{precision:0}}">Prioridad</th>
            <th data-options="field:'Maquina6',width:80,align:'center',editor:{type:'combobox',options:{
                url:'/Fimex/programacion/data_maquina?proceso=<?=$proceso?>',
                valueField:'IdMaquina',
                textField:'Identificador',
                panelWidth:265,
                queryParams:{
                    proceso:2
                },
            }}">Maquina</th>
            <th data-options="field:'Programadas6',width:80,align:'center',editor:{type:'numberspinner',options:{precision:0}}">Programadas</th>
            <th data-options="field:'Hechas6',width:80,align:'center'">Hechas</th>
            <th data-options="
                field:'action6',width:70,align:'center',
                formatter:function(value,row,index){
                    if(row.IdProgramacionDia6 != null){
                        return '<a href=\'#\' onclick=\'del(' + row.IdProgramacionDia6 + ')\'>Eliminar</a>';
                    }
                    return '';
                }
            "></th>
            <th data-options="field:'Prioridad7',width:80,align:'center',editor:{type:'numberspinner',options:{precision:0}}">Prioridad</th>
            <th data-options="field:'Maquina7',width:80,align:'center',editor:{type:'combobox',options:{
                url:'/Fimex/programacion/data_maquina?proceso=<?=$proceso?>',
                valueField:'IdMaquina',
                textField:'Identificador',
                panelWidth:265,
                queryParams:{
                    proceso:2
                },
            }}">Maquina</th>
            <th data-options="field:'Programadas7',width:80,align:'center',editor:{type:'numberspinner',options:{precision:0}}">Programadas</th>
            <th data-options="field:'Hechas7',width:80,align:'center'">Hechas</th>
            <th data-options="
                field:'action7',width:70,align:'center',
                formatter:function(value,row,index){
                    if(row.IdProgramacionDia7 != null){
                        return '<a href=\'#\' onclick=\'del(' + row.IdProgramacionDia7 + ')\'>Eliminar</a>';
                    }
                    return '';
                }
            "></th>
        </tr>
    </thead>
</table>