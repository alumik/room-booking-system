<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Room */

$this->title = '修改房间信息：' . $model->room_number;
$this->params['breadcrumbs'][] = ['label' => '房间管理', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->room_number, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = '修改';
?>
<div class="room-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
