<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model frontend\models\ete\periodicidad */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="periodicidad-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'periodo')->textInput() ?>

    <?= $form->field($model, 'hrs')->textInput() ?>

    <?= $form->field($model, 'min_sem')->textInput() ?>

    <?= $form->field($model, 'min_dia')->textInput() ?>

    <?= $form->field($model, 'lun')->checkbox() ?>

    <?= $form->field($model, 'mar')->checkbox() ?>

    <?= $form->field($model, 'mie')->checkbox() ?>

    <?= $form->field($model, 'jue')->checkbox() ?>

    <?= $form->field($model, 'vie')->checkbox() ?>

    <?= $form->field($model, 'sab')->checkbox() ?>

    <?= $form->field($model, 'dom')->checkbox() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
