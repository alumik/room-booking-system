<?php

/** @noinspection PhpUnhandledExceptionInspection */

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Application */

$this->title = '预约申请详情';
$this->params['breadcrumbs'][] = ['label' => '我的预约', 'url' => ['index']];
$this->params['breadcrumbs'][] = $model->id;
?>

<div class="application-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?php
            if ($model->canUpdate()) {
                echo Html::a('修改申请', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']);
            }
        ?>
        <?= Html::a('撤销申请', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => '你确定要撤销该申请吗？',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            [
                'attribute' => 'room_number',
                'label' => '房间号',
                'value' => $model->room->room_number . $model->room->getColoredStatusStr(),
                'captionOptions' => ['width' => '20%'],
                'format' => 'raw',
            ],
            [
                'attribute' => 'campus',
                'label' => '校区',
                'value' => $model->room->campus0->campus_name,
            ],
            'organization_name',
            'event:ntext',
            [
                'attribute' => 'start_time',
                'format' => ['date', 'php: Y-m-d H:i'],
            ],
            [
                'attribute' => 'end_time',
                'format' => ['date', 'php: Y-m-d H:i'],
            ],
            [
                'attribute' => 'status',
                'value' => $model->getStatusStr(),
                'contentOptions' => $model->getStatusBg(),
            ],
        ],
    ]) ?>

</div>
