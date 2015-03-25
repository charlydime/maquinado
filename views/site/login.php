<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

$this->title = 'Iniciar Sesion';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-login">
    <h1><?= Html::encode($this->title) ?></h1>

    <p>Favor de llenar los campos para iniciar sesion:</p>

    <div class="row">
        <div class="col-lg-5">
            <?php $form = ActiveForm::begin(['id' => 'login-form']); ?>
                <?= $form->field($model, 'username')->textInput([
                    'class'=>"easyui-textbox",
                    'data-options'=>"iconCls:'icon-man',prompt:'Usuario'",
                ])->label("") ?>
                <?= $form->field($model, 'password')->passwordInput([
                    'class'=>"easyui-textbox",
                    'data-options'=>"iconCls:'icon-lock',prompt:'Contraseña'",
                ])->label("") ?>
                <div style="color:#999;margin:1em 0">
                    Olvido la contraseña? <?= Html::a('restablecer aqui', ['site/request-password-reset']) ?>.
                </div>
                <div class="form-group">
                    <?= Html::submitButton('Iniciar', ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
                </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
