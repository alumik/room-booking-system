<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use common\models\Room;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\RoomSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '预约房间';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="room-index">
    <h1><?= Html::encode($this->title); ?></h1>
    <p>房间可预约时间最长为十二小时</p>
    <?= $this->render('_search', ['model' => $searchModel]); ?>
    <div class="scrollable col-lg-9 row">
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'showOnEmpty' => false,
            'emptyText' => '没有符合搜索条件的房间',
            'columns' => [
                [
                    'attribute' => 'room_number',
                    'contentOptions' => ['style' => 'width:80px; vertical-align:middle'],
                ],
                [
                    'attribute' => 'type',
                    'value' => 'type0.type_name',
                    'filter' => Room::getAllTypes(),
                    'contentOptions' => ['style' => 'vertical-align:middle'],
                ],
                [
                    'attribute' => 'campus',
                    'value' => 'campus0.campus_name',
                    'filter' => Room::getAllCampus(),
                    'contentOptions' => ['style' => 'vertical-align:middle'],
                ],
                [
                    'value' => function ($model) use ($searchModel) {
                        /* @var $model Room */
                        $queueCount = $model->getQueueCount($searchModel->start_time, $searchModel->end_time);
                        return $queueCount == 0 ? '无' : $queueCount . ' 个';
                    },
                    'label' => '待审核申请',
                    'contentOptions' => ['style' => 'width:100px; vertical-align:middle'],
                ],
                [
                    'attribute' => 'approval_status',
                    'value' => function ($model) use ($searchModel) {
                        /* @var $model Room */
                        $startTime = $searchModel->start_time;
                        $endTime = $searchModel->end_time;
                        $status = $model->getApprovalStatus($startTime, $endTime)['text'];

                        if ($status == '已分配') {
                            return Html::a(
                                $status,
                                ['view', 'id' => $model->id, 'startTime' => $startTime, 'endTime' => $endTime]
                            );
                        }

                        return $status;
                    },
                    'label' => '分配状态',
                    'contentOptions' => function ($model) use ($searchModel) {
                        /* @var $model Room */
                        $options = $model->getApprovalStatus($searchModel->start_time, $searchModel->end_time)['class'];
                        $options['style'] = 'width:80px; vertical-align:middle';
                        return $options;
                    },
                    'format' => 'raw',
                ],
                [
                    'class' => 'yii\grid\ActionColumn',
                    'template' => '{order}',
                    'buttons' => [
                        'order' => function ($url, $model) use ($searchModel) {
                            $s_time = strtotime($searchModel->start_time);
                            $e_time = strtotime($searchModel->end_time);

                            $options = [
                                'title' => '预约',
                                'aria-label' => '预约',
                                'data-pjax' => '0',
                            ];

                            $url = Url::to(['order', 'id' => $model->id, 'startTime' => $s_time, 'endTime' => $e_time]);

                            return Html::a('<span class="btn btn-primary">预约</span>', $url, $options);
                        }
                    ],
                    'contentOptions' => [
                        'align' => 'center',
                        'width' => '1px',
                    ]
                ],
            ],
        ]); ?>
    </div>
</div>
