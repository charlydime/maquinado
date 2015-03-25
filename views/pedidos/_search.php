<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\programacion\PedidosSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="pedidos-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'IdPedido') ?>

    <?= $form->field($model, 'IdAlmacen') ?>

    <?= $form->field($model, 'IdProducto') ?>

    <?= $form->field($model, 'Codigo') ?>

    <?= $form->field($model, 'Numero') ?>

    <?php // echo $form->field($model, 'Fecha') ?>

    <?php // echo $form->field($model, 'Cliente') ?>

    <?php // echo $form->field($model, 'OrdenCompra') ?>

    <?php // echo $form->field($model, 'Estatus') ?>

    <?php // echo $form->field($model, 'Cantidad') ?>

    <?php // echo $form->field($model, 'SaldoCantidad') ?>

    <?php // echo $form->field($model, 'FechaEmbarque') ?>

    <?php // echo $form->field($model, 'NivelRiesgo') ?>

    <?php // echo $form->field($model, 'Observaciones') ?>

    <?php // echo $form->field($model, 'TotalProgramado') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
