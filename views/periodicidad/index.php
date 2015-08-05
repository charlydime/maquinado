<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\ete\PeriodicidadSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Periodicidad';
$this->params['breadcrumbs'][] = $this->title;


$usr = Yii::$app->user->identity; 
					$u  =$usr->role;
		$barra = ['class' => 'yii\grid\ActionColumn',
		'template'=>'{delete}'	
		];
	if($u == 20)
		$barra = ['class' => 'yii\grid\ActionColumn'];
?>
<div class="periodicidad-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Periodicidad', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'periodo',
            'hrs',
            'min_sem',
            'min_dia',
             'lun',
             'mar',
             'mie',
             'jue',
             'vie',
             'sab',
             'dom',

            $barra,
        ],
    ]); ?>

</div>
