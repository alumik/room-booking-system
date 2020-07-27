<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Admin */
/* @var $roles array */
/* @var $allRoles array */

$this->title = '修改管理员权限';
$this->params['breadcrumbs'][] = ['label' => '管理员管理', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->admin_id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="admin-privilege">
    <h1><?= Html::encode($this->title); ?></h1>
    <div class="admin-privilege-form">
        <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'admin_id_display', [
            'inputOptions' => [
                'class' => 'form-control',
                'value' => $model->admin_id,
            ]
        ])->textInput(['disabled' => true])->label('工号'); ?>

        <?= $form->field($model, 'admin_name_display', [
            'inputOptions' => [
                'class' => 'form-control',
                'value' => $model->admin_name,
            ]
        ])->textInput(['disabled' => true])->label('姓名'); ?>

        <p>
            <?= Html::checkboxList('newRoles', $roles, $allRoles); ?>
        </p>

        <div class="form-group">
            <?= Html::submitButton('保存', ['class' => 'btn btn-success']); ?>
        </div>

        <?php ActiveForm::end(); ?>
    </div>
</div>
