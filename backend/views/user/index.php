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

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            [
                'attribute' => 'student_id',
                'contentOptions'=>['width'=>'80px'],
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
                'template' => '{changestatus}',
                'buttons' => [
                    'changestatus' => function($url, $model, $key)
                    {
                        $options = [
                            'title' => '切换状态',
                            'aria-label' => '切换状态',
                            'data-pjax' => '0',
                        ];
                        return Html::a('<span class="glyphicon glyphicon-refresh"></span>', $url, $options);
                    },
                ]
            ],
        ],
    ]); ?>
</div>
