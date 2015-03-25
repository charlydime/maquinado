<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\programacion\Pedidos */

$this->title = 'Update Pedidos: ' . ' ' . $model->IdPedido;
$this->params['breadcrumbs'][] = ['label' => 'Pedidos', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->IdPedido, 'url' => ['view', 'id' => $model->IdPedido]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="pedidos-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
