<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\catalogos\ProductosSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="productos-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'IdProducto') ?>

    <?= $form->field($model, 'IdMarca') ?>

    <?= $form->field($model, 'IdPresentacion') ?>

    <?= $form->field($model, 'IdAleacion') ?>

    <?= $form->field($model, 'IdProductoCasting') ?>

    <?php // echo $form->field($model, 'Identificacion') ?>

    <?php // echo $form->field($model, 'Descripcion') ?>

    <?php // echo $form->field($model, 'PiezasMolde') ?>

    <?php // echo $form->field($model, 'CiclosMolde') ?>

    <?php // echo $form->field($model, 'PesoCasting') ?>

    <?php // echo $form->field($model, 'PesoArania') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
