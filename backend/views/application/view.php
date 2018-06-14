<?php

/** @noinspection PhpUnhandledExceptionInspection */

use yii\helpers\Html;
use yii\widgets\DetailView;
use common\models\Application;
use common\models\Room;

/* @var $this yii\web\View */
/* @var $model common\models\Application */

$this->title = '预约申请详情';
$this->params['breadcrumbs'][] = ['label' => '预约管理', 'url' => ['index']];
$this->params['breadcrumbs'][] = $model->id;
?>

<div class="application-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            [
                'attribute' => 'room_number',
                'label' => '房间号',
                'value' => Html::a(
                    $model->room->room_number,
                    ['room/view', 'id' => $model->room_id]
                ) . $model->room->getColoredStatusStr(),
                'captionOptions' => ['width' => '20%'],
                'format' => 'raw',
            ],
            [
                'attribute' => 'campus',
                'label' => '校区',
                'value' => $model->room->campus0->campus_name,
            ],
            [
                'attribute' => 'applicant_student_id',
                'label' => '申请人学号',
                'value' => $model->applicant->student_id,
            ],
            [
                'attribute' => 'applicant_name',
                'label' => '申请人姓名',
                'value' => $model->applicant->username,
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

    <?PHP
        if ($model->canUpdate()) {
            ?>
            <p>
                <?PHP
                    if ($model->status != Application::STATUS_APPROVED && $model->room->available != Room::STATUS_UNAVAILABLE) {
                        ?>
                        <?= Html::a('批准', ['approve', 'id' => $model->id], [
                            'class' => 'btn btn-primary',
                            'data' => [
                                'confirm' => '你确定要批准该申请吗？',
                                'method' => 'post',
                            ],
                        ]) ?>
                        <?php
                    }
                ?>
                <?= Html::a('拒绝', ['reject', 'id' => $model->id], [
                    'class' => 'btn btn-danger',
                    'data' => [
                        'confirm' => '你确定要拒绝该申请吗？该操作不能撤销。',
                        'method' => 'post',
                    ],
                ]) ?>
            </p>
            <?php
        }
    ?>

</div>
