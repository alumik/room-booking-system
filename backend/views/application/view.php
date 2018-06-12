<?php

/** @noinspection PhpUnhandledExceptionInspection */

use yii\helpers\Html;
use yii\widgets\DetailView;

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
                'value' => $model->room->room_number . $model->room->statusStrColoered,
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
                'format' => ['date', 'php: Y-m-d H:i:s'],
            ],
            [
                'attribute' => 'end_time',
                'format' => ['date', 'php: Y-m-d H:i:s'],
            ],
            [
                'attribute' => 'status',
                'value' => $model->getStatusStr(),
                'contentOptions' => $model->getStatusBg(),
            ],
        ],
    ]) ?>

    <?PHP
        if ($model->status != \common\models\Application::STATUS_REJECTED) {
            ?>
            <p>
                <?PHP
                    if ($model->status != \common\models\Application::STATUS_APPROVED) {
                        ?>
                        <?= Html::a('批准', ['approve', 'id' => $model->id], [
                            'class' => 'btn btn-primary',
                            'data' => [
                                'confirm' => 'Are you sure you want to delete this item?',
                                'method' => 'post',
                            ],
                        ]) ?>
                        <?php
                    }
                ?>
                <?= Html::a('拒绝', ['reject', 'id' => $model->id], [
                    'class' => 'btn btn-danger',
                    'data' => [
                        'confirm' => 'Are you sure you want to delete this item?',
                        'method' => 'post',
                    ],
                ]) ?>
            </p>
            <?php
        }
    ?>

</div>
