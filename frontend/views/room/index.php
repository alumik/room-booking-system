<?php

/** @noinspection PhpUnusedParameterInspection */
/** @noinspection PhpUnhandledExceptionInspection */

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use common\models\Room;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\RoomSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

/* @author 钟震宇 <nczzy1997@gmail.com> */

$this->title = '预约房间';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="room-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>房间可预约使用时间最长为十二小时</p>

    <?php echo $this->render('_search', ['model' => $searchModel]); ?>

    <div class="scrollable col-lg-9 row">
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'showOnEmpty' => false,
            'emptyText' => '',
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
                    'attribute' => 'queue_count',
                    'value' => function($model)use($searchModel) {
                        /* @var $model Room */
                        $queue_count = $model->getQueueCount($searchModel->start_time_str, $searchModel->end_time_str);
                        return $queue_count == 0 ? '无' : $queue_count . ' 个';
                    },
                    'label' => '待审核申请',
                    'contentOptions' => ['style' => 'width:100px; vertical-align:middle'],
                ],
                [
                    'attribute' => 'approval_status',
                    'value' => function($model)use($searchModel) {
                        /* @var $model Room */
                        $s_time_str = $searchModel->start_time_str;
                        $e_time_str = $searchModel->end_time_str;
                        $a_status = $model->getApprovalStatus($s_time_str, $e_time_str)['text'];

                        if ($a_status == '已分配') {
                            return Html::a(
                                $a_status,
                                ['approvedapplication', 'id' => $model->id, 's_time_str' => $s_time_str, 'e_time_str' => $e_time_str]
                            );
                        }

                        return $a_status;
                    },
                    'label' => '分配状态',
                    'contentOptions' => function($model)use($searchModel) {
                        /* @var $model Room */
                        $options = $model->getApprovalStatus($searchModel->start_time_str, $searchModel->end_time_str)['class'];
                        $options['style'] = 'width:80px; vertical-align:middle';
                        return $options;
                    },
                    'format' => 'raw',
                ],
                [
                    'class' => 'yii\grid\ActionColumn',
                    'template' => '{order}',
                    'buttons' => [
                        'order' => function($url, $model, $key) use ($searchModel)
                        {
                            $s_time = strtotime($searchModel->start_time_str);
                            $e_time = strtotime($searchModel->end_time_str);

                            $options = [
                                'title' => '预约',
                                'aria-label' => '预约',
                                'data-pjax' => '0',
                            ];

                            $url = Url::to(['order', 'id' => $model->id, 's_time' => $s_time, 'e_time' => $e_time]);

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
