<?php

use common\models\UserLoginForm;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model UserLoginForm */

$this->title = '登录';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="site-login">
    <!-- development stage only -->
    <?= Yii::$app->session->setFlash('info', '提示：本网站仍处于开发阶段，并未接入学校数据库。请新用户在注册页面手动注册后再尝试登录。'); ?>
    <h1><?= Html::encode($this->title); ?></h1>
    <p>请输入学号和密码登录系统：</p>
    <div class="row">
        <div class="col-lg-5">
            <?php $form = ActiveForm::begin(['id' => 'login-form']); ?>

            <?= $form->field($model, 'student_id')->textInput(['autofocus' => true]); ?>

            <?= $form->field($model, 'password')->passwordInput(); ?>

            <?= $form->field($model, 'rememberMe')->checkbox(); ?>

            <div style="color:#999;margin:1em 0">
                忘记密码了？ <?= Html::a('重置密码', ['site/request-password-reset']); ?>
            </div>

            <div class="form-group">
                <?= Html::submitButton('登录', ['class' => 'btn btn-primary', 'name' => 'login-button']); ?>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
