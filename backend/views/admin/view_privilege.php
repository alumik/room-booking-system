<?php

/** @noinspection PhpUnhandledExceptionInspection */

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\AuthItemSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '权限对照表';
$this->params['breadcrumbs'][] = ['label' => '管理员管理', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="auth-item-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <div class="scrollable">
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            [
                'attribute' => 'description',
                'label' => '管理员角色',
                'contentOptions' => ['align' => 'center'],
                'headerOptions' => ['class' => 'text-center'],
            ],
            [
                'attribute' => 'priViewStudentList',
                'label' => '浏览学生列表',
                'contentOptions' => ['width' => '12%', 'align' => 'center'],
                'headerOptions' => ['class' => 'text-center'],
            ],
            [
                'attribute' => 'priManageStudent',
                'label' => '修改学生',
                'contentOptions' => ['width' => '12%', 'align' => 'center'],
                'headerOptions' => ['class' => 'text-center'],
            ],
            [
                'attribute' => 'priViewAdminList',
                'label' => '浏览管理员列表',
                'contentOptions' => ['width' => '12%', 'align' => 'center'],
                'headerOptions' => ['class' => 'text-center'],
            ],
            [
                'attribute' => 'priManageAdmin',
                'label' => '修改管理员',
                'contentOptions' => ['width' => '12%', 'align' => 'center'],
                'headerOptions' => ['class' => 'text-center'],
            ],
            [
                'attribute' => 'priManageRoom',
                'label' => '管理预约',
                'contentOptions' => ['width' => '12%', 'align' => 'center'],
                'headerOptions' => ['class' => 'text-center'],
            ],
            [
                'attribute' => 'priManagePermission',
                'label' => '管理权限',
                'contentOptions' => ['width' => '12%', 'align' => 'center'],
                'headerOptions' => ['class' => 'text-center'],
            ],
        ],
    ]); ?>
    </div>
</div>
