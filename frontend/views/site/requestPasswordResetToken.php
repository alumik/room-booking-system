<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\PasswordResetRequestForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = '密码重置申请';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-request-password-reset">
    <h1><?= Html::encode($this->title) ?></h1>

    <p>请输入你注册时使用的电子邮箱地址，我们会给你发送一封带有密码重置链接的邮件</p>

    <div class="row">
        <div class="col-lg-5">
            <?php $form = ActiveForm::begin(['id' => 'request-password-reset-form']); ?>

                <?= $form->field($model, 'email')->textInput(['autofocus' => true, 'spellcheck' => false]) ?>

                <div class="form-group">
                    <?= Html::submitButton('发送', ['class' => 'btn btn-primary']) ?>
                </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
