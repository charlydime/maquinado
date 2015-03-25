<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\programacion\Pedidos */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="pedidos-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'IdAlmacen')->textInput() ?>

    <?= $form->field($model, 'IdProducto')->textInput() ?>

    <?= $form->field($model, 'Codigo')->textInput() ?>

    <?= $form->field($model, 'Numero')->textInput() ?>

    <?= $form->field($model, 'Fecha')->textInput() ?>

    <?= $form->field($model, 'Cliente')->textInput() ?>

    <?= $form->field($model, 'OrdenCompra')->textInput() ?>

    <?= $form->field($model, 'Estatus')->textInput() ?>

    <?= $form->field($model, 'Cantidad')->textInput() ?>

    <?= $form->field($model, 'SaldoCantidad')->textInput() ?>

    <?= $form->field($model, 'FechaEmbarque')->textInput() ?>

    <?= $form->field($model, 'NivelRiesgo')->textInput() ?>

    <?= $form->field($model, 'Observaciones')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'TotalProgramado')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
