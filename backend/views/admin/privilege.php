<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Admin */
/* @var $authAssignmentsArray array*/
/* @var $allPrivilegesArray array*/

$this->title = '权限设置：' . $model->admin_name;
$this->params['breadcrumbs'][] = ['label' => '管理员管理', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->admin_id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = '权限设置';
?>
<div class="admin-privilege">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="admin-privilege-form">

        <?php $form = ActiveForm::begin(); ?>
        <br/>
        <?= Html::checkboxList('newPri', $authAssignmentsArray, $allPrivilegesArray); ?>
        <br/>
        <div class="form-group">
            <?= Html::submitButton('保存', ['class' => 'btn btn-success']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>

</div>
