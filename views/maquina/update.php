<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model frontend\models\maquinado\pdpMaquina */

$this->title = 'Update Pdp Maquina: ' . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Pdp Maquinas', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="pdp-maquina-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
