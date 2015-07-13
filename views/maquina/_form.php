<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model frontend\models\maquinado\pdpMaquina */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="pdp-maquina-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'Maquina')->textInput() ?>

    <?= $form->field($model, 'Descripcion')->textInput() ?>

    <?= $form->field($model, 'TiempoDisponible')->textInput() ?>

    <?= $form->field($model, 'Area')->textInput() ?>

    <?= $form->field($model, 'activa')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
