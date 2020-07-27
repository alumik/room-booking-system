<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\ResetPasswordForm */
/* @var $admin common\models\Admin */

$this->title = '修改管理员密码';
$this->params['breadcrumbs'][] = ['label' => '管理员管理', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $admin->admin_id, 'url' => ['view', 'id' => $admin->id]];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="admin-reset-password">
    <h1><?= Html::encode($this->title); ?></h1>
    <div class="row col-lg-5">
        <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'password')->passwordInput(['maxlength' => true]); ?>

        <?= $form->field($model, 'password2')->passwordInput(['maxlength' => true]); ?>

        <div class="form-group">
            <?= Html::submitButton('保存', ['class' => 'btn btn-success']); ?>
        </div>

        <?php ActiveForm::end(); ?>
    </div>
</div>
