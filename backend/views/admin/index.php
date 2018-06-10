<?php

/** @noinspection PhpUnusedParameterInspection */
/** @noinspection PhpUnhandledExceptionInspection */

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\AdminSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '管理员';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="admin-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('新增管理员', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            [
                'attribute' => 'admin_id',
                'contentOptions'=>['width'=>'120px'],
            ],
            'admin_name',
            'email:email',
            [
                'attribute' => 'created_at',
                'format' => ['date', 'php: Y-m-d H:i:s'],
                'filter' => '',
            ],
            [
                'attribute' => 'updated_at',
                'format' => ['date', 'php: Y-m-d H:i:s'],
                'filter' => '',
            ],

            [
                'class' => 'yii\grid\ActionColumn',
                'contentOptions'=>['align'=>'center'],
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
                            'title' => '权限设置',
                            'aria-label' => '权限设置',
                            'data-pjax' => '0',
                        ];
                        return Html::a('<span class="glyphicon glyphicon-user"></span>', $url, $options);
                    }
                ]
            ],
        ],
    ]); ?>
</div>
