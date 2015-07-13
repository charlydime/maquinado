<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model frontend\models\maquinaSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="pdp-maquina-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'Maquina') ?>

    <?= $form->field($model, 'Descripcion') ?>

    <?= $form->field($model, 'TiempoDisponible') ?>

    <?= $form->field($model, 'Area') ?>

    <?= $form->field($model, 'activa') ?>

    <?php // echo $form->field($model, 'id') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
