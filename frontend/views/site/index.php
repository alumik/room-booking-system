<?php

/* @var $this yii\web\View */

use yii\helpers\Html;

$this->title = Yii::$app->name;
?>
<div class="site-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="alert alert-info">
        <?= nl2br(Html::encode('本页面正在建设中，请稍后再来。')) ?>
    </div>

</div>
