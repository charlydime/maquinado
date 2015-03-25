<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\catalogos\Aleaciones */

$this->title = 'Create Aleaciones';
$this->params['breadcrumbs'][] = ['label' => 'Aleaciones', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="aleaciones-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
