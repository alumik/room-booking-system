<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $model common\models\Room */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = $model->campus0->campus_name . ' ' . $model->room_number;
$this->params['breadcrumbs'][] = ['label' => '房间管理', 'url' => ['index']];
$this->params['breadcrumbs'][] = $model->id;
?>

<div class="room-view">
    <h1><?= Html::encode($this->title); ?></h1>
    <p>
        <?= Html::a('修改信息', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']); ?>
        <?= Html::a('切换可用状态', ['change-status', 'id' => $model->id], [
            'class' => 'btn btn-primary',
            'data' => [
                'confirm' => '你确定要切换该房间的可用状态吗？',
                'method' => 'post',
            ],
        ]); ?>
        <?= Html::a('删除', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => '你确定要删除该房间吗？',
                'method' => 'post',
            ],
        ]); ?>
    </p>
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'room_number',
            [
                'attribute' => 'type',
                'value' => $model->type0->type_name,
                'captionOptions' => ['width' => '20%'],
            ],
            [
                'attribute' => 'campus',
                'value' => $model->campus0->campus_name,
            ],
            [
                'attribute' => 'available',
                'value' => $model->getStatusStr(),
                'contentOptions' => $model->getStatusBg(),
            ],
        ],
    ]); ?>
    <p>
        <strong>该房间未来一个月已批准的申请</strong>
    </p>
    <div class="scrollable">
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'showOnEmpty' => false,
            'emptyText' => '没有已批准的申请',
            'columns' => [
                'id',
                [
                    'attribute' => 'applicant_student_id',
                    'label' => '申请人学号',
                    'value' => 'applicant.student_id',
                ],
                [
                    'attribute' => 'applicant_name',
                    'label' => '申请人姓名',
                    'value' => 'applicant.username',
                ],
                'organization_name',
                [
                    'attribute' => 'start_time',
                    'format' => ['date', 'php: Y-m-d H:i'],
                    'contentOptions' => ['class' => "time-column"],
                ],
                [
                    'attribute' => 'end_time',
                    'format' => ['date', 'php: Y-m-d H:i'],
                    'contentOptions' => ['class' => "time-column"],
                ],
                [
                    'class' => 'yii\grid\ActionColumn',
                    'controller' => 'application',
                    'contentOptions' => ['align' => 'center'],
                    'template' => '{view}',
                    'buttons' => [
                        'view' => function ($url) {
                            $options = [
                                'title' => '审核',
                                'aria-label' => '审核',
                                'data-pjax' => '0',
                            ];
                            return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', $url, $options);
                        },
                    ],
                ],
            ],
        ]); ?>
    </div>
</div>
