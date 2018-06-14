<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\UserLoginForm */

/* @author 钟震宇 <nczzy1997@gmail.com> */

$this->title = '登录';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="site-login">

    <!-- delevopment stage only -->
    <div class="alert alert-info">
        <?= nl2br(Html::encode('提示：由于本网站仍处于开发阶段，并未接入学校数据库。新用户请在注册界面手动注册后使用。')) ?>
    </div>

    <h1><?= Html::encode($this->title) ?></h1>

    <p>请输入学号和密码登录系统：</p>

    <div class="row">
        <div class="col-lg-5">
            <?php $form = ActiveForm::begin(['id' => 'login-form']); ?>

                <?= $form->field($model, 'student_id')->textInput(['autofocus' => true]) ?>

                <?= $form->field($model, 'password')->passwordInput() ?>

                <?= $form->field($model, 'rememberMe')->checkbox() ?>

                <div style="color:#999;margin:1em 0">
                    忘记密码了？ <?= Html::a('重置密码', ['site/request-password-reset']) ?>
                </div>

                <div class="form-group">
                    <?= Html::submitButton('登录', ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
                </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
