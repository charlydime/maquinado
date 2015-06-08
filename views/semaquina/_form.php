<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model frontend\models\maquinado\PdpMaquinaBl */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="pdp-maquina-bl-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'pieza')->textInput() ?>

    <?= $form->field($model, 'fecha')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
