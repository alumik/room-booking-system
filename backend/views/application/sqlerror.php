<?php

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

use yii\helpers\Html;

$this->title = '错误';
?>

<div class="site-error">

    <h1>预约申请冲突</h1>

    <div class="alert alert-danger">
        <?= nl2br(Html::encode('你将要批准的申请与已经批准的申请冲突')) ?>
    </div>

    <p>请返回申请列表检查冲突情况。</p>

    <p>如果你觉得这是服务器错误，请联系我们，谢谢。</p>

    <?= Html::a('返回', ['index'], ['class' => 'btn btn-primary']) ?>

</div>