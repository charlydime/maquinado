<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model frontend\models\produccion\Producciones */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="producciones-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'IdCentroTrabajo')->textInput() ?>

    <?= $form->field($model, 'IdMaquina')->textInput() ?>

    <?= $form->field($model, 'IdUsuario')->textInput() ?>

    <?= $form->field($model, 'IdProduccionEstatus')->textInput() ?>

    <?= $form->field($model, 'Fecha')->textInput() ?>

    <?= $form->field($model, 'IdProceso')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
