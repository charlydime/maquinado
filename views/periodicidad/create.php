<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model frontend\models\ete\periodicidad */

$this->title = 'Create Periodicidad';
$this->params['breadcrumbs'][] = ['label' => 'Periodicidads', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="periodicidad-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
