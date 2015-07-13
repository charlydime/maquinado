<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\maquinaSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Pdp Maquinas';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pdp-maquina-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Pdp Maquina', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'Maquina',
            'Descripcion',
            'TiempoDisponible',
            'Area',
            'activa',
            // 'id',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
