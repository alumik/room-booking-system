<?php

/** @noinspection PhpUnhandledExceptionInspection */

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\ApplicationSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '预约管理';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="application-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <p>黄色标记表明该申请与已批准的申请冲突或房间已不可用</p>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            'id',
            [
                'attribute' => 'room_number',
                'label' => '房间号',
                'value' => 'room.room_number',
            ],
            [
                'attribute' => 'campus',
                'label' => '校区',
                'value' => 'room.campus0.campus_name',
                'filter' => \common\models\Room::getAllCampus(),
            ],
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
                'format' => ['date', 'php: Y-m-d H:i:s'],
                'filter' => '',
            ],
            [
                'attribute' => 'end_time',
                'format' => ['date', 'php: Y-m-d H:i:s'],
                'filter' => '',
            ],
            [
                'attribute' => 'status',
                'value' => 'statusStr',
                'filter' => \common\models\Application::getAllStatus(),
                'contentOptions' => function($model) {
                    $options = $model->getStatusBg();
                    $options['width'] = '80px';
                    return $options;
                },
            ],

            [
                'class' => 'yii\grid\ActionColumn',
                'contentOptions' => function($model) {
                    $options = $model->getActionBg();
                    $options['align'] = 'center';
                    return $options;
                },
                'template' => '{view}',
                'buttons' => [
                    'view' => function($url, $model, $key)
                    {
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
