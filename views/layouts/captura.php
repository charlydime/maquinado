<?php
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use frontend\assets\AppAsset;
use frontend\widgets\Alert;
use common\models\Areas;
use yii\db\ActiveQuery;

/* @var $this \yii\web\View */
/* @var $content string */
AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<?php $this->registerCSS(".container{width:100%;}");?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
    <?php $this->beginBody() ?>
    <div class="wrap">
        <?php
            $area = Yii::$app->session->get('area');
            $brandLabel = $area !== null ? "<b>Sistema de ".$area['Descripcion']." || </b>" : "<b>Sistema Fimex || </b>";
            NavBar::begin([
                'brandLabel' => $brandLabel,
                'brandUrl'=> '#',
                'options' => [
                    'class' => 'navbar-inverse navbar-fixed-top',
                ],
            ]);
                echo Nav::widget([
                    'options' => ['class' => 'navbar-nav navbar-left'],
                    'items' => [
                        ['label' => 'Salir', 'url' => ['/site/']],
                    ],
                ]);
            NavBar::end();
        ?>

        <div class="container">
        <?= Alert::widget() ?>
        <?= $content ?>
        </div>
    </div>

    <footer class="footer">
        <div class="container">
        <p class="pull-left">&copy; My Company <?= date('Y') ?></p>
        <p class="pull-right"><?= Yii::powered() ?></p>
        </div>
    </footer>

    <?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>