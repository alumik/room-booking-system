<?php

/** @noinspection PhpUnhandledExceptionInspection */

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Admin */

$this->title = $model->admin_name;
$this->params['breadcrumbs'][] = ['label' => '管理员', 'url' => ['index']];
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
                'confirm' => '确定要删除该管理员吗？',
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
                'attribute' => 'created_at',
                'format' => ['date', 'php: Y-m-d H:i:s'],
            ],
            [
                'attribute' => 'updated_at',
                'format' => ['date', 'php: Y-m-d H:i:s'],
            ],
        ],
    ]) ?>

</div>
