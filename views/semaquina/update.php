<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model frontend\models\maquinado\PdpMaquinaBl */

$this->title = 'Update Pdp Maquina Bl: ' . ' ' . $model->pieza;
$this->params['breadcrumbs'][] = ['label' => 'Pdp Maquina Bls', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->pieza, 'url' => ['view', 'id' => $model->pieza]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="pdp-maquina-bl-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
