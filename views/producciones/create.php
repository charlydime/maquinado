<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model frontend\models\produccion\Producciones */

$this->title = 'Create Producciones';
$this->params['breadcrumbs'][] = ['label' => 'Producciones', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="producciones-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
