<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\catalogos\AleacionesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Aleaciones';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="aleaciones-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Aleaciones', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'IdAleacion',
            'Identificador',
            'Descripcion',
            'IdAleacionTipo',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
