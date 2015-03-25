<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\catalogos\Aleaciones */

$this->title = $model->IdAleacion;
$this->params['breadcrumbs'][] = ['label' => 'Aleaciones', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="aleaciones-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->IdAleacion], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->IdAleacion], [
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
            'IdAleacion',
            'Identificador',
            'Descripcion',
            'IdAleacionTipo',
        ],
    ]) ?>

</div>
