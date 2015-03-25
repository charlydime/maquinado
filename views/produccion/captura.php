
<?php
/* @var $this yii\web\View */
use yii\helpers\Html;
use yii\bootstrap\Modal;
use yii\helpers\URL;

$this->title = $title;
$this->params['breadcrumbs'][] = $this->title;
$this->params['breadcrumbs'][] = $proceso;

$fecha = $produccion->getAttributes()['Fecha']!=null ? date('Y-m-d',strtotime($produccion->getAttributes()['Fecha'])) : date('Y-m-d');
?>

<div id="encabezado">
<?= $this->render('FormProduccion',[
    'model'=>$produccion,
    'proceso'=>$proceso
]);?>
</div>


<?php if($produccion->getAttributes()['IdProduccionEstatus'] == 1 ): ?>
<div class="easyui-layout" style="width:100%;height:690px;">
    <div data-options="region:'center',border:false">
        <div class="easyui-layout" data-options="fit:true">
            <div data-options="region:'west',border:true,split:true" title="Programado" style="width:20%">
                <?= $this->render('programacion',[
                    'proceso'=>$proceso,
                    'fecha'=>$fecha
                ]);?>
            </div>
            <div data-options="region:'center',border:false">
                <div class="easyui-layout" data-options="fit:true">u
                    <div data-options="region:'east',border:true" style="width:30%">
                        <?= $this->render('FormProduccionRechazo',[
                            'proceso'=>$proceso
                        ]);?>
                        <div class="easyui-layout" style="width:100%;">
                            <div data-options="region:'center',border:true,split:false">
                                
                            </div>
                        </div>
                    </div>
                    <div data-options="region:'center',border:true,split:false" style="width:70%">
                        <?= $this->render('FormProduccionDetalle',[
                            'proceso'=>$proceso,
                            'fecha'=>$fecha
                        ]);?>
                        <?= $this->render('FormTiemposMuerto',[
                            'proceso'=>$proceso,
                            'fecha'=>$fecha,
                            'model'=>$produccion,
                        ]);?>
                        <div id="resultado"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php endif ?>
<?php
$this->registerJS("
    $.extend($.fn.datagrid.defaults.editors, {
        combogrid: {
            init: function(container, options){
                var input = $('<input type=\"text\" class=\"datagrid-editable-input\">').appendTo(container); 
                input.combogrid(options);
                return input;
            },
            destroy: function(target){
                $(target).combogrid('destroy');
            },
            getValue: function(target){
                return $(target).combogrid('getValue');
            },
            setValue: function(target, value){
                $(target).combogrid('setValue', value);
            },
            resize: function(target, width){
                $(target).combogrid('resize',width);
            }
        },
    });
    var Productos = undefined;
    var gridIndex = {
        tiempo_muerto:undefined,
        rechazo:undefined,
        detalle:undefined,
    };
    
    function onClickRow(indice,grid){
        var gridSelector = '#'+grid;
        if (gridIndex[grid] != indice){
            if (endEditing(grid,gridSelector)){
                $(gridSelector).datagrid('selectRow', indice);
                $(gridSelector).datagrid('beginEdit', indice);
                gridIndex[grid] = indice;
            } else {
                $(gridSelector).datagrid('selectRow', gridIndex[grid]);
            }
        }
    }

    function endEditing(grid,gridSelector){
        if (gridIndex[grid] == undefined){return true}
        if ($(gridSelector).datagrid('validateRow', gridIndex[grid])){
            $(gridSelector).datagrid('endEdit', gridIndex[grid]);
            gridIndex[grid] = undefined;
            return true;
        } else {
            return false;
        }
    }
    
    function append(grid){
        var gridSelector = '#'+grid;
        if (endEditing(grid,gridSelector)){
            var data = '';
            switch(grid){
                case 'detalle':
                        data = {
                            IdProduccion:$('#IdProduccion').val(),
                            IdProduccionDetalle:null,
                        };
                        $('#tbRechazo .easyui-linkbutton').linkbutton('disable');
                        $('#tbTM .easyui-linkbutton').linkbutton('disable');
                        $('#detalle #add').linkbutton('disable');
                    break;
                case 'rechazo':
                        data = {
                            IdProduccionDetalle:$('#detalle').datagrid('getRows')[gridIndex['detalle']]['IdProduccionDetalle'],
                            IdProduccionDefecto:null,
                        };
                    break;
                case 'tiempo_muerto':
                    data = {
                            IdProduccion:$('#IdProduccion').val(),
                            Inicio:'00:00',
                            Fin:'00:00',
                        };
                    break;
            }
            
            $(gridSelector).datagrid('appendRow',data);
            gridIndex[grid] = $(gridSelector).datagrid('getRows').length-1;
            $(gridSelector).datagrid('selectRow', gridIndex[grid])
                    .datagrid('beginEdit', gridIndex[grid]);
        }
    }
        
    function accept(grid){
        var gridSelector = '#'+grid;
        if (endEditing(grid,gridSelector)){
            var dat = $(gridSelector).datagrid('getChanges');
            if(dat.length == 0){
                return false
            }
            if(Productos != undefined && dat[0].IdProductos == ''){
                dat[0].IdProductos = Productos.IdProducto;
            }
            console.log(dat);
            dat = JSON.stringify(dat);
            $.get('".URL::to('/Fimex/produccion/save')."',
                {
                    data: dat,
                    grid: grid
                },
                function(data,status){
                    console.log(data);
                    if(status == 'success' ){
                        $(gridSelector).datagrid('load');
                    }else{
                        reject(grid);
                        alert('Error al guardar los datos');
                    }
                }
            );
            $(gridSelector).datagrid('acceptChanges');
            if(grid == 'detalle'){
                $('#tbRechazo .easyui-linkbutton').linkbutton('enable');
                $('#tbTM .easyui-linkbutton').linkbutton('enable');
                $('#detalle #add').linkbutton('enable');
            }
            dat= undefined;
        }
    }
    
    function getChanges(grid){
        $(grid).datagrid('load');
    }
    
    function onAfterEdit(grid){
        accept(grid);
    }
    function onUnselect(grid){
        onAfterEdit(grid);
    }
    
    function updateActions(index,grid){
        $('#'+grid).datagrid('updateRow',{
            index: index,
            row:{}
        });
    }
    function getRowIndex(target){
        var tr = $(target).closest('tr.datagrid-row');
        return parseInt(tr.attr('datagrid-row-index'));
    }
    function editrow(target,grid){
        $('#'+grid).datagrid('beginEdit', getRowIndex(target));
    }
    function deleterow(target,grid){
        $.messager.confirm('Confirm','Seguro que desea eliminar el registro?',function(r){
            if (r){
                gridSelector = '#'+grid;
                rows = $(gridSelector).datagrid('getRows');
                console.log(rows[getRowIndex(target)]);
                $.get('".URL::to('/Fimex/produccion/delete')."',
                    {
                        data: JSON.stringify(rows[getRowIndex(target)]),
                        grid: grid
                    },
                    function(data,status){
                        console.log(data);
                        if(status == 'success' ){
                            $(gridSelector).datagrid('load');
                        }else{
                            reject(grid);
                            alert('Error al guardar los datos');
                        }
                    }
                );
            }
        });
    }
    function saverow(target,grid){
        gridSelector = '#'+grid;
        $(gridSelector).datagrid('endEdit', getRowIndex(target));
        console.log($(gridSelector).datagrid('getChanges'));
        var dat = $(gridSelector).datagrid('getChanges');
            if(dat.length == 0){
                return false
            }
            if(Productos != undefined && dat[0].IdProductos == ''){
                dat[0].IdProductos = Productos.IdProducto;
            }
            console.log(dat);
            dat = JSON.stringify(dat);
            $.get('".URL::to('/Fimex/produccion/save')."',
                {
                    data: dat,
                    grid: grid
                },
                function(data,status){
                    console.log(data);
                    if(status == 'success' ){
                        $(gridSelector).datagrid('load');
                    }else{
                        reject(grid);
                        alert('Error al guardar los datos');
                    }
                }
            );
    }
    function cancelrow(target,grid){
        $('#'+grid).datagrid('cancelEdit', getRowIndex(target));
    }
",$this::POS_END);