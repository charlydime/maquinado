<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model frontend\models\maquinado\pdpMaquina */

$this->title = 'Create Pdp Maquina';
$this->params['breadcrumbs'][] = ['label' => 'Pdp Maquinas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pdp-maquina-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
