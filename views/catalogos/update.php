<?php

use yii\helpers\Html;

?>
<div class="aleaciones-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render($form, [
        'model' => $model,
    ]) ?>

</div>
