<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\catalogos\AleacionesSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="aleaciones-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'IdAleacion') ?>

    <?= $form->field($model, 'Identificador') ?>

    <?= $form->field($model, 'Descripcion') ?>

    <?= $form->field($model, 'IdAleacionTipo') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
