<?php

use kartik\widgets\ActiveForm;
use kartik\builder\TabularForm;
use kartik\grid\GridView;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $searchModel app\models\programacion\PedidosSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Pedidos';
$this->params['breadcrumbs'][] = $this->title;

$form = ActiveForm::begin();
$attribs = $model->formAttribs;
//var_dump($attribs);exit;

echo TabularForm::widget([
    'dataProvider' => $dataProvider,
    'form' => $form,
    'attributes' => $attribs,
    'serialColumn' => false,
    'checkboxColumn'=>false,
    //'actionColumn'=>false,
    'gridSettings' => [
        'beforeHeader'=>[
            [
                'columns'=>[
                    ['content'=>'<input type="week" />', 'options'=>['colspan'=>4, 'class'=>'text-center warning']], 
                    ['content'=>'Header Before 2', 'options'=>['colspan'=>4, 'class'=>'text-center warning']], 
                    ['content'=>'Header Before 3', 'options'=>['colspan'=>3, 'class'=>'text-center warning']], 
                    [
                        'content'=>
                            Html::a('<i class="glyphicon glyphicon-plus"></i> Agregar', '#', ['class'=>'btn btn-success']) . ' ' . 
                            Html::a('<i class="glyphicon glyphicon-remove"></i> Eliminar', '#', ['class'=>'btn btn-danger']) . ' ' .
                            Html::submitButton('<i class="glyphicon glyphicon-floppy-disk"></i> Guardar', ['class'=>'btn btn-primary']),
                        'options'=>['colspan'=>3, 'class'=>'text-center warning']
                    ],
                ],
                'options'=>['class'=>'skip-export'] // remove this row from export
            ]
        ],
        'floatHeader' => true,
        'panel' => [
            'heading' => '<h3 class="panel-title"><i class="glyphicon gly phicon-book"></i>Pedidos</h3>',
            'type' => GridView::TYPE_PRIMARY,
            'before'=> ' '
        ]
    ]   
]);
ActiveForm::end();
?>
