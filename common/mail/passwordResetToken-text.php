<?php

/* @var $this yii\web\View */
/* @var $user common\models\User */

$resetLink = Yii::$app->urlManager->createAbsoluteUrl(['site/reset-password', 'token' => $user->password_reset_token]);
?>
你好 <?= $user->username ?>,

点击这条链接进行密码重置：

<?= $resetLink ?>
