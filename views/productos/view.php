<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\catalogos\Productos */

?>
<div class="productos-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->IdProducto], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->IdProducto], [
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
            'IdProducto',
            'IdMarca',
            'IdPresentacion',
            'IdAleacion',
            'IdProductoCasting',
            'Identificacion',
            'Descripcion',
            'PiezasMolde',
            'CiclosMolde',
            'PesoCasting',
            'PesoArania',
        ],
    ]) ?>

</div>
