<?php

/* @var $this yii\web\View */

use yii\helpers\Html;

$this->title = '关于';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="site-about">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="alert alert-info">
        <?= nl2br(Html::encode('本网站作者：钟震宇（网站） 张志毅（数据库/欢迎界面）。')) ?>
    </div>

    <p>网站版本1.1</p>

</div>