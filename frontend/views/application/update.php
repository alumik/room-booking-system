<?php

/** @noinspection PhpUndefinedNamespaceInspection */
/** @noinspection PhpUndefinedClassInspection */

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\datetime\DateTimePicker;

/* @var $this yii\web\View */
/* @var $model common\models\Application */

$this->title = '修改预约申请：' . $model->id;
$this->params['breadcrumbs'][] = ['label' => '我的预约', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = '修改';
?>

<div class="application-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>注意：如果需要修改房间，请撤销申请后重新提交申请</p>

    <div class="application-form">

        <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'organization_name')
            ->textInput(['maxlength' => true])
            ->label('组织名（个人申请可留空）')
        ?>

        <?= $form->field($model, 'start_time')->widget(DateTimePicker::classname(), [
            'options' => ['placeholder' => '选择开始时间'],
            'readonly' => true,
            'pluginOptions' => [
                'autoclose' => true,
                'weekStart' => 1,
                'startDate' => date('Y-m-d H:i', time()),
                'endDate' => date('Y-m-d H:i', time() + 3600 * 24 * 30),
                'minuteStep' => 10,
            ]
        ]); ?>

        <?= $form->field($model, 'end_time')->widget(DateTimePicker::classname(), [
            'options' => ['placeholder' => '选择结束时间'],
            'readonly' => true,
            'pluginOptions' => [
                'autoclose' => true,
                'weekStart' => 1,
                'startDate' => date('Y-m-d H:i', time()),
                'endDate' => date('Y-m-d H:i', time() + 3600 * 24 * 30),
                'minuteStep' => 10,
            ]
        ]); ?>

        <?= $form->field($model, 'event')->textarea(['rows' => 6]) ?>

        <div class="form-group">
            <?= Html::submitButton('保存', ['class' => 'btn btn-success']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>

</div>
