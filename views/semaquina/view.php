<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model frontend\models\maquinado\PdpMaquinaBl */

$this->title = $model->pieza;
$this->params['breadcrumbs'][] = ['label' => 'Pdp Maquina Bls', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pdp-maquina-bl-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->pieza], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->pieza], [
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
            'pieza',
            'fecha',
        ],
    ]) ?>

</div>
