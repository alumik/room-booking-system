<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\datetime\DateTimePicker;

/* @var $this yii\web\View */
/* @var $model frontend\models\RoomSearch */
/* @var $form yii\widgets\ActiveForm */

/* @author 钟震宇 <nczzy1997@gmail.com> */
?>

<div class="room-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => ['class' => 'row col-lg-4'],
    ]); ?>

    <?= $form->field($model, 'start_time_str')->widget(DateTimePicker::class, [
        'readonly' => true,
        'removeButton' => false,
        'options' => [
            'placeholder' => '选择开始时间',
            'autocomplete' => 'off',
        ],
        'pluginOptions' => [
            'autoclose' => true,
            'weekStart' => 1,
            'startDate' => date('Y-m-d H:i', time()),
            'endDate' => date('Y-m-d H:i', time() + 3600 * 24 * 30),
            'minuteStep' => 10,
        ]
    ]); ?>

    <?= $form->field($model, 'end_time_str')->widget(DateTimePicker::class, [
        'readonly' => true,
        'removeButton' => false,
        'options' => [
            'placeholder' => '选择结束时间',
            'autocomplete' => 'off',
        ],
        'pluginOptions' => [
            'autoclose' => true,
            'weekStart' => 1,
            'startDate' => date('Y-m-d H:i', time()),
            'endDate' => date('Y-m-d H:i', time() + 3600 * 24 * 30),
            'minuteStep' => 10,
        ]
    ]); ?>

    <div class="form-group">
        <?= Html::submitButton('查看可用房间', ['class' => 'btn btn-primary']) ?>
        <?= Html::a('重置搜索条件', ['index'], ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
