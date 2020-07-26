<?php

/** @noinspection PhpUnhandledExceptionInspection */

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\User */

/* @author 钟震宇 <nczzy1997@gmail.com> */

$this->title = '我的账号';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="user-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('修改信息', ['update'], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('修改密码', ['reset-password'], ['class' => 'btn btn-primary']) ?>
    </p>

    <?= DetailView::widget([
        'template' => "<tr><th style='width: 20%'>{label}</th><td style='width: 80%'>{value}</td></tr>",
        'model' => $model,
        'attributes' => [
            'student_id',
            'username',
            'email:email',
            [
                'attribute' => 'status',
                'value' => $model->getStatusStr(),
            ],
            [
                'attribute' => 'created_at',
                'format' => ['date', 'php: Y-m-d H:i'],
            ],
            [
                'attribute' => 'updated_at',
                'format' => ['date', 'php: Y-m-d H:i'],
            ],
        ],
    ]) ?>

</div>
