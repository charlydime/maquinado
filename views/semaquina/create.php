<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model frontend\models\maquinado\PdpMaquinaBl */

$this->title = 'Create Pdp Maquina Bl';
$this->params['breadcrumbs'][] = ['label' => 'Pdp Maquina Bls', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pdp-maquina-bl-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
