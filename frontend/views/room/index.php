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

$this->title = '预约房间';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="room-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>房间最长预约时间为十二小时</p>

    <?php echo $this->render('_search', ['model' => $searchModel]); ?>

    <div class="scrollable">
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'showOnEmpty' => false,
            'emptyText' => '',
            'columns' => [
                [
                    'attribute' => 'room_number',
                    'contentOptions' => ['width' => '80px'],
                ],
                [
                    'attribute' => 'type',
                    'value' => 'type0.type_name',
                    'filter' => Room::getAllTypes(),
                ],
                [
                    'attribute' => 'campus',
                    'value' => 'campus0.campus_name',
                    'filter' => Room::getAllCampus(),
                ],
                [
                    'attribute' => 'queue_count',
                    'value' => function($model)use($searchModel) {
                        /* @var $model Room */
                        return $model->getQueueCount($searchModel->start_time_str, $searchModel->end_time_str);
                    },
                    'label' => '待审核申请',
                    'contentOptions' => ['width' => '100px'],
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
                        $options['width'] = '80px';
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
                                'title' => '预约该房间',
                                'aria-label' => '预约该房间',
                                'data-pjax' => '0',
                            ];

                            $url = Url::to(['order', 'id' => $model->id, 's_time' => $s_time, 'e_time' => $e_time]);

                            return Html::a('<span class="btn btn-primary">预约该房间</span>', $url, $options);
                        }
                    ],
                    'contentOptions' => [
                        'align' => 'center',
                        'width' => '113px',
                    ]
                ],
            ],
        ]); ?>
    </div>
</div>
