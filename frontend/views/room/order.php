<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Application */

$this->title = '预约房间';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="application-create">
    <h1><?= Html::encode($this->title); ?></h1>
    <div class="application-form">
        <?php $form = ActiveForm::begin(); ?>
        <?= $form->field($model, 'room_number', [
            'inputOptions' => [
                'class' => 'form-control',
                'value' => $model->room->campus0->campus_name . ' ' . $model->room->room_number,
            ]
        ])->textInput(['disabled' => true])->label('房间'); ?>

        <?= $form->field($model, 'organization_name')
            ->textInput(['maxlength' => true])
            ->label('组织名（个人申请可不填）'); ?>

        <?= $form->field($model, 'start_time', [
            'inputOptions' => [
                'class' => 'form-control',
                'value' => date('Y-m-d H:i', $model->start_time)
            ]
        ])->textInput(['disabled' => true]); ?>

        <?= $form->field($model, 'end_time', [
            'inputOptions' => [
                'class' => 'form-control',
                'value' => date('Y-m-d H:i', $model->end_time)
            ]
        ])->textInput(['disabled' => true]); ?>

        <?= $form->field($model, 'event')->textarea(['rows' => 6]); ?>

        <div class="form-group">
            <?= Html::submitButton('提交申请', ['class' => 'btn btn-success']); ?>
        </div>

        <?php ActiveForm::end(); ?>
    </div>
</div>
