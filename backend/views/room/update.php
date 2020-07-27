<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Room */

$this->title = '修改房间信息';
$this->params['breadcrumbs'][] = ['label' => '房间管理', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="room-update">
    <h1><?= Html::encode($this->title); ?></h1>
    <?= $this->render('_form', [
        'model' => $model,
    ]); ?>
</div>
