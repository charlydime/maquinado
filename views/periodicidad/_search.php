<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model frontend\models\ete\PeriodicidadSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="periodicidad-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'periodo') ?>

    <?= $form->field($model, 'hrs') ?>

    <?= $form->field($model, 'min_sem') ?>

    <?= $form->field($model, 'min_dia') ?>

    <?php // echo $form->field($model, 'lun') ?>

    <?php // echo $form->field($model, 'mar') ?>

    <?php // echo $form->field($model, 'mie') ?>

    <?php // echo $form->field($model, 'jue') ?>

    <?php // echo $form->field($model, 'vie') ?>

    <?php // echo $form->field($model, 'sab') ?>

    <?php // echo $form->field($model, 'dom') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
