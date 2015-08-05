<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\maquinado\semaquinaSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Pdp Maquina Bls';
$this->params['breadcrumbs'][] = $this->title;

$usr = Yii::$app->user->identity; 
					$u  =$usr->role;
		$barra = ['class' => 'yii\grid\ActionColumn',
		'template'=>'{delete}'	
		];
	if($u == 20)
		$barra = ['class' => 'yii\grid\ActionColumn'];

?>
<div class="pdp-maquina-bl-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Pdp Maquina Bl', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= 
	
		GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'pieza',
            'fecha',

           $barra ,
        ],
    ]); ?>

</div>
