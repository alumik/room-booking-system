<?php

/** @noinspection PhpUnusedParameterInspection */
/** @noinspection PhpUnhandledExceptionInspection */

use yii\helpers\Html;
use yii\grid\GridView;
use common\models\Room;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\RoomSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

/* @author 钟震宇 <nczzy1997@gmail.com> */

$this->title = '房间管理';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="room-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('新增房间', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <div class="scrollable col-lg-9 row">
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
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
                    'attribute' => 'available',
                    'value' => 'statusStr',
                    'filter' => Room::getAllStatus(),
                    'contentOptions' => function($model) {
                        /* @var $model \common\models\Room */
                        $options = $model->getStatusBg();
                        $options['width'] = '80px';
                        return $options;
                    },
                ],
                [
                    'class' => 'yii\grid\ActionColumn',
                    'contentOptions' => ['width' => '80px', 'align' => 'center'],
                    'template' => '{view} {update} {changestatus}',
                    'buttons' => [
                        'update' => function($url, $model, $key)
                        {
                            $options = [
                                'title' => '修改信息',
                                'aria-label' => '修改信息',
                                'data-pjax' => '0',
                            ];
                            return Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url, $options);
                        },
                        'changestatus' => function($url, $model, $key)
                        {
                            $options = [
                                'title' => '切换状态',
                                'aria-label' => '切换状态',
                                'data-pjax' => '0',
                                'data-confirm' => '确定要切换该房间状态吗？',
                                'data-method' => 'post',
                            ];
                            return Html::a('<span class="glyphicon glyphicon-refresh"></span>', $url, $options);
                        },
                    ],
                ],
            ],
        ]); ?>
    </div>

</div>
