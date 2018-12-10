<?php

namespace _;

use insolita\wgadminlte\Box;
use yii\bootstrap\Tabs;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use yii\helpers\VarDumper;
use yii\widgets\ListView;

$this->params['breadcrumbs'][] = ['label' => 'Configuration'];
$this->title = "Configuration";
?>

<div class="row">
    <div class="col-sm-6">
        <?php $this->beginBlock('controllers') ?>

        <div class="row">
            <?= $this->render('_module', ['key' => null]) ?>
        </div>

        <?= ListView::widget(
            [
                'dataProvider' => $loadedModulesDataProvider,
                'itemView' => '_module',
                'layout' => '{summary}{pager}<div class="row">{items}</div>'
            ]
        )
        ?>
        <?php $this->endBlock('controllers') ?>
    </div>
</div>


<?php $this->beginBlock('params') ?>
<?php foreach ($params as $name => $element): ?>
    <div class="row">
        <div class="col-sm-2">
            <b><?= $name ?></b>
        </div>
        <div class="col-sm-10">
<pre>
<?= Json::encode($element, JSON_PRETTY_PRINT) ?>
</pre>
        </div>
    </div>
<?php endforeach ?>
<?php $this->endBlock('params') ?>


<?php $this->beginBlock('components') ?>
<?php foreach ($components as $name => $element): ?>
    <div class="row">
        <div class="col-sm-2">
            <b><?= $name ?></b>
        </div>
        <div class="col-sm-10">
<pre>
<?= Json::encode($element, JSON_PRETTY_PRINT) ?>
</pre>
        </div>
    </div>
<?php endforeach ?>
<?php $this->endBlock('components') ?>



<?php $this->beginBlock('modules') ?>
<?php
foreach ($modules AS $name => $module) {
    $moduleItems[] = [
        'label' => $name,
        'content' => '<pre>' . VarDumper::dumpAsString($module, 2, true) . '</pre>'
    ];
}
?>
<?= Tabs::widget(['items' => $moduleItems]) ?>
<?php $this->endBlock('modules') ?>


<div class="nav-tabs-custom">
    <?= Tabs::widget([
                         'items' => [
                             [
                                 'label' => 'Params',
                                 'content' => $this->blocks['params'],
                             ],
                             [
                                 'label' => 'Components',
                                 'content' => $this->blocks['components'],
                             ],
                             [
                                 'label' => 'Modules',
                                 'content' => $this->blocks['modules'],
                             ],
                             [
                                 'label' => 'Controllers',
                                 'content' => $this->blocks['controllers'],
                             ],
                         ],
                     ]) ?>
</div>