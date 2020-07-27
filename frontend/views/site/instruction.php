<?php

use yii\helpers\Html;

$this->title = '说明';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="application-print">
    <h1><?= Html::encode($this->title); ?></h1>
    <div class="alert alert-info">
        <?= nl2br(Html::encode('本功能正在建设中，请稍后再来。')); ?>
    </div>
</div>
