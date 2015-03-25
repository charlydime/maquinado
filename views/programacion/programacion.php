<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\programacion\ProgramacionesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = $title;
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="programaciones-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?= $this->render('pedidos');
    ?>
    <hr />
    <?= $this->render('programacionSemanal',[
            'semanas'=>$semanas,
            //'data'=>$data,
        ]);
    ?>
    

</div>
