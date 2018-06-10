<?php

/** @noinspection PhpUnusedParameterInspection */
/** @noinspection PhpUnhandledExceptionInspection */

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\AdminSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '管理员管理';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="admin-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('新增管理员', ['create'], ['class' => 'btn btn-success']) ?>
        <?= Html::a('权限对照表', ['viewprivilege'], ['class' => 'btn btn-primary']) ?>
    </p>
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
                'value' => 'superAdminStr',
                'filter' => ['0' => '不是', '1' => '是'],
                'contentOptions' => ['align' => 'center'],
            ],
            [
                'attribute' => 'web_admin',
                'label' => '网站管理员',
                'value' => 'webAdminStr',
                'filter' => ['0' => '不是', '1' => '是'],
                'contentOptions' => ['align' => 'center'],
            ],
            [
                'attribute' => 'student_admin',
                'label' => '学生管理员',
                'value' => 'studentAdminStr',
                'filter' => ['0' => '不是', '1' => '是'],
                'contentOptions' => ['align' => 'center'],
            ],
            [
                'attribute' => 'room_admin',
                'label' => '预约管理员',
                'value' => 'roomAdminStr',
                'filter' => ['0' => '不是', '1' => '是'],
                'contentOptions' => ['align' => 'center'],
            ],

            [
                'class' => 'yii\grid\ActionColumn',
                'contentOptions' => ['align' => 'center'],
                'template' => '{view} {update} {resetpwd} {privilege}',
                'buttons' => [

                    'resetpwd' => function($url, $model, $key)
                    {
                        $options = [
                            'title' => '修改密码',
                            'aria-label' => '修改密码',
                            'data-pjax' => '0',
                        ];
                        return Html::a('<span class="glyphicon glyphicon-cog"></span>', $url, $options);
                    },
                    'privilege' => function($url, $model, $key)
                    {
                        $options = [
                            'title' => '修改权限',
                            'aria-label' => '修改权限',
                            'data-pjax' => '0',
                        ];
                        return Html::a('<span class="glyphicon glyphicon-user"></span>', $url, $options);
                    }
                ]
            ],
        ],
    ]); ?>
</div>
