<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Application */

/* @author 钟震宇 <nczzy1997@gmail.com> */

$this->title = '打印预约申请';
$this->params['breadcrumbs'][] = ['label' => '我的预约', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = '打印';
?>

<div class="application-print">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="alert alert-info">
        <?= nl2br(Html::encode('本功能正在建设中，请稍后再来。')) ?>
    </div>

</div>