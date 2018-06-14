<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Admin */

/* @author 钟震宇 <nczzy1997@gmail.com> */

$this->title = '新增管理员';
$this->params['breadcrumbs'][] = ['label' => '管理员管理', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="admin-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="row col-lg-5">

        <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'admin_id')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'admin_name')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'password')->passwordInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'password2')->passwordInput(['maxlength' => true]) ?>

        <div class="form-group">
            <?= Html::submitButton('新增', ['class' => 'btn btn-success']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>

</div>
