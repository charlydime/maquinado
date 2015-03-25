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
            //echo Html::img('@web/frontend/assets/img/fimex_logo.png',['width'=>'100']);
            $area = Yii::$app->session->get('area');
            $brandLabel = ($area !== null ? "<b>Sistema de ".$area['Descripcion']." :: </b>" : "<b>Sistema Fimex :: </b>");
            //var_dump($area);exit;
            if($area !== null){
                $menuItems = Yii::$app->params['menu'][$area['IdArea']];
            }else{
                $model = new Areas();
                
                foreach($model->find()->asArray()->all() as $area => $valores){
                    $menuItems[] = ['label' => $valores['Descripcion'], 'url' => ['/site/index?area='.$valores['IdArea']]];
                }
            }

            NavBar::begin([
                'brandLabel' => $brandLabel,
                'brandUrl'=> '#',
                'options' => [
                    'class' => 'navbar-inverse navbar-fixed-top',
                ],
            ]);

            if (Yii::$app->user->isGuest) {
                $menuLogin[] = ['label' => 'Iniciar Sesion', 'url' => ['/site/login']];
            } else {
                $menuLogin[] = [
                    'label' => 'Cerrar Sesion (' . Yii::$app->user->identity->username . ')',
                    'url' => ['/site/logout'],
                    'linkOptions' => ['data-method' => 'post']
                ];
            }
            if (!Yii::$app->user->isGuest) {
                echo Nav::widget([
                    'options' => ['class' => 'navbar-nav navbar-left'],
                    'items' => $menuItems,
                ]);
            }
            echo Nav::widget([
                'options' => ['class' => 'navbar-nav navbar-right'],
                'items' => $menuLogin,
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