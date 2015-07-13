<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model frontend\models\maquinado\turnosSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="turnos-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'idturno') ?>

    <?= $form->field($model, 'Turno') ?>

    <?= $form->field($model, 'hinicio') ?>

    <?= $form->field($model, 'htermino') ?>

    <?= $form->field($model, 'area') ?>

    <?php // echo $form->field($model, 'ncorto') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
