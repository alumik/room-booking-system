<?php

/** @noinspection PhpUnusedParameterInspection */
/** @noinspection PhpUnhandledExceptionInspection */

use yii\helpers\Html;
use yii\grid\GridView;
use common\models\User;

/* @var $this yii\web\View */
/* @var $searchModel common\models\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '学生管理';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="user-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="scrollable">
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'columns' => [
                [
                    'attribute' => 'student_id',
                    'contentOptions' => ['width' => '80px'],
                ],
                'username',
                'email:email',
                [
                    'attribute' => 'status',
                    'value' => 'statusStr',
                    'filter' => User::allStatus(),
                ],
                [
                    'attribute' => 'created_at',
                    'format' => ['date', 'php: Y-m-d H:i'],
                    'filter' => '',
                ],
                [
                    'attribute' => 'updated_at',
                    'format' => ['date', 'php: Y-m-d H:i'],
                    'filter' => '',
                ],

                [
                    'class' => 'yii\grid\ActionColumn',
                    'contentOptions' => ['align' => 'center'],
                    'template' => '{changestatus}',
                    'buttons' => [
                        'changestatus' => function($url, $model, $key)
                        {
                            if ($model->status == User::STATUS_ACTIVE) {
                                $options = [
                                    'title' => '禁用学生',
                                    'aria-label' => '禁用学生',
                                    'data-pjax' => '0',
                                    'data-method' => 'post',
                                    'data-confirm' => '你确定要禁用该学生吗？该学生的所有预约都将被取消。',
                                ];
                                return Html::a('<span class="glyphicon glyphicon-remove"></span>', $url, $options);
                            }
                            $options = [
                                'title' => '启用学生',
                                'aria-label' => '启用学生',
                                'data-pjax' => '0',
                                'data-method' => 'post',
                            ];
                            return Html::a('<span class="glyphicon glyphicon-ok"></span>', $url, $options);
                        },
                    ]
                ],
            ],
        ]); ?>
    </div>

</div>
