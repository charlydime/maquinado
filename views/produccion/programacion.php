<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\helpers\URL;
use common\models\Grid;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\programacion\ProgramacionesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */


$columns['Producto'] = [
    'label'=>'Producto',
    'data-options'=>['width'=>80,'align'=>'center','editor'=>['type'=>'numberspinner','options'=>['precision'=>0]]]
];
$columns['Prioridad']=[
    'label'=>'Pr',
    'data-options'=>['width'=>80,'align'=>'center','editor'=>['type'=>'numberspinner','options'=>['precision'=>0]]]
];
$columns['Programadas']=[
    'label'=>'P',
    'data-options'=>['width'=>80,'align'=>'center']
];
$columns['Hechas']=[
    'label'=>'H',
    'data-options'=>['width'=>80,'align'=>'center']
];


$id = 'programacion';
       
$grid = new Grid([
    'id'=>$id,
    'style'=>'height:100%',
    'onClickRow'=> "
        function (index){
            if (ProgramacionIndex != index){
                if (endEditing('#$id')){
                    $('#$id').datagrid('selectRow', index);
                    $('#$id').datagrid('beginEdit', index);
                    ProgramacionIndex = index;
                } else {
                    $('#$id').datagrid('selectRow', ProgramacionIndex);
                }
            }
        }",
    'view'=>'groupview',
    'queryParams'=>"{
        Dia: '$fecha'
    }",
    'groupFormatter'=>"function(value,rows){
        return 'Programacion para el turno de ' + value ;
    }",
    'onLoadSussess'=>"function (){
            alert(hola);
        }",
    'onAfterEdit'=> 'onAfterEdit',
    'onUnselect'=> 'onUnselect',
    'dataOptions' => [
        'url' =>"/Fimex/programacion/data_diaria2?proceso=$proceso",
        'singleSelect'=> false,
        'method'=> 'post',
        'collapsible'=>true,
        'remoteSort'=>false,
        'multiSort'=>true,
        'showFooter'=> true,
        'groupField'=>'Turno',
    ],
    'columns' => [
        $columns
    ]
]);

$grid->display();
?>