<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model frontend\models\ete\reportes */
/* @var $form ActiveForm */
?>
<div class="reporte">
<h2>Reporte de falla</h2>
<h5><?= $msj ?> </h5>
    <?php $form = ActiveForm::begin(); ?>


        <?= $form->field($model, 'nomina') ?>
        <?= $form->field($model, 'descripcion')->textarea() ?>
    
        <div class="form-group">
            <?= Html::submitButton('Enviar', ['class' => 'btn btn-primary']) ?>
        </div>
    <?php ActiveForm::end(); ?>

</div><!-- reporte -->
