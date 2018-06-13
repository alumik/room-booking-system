<?php

/** @noinspection PhpUnhandledExceptionInspection */

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Room */

$this->title = $model->room_number;
$this->params['breadcrumbs'][] = ['label' => '房间管理', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="room-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('修改信息', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('删除', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => '你确定要删除该房间吗？',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'room_number',
            [
                'attribute' => 'type',
                'value' => $model->type0->type_name,
                'captionOptions' => ['width' => '20%'],
            ],
            [
                'attribute' => 'campus',
                'value' => $model->campus0->campus_name,
            ],
            [
                'attribute' => 'available',
                'value' => $model->getStatusStr(),
                'contentOptions' => $model->getStatusBg(),
            ],
        ],
    ]) //TODO: 预定列表?>

</div>