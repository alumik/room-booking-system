<?php

/** @noinspection PhpUnhandledExceptionInspection */

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Admin */

/* @author 钟震宇 <nczzy1997@gmail.com> */

$this->title = '管理员：' . $model->admin_name;
$this->params['breadcrumbs'][] = ['label' => '管理员管理', 'url' => ['index']];
$this->params['breadcrumbs'][] = $model->admin_id;
?>

<div class="admin-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('修改信息', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('修改密码', ['resetpwd', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('修改权限', ['privilege', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('删除', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => '你确定要删除该管理员吗？',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'template' => "<tr><th width='20%'>{label}</th><td width='80%'>{value}</td></tr>",
        'model' => $model,
        'attributes' => [
            'admin_id',
            'admin_name',
            'email:email',
            [
                'label' => '管理员角色',
                'value' => $model->getRolesDescription(),
            ],
            [
                'attribute' => 'created_at',
                'format' => ['date', 'php: Y-m-d H:i'],
            ],
            [
                'attribute' => 'updated_at',
                'format' => ['date', 'php: Y-m-d H:i'],
            ],
        ],
    ]) ?>

</div>
