<?php

namespace _;

use dmstr\cookiebutton\CookieButton;
use dmstr\modules\prototype\widgets\TwigWidget;
use lo\modules\noty\Wrapper;
use rmrevin\yii\fontawesome\FA;
use Yii;
use yii\base\InvalidCallException;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Menu;

/* @var $this \yii\web\View */
/* @var $content string */

\dmstr\modules\backend\assets\BackendAsset::register($this);
\dmstr\modules\backend\assets\BackendJsAsset::register($this);

if (Yii::$app->settings) {
    $adminLteSkin = Yii::$app->settings->get('skin', 'backend.adminlte') ?: 'black-light';
    $navBarIcon = Yii::$app->settings->get('navBarIcon', 'backend.adminlte') ?: FA::_HEART;
}

$js = <<<JS
$(document).on('click', '.sidebar-toggle', function () {
    if ($('body').hasClass("sidebar-collapse") && $('body').hasClass("sidebar-open")) {
       $('body').removeClass("sidebar-collapse");
    }
});
JS;
$this->registerJs($js);

?>

<?php $this->beginPage() ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) .' - '.Yii::$app->name ?></title>
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    <!-- Theme style -->
    <?php $this->head() ?>
</head>

<body class="hold-transition skin-<?= $adminLteSkin ?>
        <?= Yii::$app->request->cookies['dmstr-backend_pin-navigation'] ? '' : 'sidebar-collapse' ?>
        <?= Yii::$app->settings->get('sidebar', 'backend.adminlte', 'sidebar-mini ') ?> ">
<?php $this->beginBody() ?>

<?php

// Dynamic blocks need to be rendered before `context.menuItems`, otherwise the events are fired too late.
// TODO: try catch is for open begin/end detection, move to TwigWidget
try {
    $this->beginBlock('twig-main-top');
    echo TwigWidget::widget([
        'position' => 'main-top',
        'registerMenuItems' => true,
        'queryParam' => false,
        'renderEmpty' => false,
    ]);
    $this->endBlock('twig-main-top');
} catch (InvalidCallException $e) {
    $this->blocks['twig-main-top'] = 'X';
    Yii::$app->session->addFlash('error', $e->getMessage());
}

try {
    $this->beginBlock('twig-main-bottom');
    echo TwigWidget::widget([
        'position' => 'main-bottom',
        'registerMenuItems' => true,
        'queryParam' => false,
        'renderEmpty' => false,
    ]);
    $this->endBlock('twig-main-bottom');
} catch (InvalidCallException $e) {
    $this->blocks['twig-main-bottom'] = 'X';
    Yii::$app->session->addFlash('error', $e->getMessage());
}

try {
    $this->beginBlock('extra-content');
    echo TwigWidget::widget([
        'key' => 'backend.extra.content',
        'position' => 'bottom',
        'registerMenuItems' => true,
        'queryParam' => false,
        'renderEmpty' => false,
    ]);
    $this->endBlock('extra-content');
} catch (InvalidCallException $e) {
    $this->blocks['extra-content'] = '';
    Yii::$app->session->addFlash('error', $e->getMessage());
}

?>

<div class="wrapper">

    <header class="main-header">
        <!-- Logo -->
        <a href="<?= Url::to(['/backend']) ?>" class="logo">
            <?= FA::icon($navBarIcon) ?>
            <span class="title"></span><?= getenv('APP_TITLE') ?>
        </a>
        <!-- Header Navbar: style can be found in header.less -->
        <nav class="navbar navbar-static-top" role="navigation">
            <!-- Sidebar toggle button-->
            <?= CookieButton::widget([
                'label' => '',
                'options' => [
                    'class' => 'sidebar-toggle',
                    'data-toggle' => 'push-menu',
                    'role' => 'button',
                ],
                'cookieName' => 'dmstr-backend_pin-navigation',
                'cookieValue' => 'on',
                'cookieOptions' => [
                    'path' => '/',
                    'http' => true,
                    'expires' => 7,
                ],
            ]) ?>


            <div class="navbar-custom-menu">
                <ul class="nav navbar-nav">

                    <?php if (!\Yii::$app->user->isGuest): ?>

                        <!-- Messages: style can be found in dropdown.less-->
                        <li class="backend-extra-items-menu">
                            <?= \dmstr\modules\prototype\widgets\TwigWidget::widget([
                                'key' => 'backend.extra.menuItems',
                                'renderEmpty' => false,
                            ]) ?>
                        </li>







                        <!-- User Account: style can be found in dropdown.less -->
                        <li class="dropdown user user-menu">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                <i class="glyphicon glyphicon-user"></i>
                                <span><?= \Yii::$app->user->identity->username ?> <i class="caret"></i></span>
                            </a>
                            <ul class="dropdown-menu">
                                <!-- User image -->
                                <li class="user-header bg-light-blue">
                                    <p>
                                        <?= \Yii::$app->user->identity->username ?>
                                        <small><?= \Yii::$app->user->identity->email ?></small>
                                    </p>
                                </li>
                                <!-- Menu Footer-->
                                <li class="user-footer">
                                    <div class="pull-left">
                                        <a href="<?= \yii\helpers\Url::to(['/user/settings/profile']) ?>"
                                           class="btn btn-default btn-flat">Profile</a>
                                    </div>
                                    <div class="pull-right">
                                        <a href="<?= \yii\helpers\Url::to(['/user/security/logout']) ?>"
                                           target="_top"
                                           class="btn btn-default btn-flat" data-method="post">Sign out</a>
                                    </div>
                                </li>
                            </ul>
                        </li>

                        <li class="expand-menu">
                            <a href="<?= Url::to('') ?>" target="_top">
                                <i class="fa fa-expand"></i>
                            </a>
                        </li>


                        <li>
                            <a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </nav>
    </header>
    <!-- Left side column. contains the logo and sidebar -->
    <aside class="main-sidebar">
        <!-- sidebar: style can be found in sidebar.less -->
        <section class="sidebar">
            <?= $this->render('_sidebar') ?>
        </section>
        <!-- /.sidebar -->
    </aside>

    <!-- Right side column. Contains the navbar and content of the page -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                <?= $this->title ?>
                <small><?= \yii\helpers\Inflector::id2camel($this->context->module->id) ?></small>
            </h1>
            <?=
            \yii\widgets\Breadcrumbs::widget(
                [
                    'homeLink' => ['label' => 'Backend', 'url' => ['/backend']],
                    'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
                ]
            ) ?>
        </section>

        <!-- Main content -->

        <section class="content">
            <?= Wrapper::widget([
                'layerClass' => 'lo\modules\noty\layers\Growl',
                'options' => [
                    'dismissQueue' => true,
                    'location' => 'br',
                    'timeout' => 4000,
                ],
            ]) ?>
            <?= $this->blocks['twig-main-top'] ?>
            <?= $content ?>
            <?= $this->blocks['twig-main-bottom'] ?>
        </section>
        <!-- /.content -->
    </div>

    <?= $this->blocks['extra-content'] ?>

    <!-- /.content-wrapper -->
    <footer class="main-footer">
        <strong>
            <?= getenv('APP_NAME') ?>-<?= APP_VERSION ?></strong>
        built with
        <a href="http://phundament.com" target="_blank">phd</a>
    </footer>

    <?= $this->render('_control-sidebar') ?>
</div>
<!-- ./wrapper -->

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
