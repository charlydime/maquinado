<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model frontend\models\maquinado\pdpmaquina */

$this->title = 'Update Pdpmaquina: ' . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Pdpmaquinas', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="pdpmaquina-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
