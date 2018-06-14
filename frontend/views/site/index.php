<?php

use yii\helpers\Html;

/* @var $this yii\web\View */

/* @author 钟震宇 <nczzy1997@gmail.com> */

$this->title = Yii::$app->name;
?>
<div class="site-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="alert alert-info">
        <?= nl2br(Html::encode('本页面正在建设中，请稍后再来。')) ?>
    </div>

</div>
