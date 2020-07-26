<?php

/** @noinspection PhpUnusedParameterInspection */
/** @noinspection PhpUnhandledExceptionInspection */

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $model common\models\Room */
/* @var $dataProvider yii\data\ActiveDataProvider */

/* @author 钟震宇 <nczzy1997@gmail.com> */

$this->title = '分配详情：' . $model->room_number;
$this->params['breadcrumbs'][] = ['label' => '预约房间', 'url' => ['index']];
$this->params['breadcrumbs'][] = $model->room_number;
?>

<div class="room-view">

    <h1><?= Html::encode($this->title) ?></h1>

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
    ]) ?>

    <p><strong>该房间与你设定时间相冲突且已批准的申请</strong></p>

    <div class="scrollable">
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'showOnEmpty' => false,
            'emptyText' => '没有已批准的申请',
            'columns' => [
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
                    'template' => '{conflict-detail}',
                    'buttons' => [
                        'conflict-detail' => function($url, $model, $key)
                        {
                            $options = [
                                'title' => '查看详情',
                                'aria-label' => '查看详情',
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