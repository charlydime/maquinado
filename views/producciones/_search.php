<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model frontend\models\produccion\ProduccionesSerach */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="producciones-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'IdProduccion') ?>

    <?= $form->field($model, 'IdCentroTrabajo') ?>

    <?= $form->field($model, 'IdMaquina') ?>

    <?= $form->field($model, 'IdUsuario') ?>

    <?= $form->field($model, 'IdProduccionEstatus') ?>

    <?php // echo $form->field($model, 'Fecha') ?>

    <?php // echo $form->field($model, 'IdProceso') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
