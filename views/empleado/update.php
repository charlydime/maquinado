<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model frontend\models\ete\empleado */

$this->title = 'Update Empleado: ' . ' ' . $model->idEmpleado;
$this->params['breadcrumbs'][] = ['label' => 'Empleados', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->idEmpleado, 'url' => ['view', 'id' => $model->idEmpleado]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="empleado-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
