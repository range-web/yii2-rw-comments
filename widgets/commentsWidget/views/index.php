<?php
$count = $dataProvider->getTotalCount();

$title = str_replace('{title}',$title, $this->context->titleTemplate);
$title = str_replace('{count}',$count, $title);

$canNewComment = true;
if ($this->context->object == 'User' && $this->context->object_id == Yii::$app->user->id) {
    $canNewComment = false;
}

?>

<div id="comments-widget">
    <?= $title ?>

    <?php if (!\Yii::$app->user->isGuest && $canNewComment) { ?>
        <?if($this->context->createBtuttonPosition == 'top'):?>
            <a href="#" class="btn btn-primary pull-right create-comment"><?= $createButtonTxt ?></a>
        <?endif;?>
        <?= $this->render('_form', [
            'model' => $model,
            'sendButtonText' => $sendButtonText,
            'cancelButtonText' => $cancelButtonText
        ]) ?>
    <?php } ?>

        <div class="comments col-md-12 mb-20">
            <?php if ($count > 0) : ?>
                <?=
                \yii\widgets\ListView::widget([
                    'dataProvider' => $dataProvider,
                    'layout' => '{items}{pager}',
                    'itemView' => '_indexItem',
                ]);
                ?>
<!--                --><?//= $this->render('_index_loop', [
//                    'models' => $models,
//                    'level' => $level,
//                    'maxLevel' => $maxLevel
//                ]) ?>
            <?php else : ?>
                Отзывов пока нет
            <?php endif; ?>
        </div>
        <?if(!\Yii::$app->user->isGuest && $this->context->createBtuttonPosition == 'bottom' && $canNewComment):?>
            <div class="">
                <a href="#" class="btn btn-primary pull-right create-comment"><?= $createButtonTxt ?></a>
            </div>
        <?endif;?>
</div>