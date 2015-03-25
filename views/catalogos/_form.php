<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\catalogos\Aleaciones */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="aleaciones-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'Identificador')->textInput() ?>

    <?= $form->field($model, 'Descripcion')->textInput() ?>

    <?= $form->field($model, 'IdAleacionTipo')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Guardar', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
