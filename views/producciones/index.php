<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\produccion\ProduccionesSerach */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Producciones';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="producciones-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Producciones', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'IdProduccion',
            'IdCentroTrabajo',
            'IdMaquina',
            'IdUsuario',
            'IdProduccionEstatus',
            // 'Fecha',
            // 'IdProceso',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
