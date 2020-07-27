<?php

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use common\widgets\Alert;
use common\models\Application;
use backend\assets\AppAsset;

/* @var $this yii\web\View */
/* @var $content string */

AppAsset::register($this);
?>

<?php $this->beginPage(); ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language; ?>">
<head>
    <meta charset="<?= Yii::$app->charset; ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags(); ?>
    <title><?= Html::encode($this->title); ?></title>
    <?php $this->head(); ?>
</head>
<body>
<?php $this->beginBody(); ?>
<div class="wrap">
    <?php
    NavBar::begin([
        'brandLabel' => Yii::$app->name,
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar-inverse navbar-fixed-top',
        ],
    ]);

    $menuItems = [];

    if (Yii::$app->user->isGuest) {
        $menuItems[] = ['label' => '登录', 'url' => ['/site/login']];
    } else {
        if (Yii::$app->user->can('manageRoom')) {
            $menuItems[] = ['label' => '房间管理', 'url' => ['/room/index']];

            $_count = Application::getPendingApplicationCount();
            if ($_count > 0) {
                $menuItems[] = ['label' => '预约管理 <span class="badge badge-inverse">' . $_count . '</span>', 'url' => ['/application/index']];
            } else {
                $menuItems[] = ['label' => '预约管理', 'url' => ['/application/index']];
            }
        }

        if (Yii::$app->user->can('viewStudentList')) {
            $menuItems[] = ['label' => '学生管理', 'url' => ['/user/index']];
        }

        if (Yii::$app->user->can('viewAdminList')) {
            $menuItems[] = ['label' => '管理员管理', 'url' => ['/admin/index']];
        }

        $menuItems[] = '<li>'
            . Html::beginForm(['/site/logout'], 'post')
            . Html::submitButton(
                '注销 (' . Yii::$app->user->identity->admin_name . ')',
                ['class' => 'btn btn-link logout']
            )
            . Html::endForm()
            . '</li>';
    }

    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
        'items' => $menuItems,
        'encodeLabels' => false,
    ]);

    NavBar::end();
    ?>
    <?php if ($this->context->id == 'site' && $this->context->action->id == 'index'): ?>
        <?= $content; ?>
    <?php else: ?>
        <div class="container">
            <?= Breadcrumbs::widget([
                'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
            ]); ?>
            <?= Alert::widget(); ?>
            <?= $content; ?>
        </div>
    <?php endif; ?>
</div>
<?php if (!($this->context->id == 'site' && $this->context->action->id == 'index')): ?>
    <footer class="footer">
        <div class="container">
            <p class="text-center">&copy; <?= Html::a(Yii::$app->name, ['/site/about']) ?> <?= date('Y') ?></p>
        </div>
    </footer>
<?php endif; ?>
<?php $this->endBody(); ?>
</body>
</html>
<?php $this->endPage(); ?>
