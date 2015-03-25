<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model frontend\models\produccion\Producciones */

$this->title = 'Update Producciones: ' . ' ' . $model->IdProduccion;
$this->params['breadcrumbs'][] = ['label' => 'Producciones', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->IdProduccion, 'url' => ['view', 'id' => $model->IdProduccion]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="producciones-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
