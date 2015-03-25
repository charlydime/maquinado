<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\programacion\Pedidos */

$this->title = 'Create Pedidos';
$this->params['breadcrumbs'][] = ['label' => 'Pedidos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pedidos-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
