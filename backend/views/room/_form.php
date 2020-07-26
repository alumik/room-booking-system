<?php

use common\models\Campus;
use common\models\RoomType;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Room */
/* @var $form yii\widgets\ActiveForm */

/* @author 钟震宇 <nczzy1997@gmail.com> */
?>

<div class="room-form col-lg-5 row">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'room_number')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'type')->dropDownList(
        RoomType::find()
            ->select(['type_name', 'id'])
            ->orderBy('id')
            ->indexBy('id')
            ->column(),
        ['prompt' => '请选择房间类型']) ?>

    <?= $form->field($model, 'campus')->dropDownList(
        Campus::find()
            ->select(['campus_name', 'id'])
            ->orderBy('id')
            ->indexBy('id')
            ->column(),
        ['prompt' => '请选择房间所在校区']) ?>

    <div class="form-group">
        <?= Html::submitButton('保存', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
