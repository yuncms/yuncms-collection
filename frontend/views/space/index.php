<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ListView;
use yii\bootstrap\Nav;
use yuncms\user\models\User;

/**
 * @var \yii\web\View $this
 * @var  User $model
 * @var string $type
 * @var \yii\data\ActiveDataProvider $dataProvider
 */
$this->context->layout = '@yuncms/space/frontend/views/layouts/space';
$this->params['user'] = $model;

if ($type == 'questions') {
    $what = Yii::t('collection', 'Questions');
} elseif ($type == 'articles') {
    $what = Yii::t('collection', 'Articles');
} else {
    $what = '';
}
if (!Yii::$app->user->isGuest && Yii::$app->user->id == $model->id) {//Me
    $this->title = Yii::t('collection', '{who} collected {what}', [
        'who' => Yii::t('collection', 'My'),
        'what' => $what,
    ]);
} else {
    $this->title = Yii::t('collection', '{who} collected {what}', [
        'who' => Html::encode($model->nickname),
        'what' => $what
    ]);
}
?>
<div class="stream-following">

    <?= Nav::widget([
        'options' => ['class' => 'nav nav-tabs'],
        'items' => [
            //问答
            ['label' => Yii::t('collection', 'Questions'), 'url' => ['/collection/space/index', 'id' => $model->id, 'type' => 'questions'], 'visible' => Yii::$app->hasModule('question')],
            //文章
            ['label' => Yii::t('collection', 'Articles'), 'url' => ['/collection/space/index', 'id' => $model->id, 'type' => 'articles'], 'visible' => Yii::$app->hasModule('article')],
            //直播
            ['label' => Yii::t('collection', 'Lives'), 'url' => ['/collection/space/index', 'id' => $model->id, 'type' => 'lives'], 'visible' => Yii::$app->hasModule('live')],
            //直播
            ['label' => Yii::t('collection', 'Notes'), 'url' => ['/collection/space/index', 'id' => $model->id, 'type' => 'notes'], 'visible' => Yii::$app->hasModule('note')],
        ],
    ]); ?>

    <?php if ($type == 'lives') {
        $tag = 'div';
        $class = 'live-box';
        $options = [
            'tag' => 'div',
            'class' => 'live-box'
        ];
        $itemOptions = ['tag' => 'div', 'class' => 'live'];
        \yuncms\live\frontend\assets\LiveAsset::register($this);
    } else {
        $options = [
            'tag' => 'div',
            'class' => 'stream-list question-stream mt-10'
        ];
        $itemOptions = ['tag' => 'section', 'class' => 'stream-list-item'];
    } ?>

    <?= ListView::widget([
        'dataProvider' => $dataProvider,
        'itemOptions' => $itemOptions,
        'itemView' => '_item',//子视图
        'viewParams' => ['type' => $type],
        'layout' => "{items}\n{pager}",
        'options' => $options
    ]); ?>
</div>





