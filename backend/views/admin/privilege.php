<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Admin */
/* @var $roles array*/
/* @var $allRoles array*/

/* @author 钟震宇 <nczzy1997@gmail.com> */

$this->title = '修改权限：' . $model->admin_name;
$this->params['breadcrumbs'][] = ['label' => '管理员管理', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->admin_id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = '修改权限';
?>

<div class="admin-privilege">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="admin-privilege-form">

        <?php $form = ActiveForm::begin(); ?>

        <p>
            <?= Html::checkboxList('newRoles', $roles, $allRoles); ?>
        </p>

        <div class="form-group">
            <?= Html::submitButton('保存', ['class' => 'btn btn-success']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>

</div>
