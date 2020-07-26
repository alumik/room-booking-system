<?php

use yii\helpers\Html;

/* @var $this yii\web\View */

/* @author 钟震宇 <nczzy1997@gmail.com> */
/* @author 张志毅 */

$this->title = Yii::$app->name;
?>

<script>
    console.log('一张网页，要经历怎样的过程，才能抵达用户面前？\n' +
        '一位新人，要经历怎样的成长，才能站在技术之巅？\n' +
        '探寻这里的秘密；\n' +
        '体验这里的挑战；\n' +
        '成为这里的主人；\n' +
        '加入我们，你，可以影响世界。');
</script>

<!-- 需要Cleanup -->
<link href="/favicon.ico" rel="shortcut icon">
<link href="/lib/font-awesome/css/font-awesome.min.css" rel="stylesheet">
<link href="/lib/animate-css/animate.min.css" rel="stylesheet">
<link href="/css/style.css" rel="stylesheet">

<div class="site-index">

    <section id="hero" class="blur">

        <div class="hero-container">

            <div class="wow fadeIn">

                <div class="hero-logo">
                    <img class="" src="/img/logo.png" alt="<?= Yii::$app->name ?>">
                </div>

                <h1><?= Yii::$app->name ?></h1>

                <h2>更加<span class="rotating">便利的操作, 简洁的流程</span></h2>

                <div class="actions">
                    <?= Html::a('查看说明', ['instruction'],  ['class' => 'btn btn-info']) ?>
                </div>

            </div>
        </div>
    </section>

</div>

<!-- 需要Cleanup -->
<script src="/lib/jquery/jquery.min.js"></script>
<script src="/lib/jquery/jquery-migrate.min.js"></script>
<script src="/lib/superfish/hoverIntent.js"></script>
<script src="/lib/superfish/superfish.min.js"></script>
<script src="/lib/morphext/morphext.min.js"></script>
<script src="/lib/wow/wow.min.js"></script>
<script src="/lib/stickyjs/sticky.js"></script>
<script src="/lib/easing/easing.js"></script>
<script src="/js/custom.js"></script>