<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model frontend\models\maquinado\pdpmaquina */

$this->title = 'Create Pdpmaquina';
$this->params['breadcrumbs'][] = ['label' => 'Pdpmaquinas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pdpmaquina-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
