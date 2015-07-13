<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model frontend\models\maquinado\Turnos */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="turnos-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'Turno')->textInput() ?>

    <?= $form->field($model, 'hinicio')->textInput() ?>

    <?= $form->field($model, 'htermino')->textInput() ?>

    <?= $form->field($model, 'area')->textInput() ?>

    <?= $form->field($model, 'ncorto')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
