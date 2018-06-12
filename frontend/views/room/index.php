<?php

/** @noinspection PhpUnhandledExceptionInspection */

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\RoomSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '预约房间';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="room-index">

    <h1><?= Html::encode($this->title) ?></h1>

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
                    'filter' => \common\models\Room::getAllTypes(),
                ],
                [
                    'attribute' => 'campus',
                    'value' => 'campus0.campus_name',
                    'filter' => \common\models\Room::getAllCampus(),
                ],
                [
                    'attribute' => 'queue_count',
                    'value' => function($model)use($searchModel) {
                        return $model->getQueueCount($searchModel->start_time, $searchModel->end_time);
                    },
                    'label' => '待审核申请',
                    'contentOptions' => ['width' => '100px'],
                ],
                [
                    'attribute' => 'approval_status',
                    'value' => function($model)use($searchModel) {
                        return $model->getApprovalStatus($searchModel->start_time, $searchModel->end_time)['text'];
                    },
                    'label' => '分配状态',
                    'contentOptions' => function($model)use($searchModel) {
                        $options = $model->getApprovalStatus($searchModel->start_time, $searchModel->end_time)['class'];
                        $options['width'] = '80px';
                        return $options;
                    },
                ],
                [
                    'class' => 'yii\grid\ActionColumn',
                    'template' => '{order}',
                    'buttons' => [
                        'order' => function($url, $model, $key) use ($searchModel)
                        {
                            $s_time = strtotime($searchModel->start_time);
                            $e_time = strtotime($searchModel->end_time);

                            $options = [
                                'title' => '预约该房间',
                                'aria-label' => '预约该房间',
                                'data-pjax' => '0',
                            ];

                            $url .= '&s_time=' . $s_time;
                            $url .= '&e_time=' . $e_time;

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
