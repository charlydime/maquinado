<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model frontend\models\maquinado\Turnos */

$this->title = 'Update Turnos: ' . ' ' . $model->idturno;
$this->params['breadcrumbs'][] = ['label' => 'Turnos', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->idturno, 'url' => ['view', 'id' => $model->idturno]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="turnos-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
