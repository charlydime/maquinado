<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\catalogos\Productos */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="productos-form">

    <?php 
    $form = ActiveForm::begin([
        'id'=>'param-moldeo',
        'enableAjaxValidation'=>true,
    ]); ?>
    
    <table>
        <tr>
            <td><?= $form->field($model, 'PiezasMolde')->textInput(['class' => 'f1 easyui-textbox']) ?></td>
            <td><?= $form->field($model, 'CiclosMolde')->textInput(['class' => 'f1 easyui-textbox']) ?></td>
        </tr>
        <tr>
            <td><?= $form->field($model, 'PesoCasting')->textInput(['class' => 'f1 easyui-textbox']) ?></td>
            <td><?= $form->field($model, 'PesoArania')->textInput(['class' => 'f1 easyui-textbox']) ?></td>
        </tr>
    </table>
    <div class="form-group">
        <?= Html::Button('Guardar', [
            'class' => 'btn btn-primary',
            'onclick'=>'submitForm()'
        ]) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
