<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\AdminSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '管理员管理';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="admin-index">
    <h1><?= Html::encode($this->title); ?></h1>
    <p>
        <?= Html::a('新增管理员', ['create'], ['class' => 'btn btn-success']); ?>
        <?= Html::a('权限对照表', ['view-privilege'], ['class' => 'btn btn-primary']); ?>
    </p>
    <div class="scrollable">
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'columns' => [
                [
                    'attribute' => 'admin_id',
                    'contentOptions' => ['width' => '120px'],
                ],
                'admin_name',
                'email:email',
                [
                    'attribute' => 'super_admin',
                    'label' => '超级管理员',
                    'value' => 'superAdminIndicator',
                    'filter' => ['1' => '是', '0' => '否'],
                    'contentOptions' => ['align' => 'center'],
                    'headerOptions' => ['class' => 'text-center'],
                ],
                [
                    'attribute' => 'web_admin',
                    'label' => '网站管理员',
                    'value' => 'webAdminIndicator',
                    'filter' => ['1' => '是', '0' => '否'],
                    'contentOptions' => ['align' => 'center'],
                    'headerOptions' => ['class' => 'text-center'],
                ],
                [
                    'attribute' => 'student_admin',
                    'label' => '学生管理员',
                    'value' => 'studentAdminIndicator',
                    'filter' => ['1' => '是', '0' => '否'],
                    'contentOptions' => ['align' => 'center'],
                    'headerOptions' => ['class' => 'text-center'],
                ],
                [
                    'attribute' => 'room_admin',
                    'label' => '预约管理员',
                    'value' => 'roomAdminIndicator',
                    'filter' => ['1' => '是', '0' => '否'],
                    'contentOptions' => ['align' => 'center'],
                    'headerOptions' => ['class' => 'text-center'],
                ],

                [
                    'class' => 'yii\grid\ActionColumn',
                    'contentOptions' => ['align' => 'center'],
                    'template' => '{view} {update} {reset-password} {privilege}',
                    'buttons' => [
                        'update' => function ($url) {
                            $options = [
                                'title' => '修改信息',
                                'aria-label' => '修改信息',
                                'data-pjax' => '0',
                            ];
                            return Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url, $options);
                        },
                        'reset-password' => function ($url) {
                            $options = [
                                'title' => '修改密码',
                                'aria-label' => '修改密码',
                                'data-pjax' => '0',
                            ];
                            return Html::a('<span class="glyphicon glyphicon-lock"></span>', $url, $options);
                        },
                        'privilege' => function ($url) {
                            $options = [
                                'title' => '修改权限',
                                'aria-label' => '修改权限',
                                'data-pjax' => '0',
                            ];
                            return Html::a('<span class="glyphicon glyphicon-th-list"></span>', $url, $options);
                        }
                    ]
                ],
            ],
        ]); ?>
    </div>
</div>
