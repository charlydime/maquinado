<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model frontend\models\produccion\Producciones */

$this->title = $model->IdProduccion;
$this->params['breadcrumbs'][] = ['label' => 'Producciones', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="producciones-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->IdProduccion], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->IdProduccion], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'IdProduccion',
            'IdCentroTrabajo',
            'IdMaquina',
            'IdUsuario',
            'IdProduccionEstatus',
            'Fecha',
            'IdProceso',
        ],
    ]) ?>

</div>
