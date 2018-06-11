<?php

/** @noinspection PhpUnhandledExceptionInspection */

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\RoomSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '房间管理';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="room-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('新增房间', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

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
                'filter' => \common\models\Room::getAllTypes(),
            ],
            [
                'attribute' => 'campus',
                'value' => 'campus0.campus_name',
                'filter' => \common\models\Room::getAllCampus(),
            ],
            [
                'attribute' => 'available',
                'value' => 'statusStr',
                'filter' => \common\models\Room::getAllStatus(),
                'contentOptions' => function($model) {
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
                        ];
                        return Html::a('<span class="glyphicon glyphicon-refresh"></span>', $url, $options);
                    },
                ],
            ],
        ],
    ]); ?>
</div>
