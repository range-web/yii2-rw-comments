<?php
$count = count($models);
?>

<div id="comments-widget">
    <h2 id="comments"><?= $title ?> (<?=$count?>)</h2>

    <?php if (!\Yii::$app->user->isGuest) { ?>
        <a href="#" class="btn btn-primary pull-right create"><?= $createButtonTxt ?></a>
    <?php } ?>


        <div class="comments col-md-12">
            <?php if ($count > 0) : ?>
                <?= $this->render('_index_loop', [
                    'models' => $models,
                    'level' => $level,
                    'maxLevel' => $maxLevel
                ]) ?>
            <?php else : ?>
                Отзывов пока нет
            <?php endif; ?>
        </div>

        <?php if (!\Yii::$app->user->isGuest) { ?>
            <div class="hide">
                <?= $this->render('_form', [
                    'model' => $model,
                    'sendButtonText' => $sendButtonText,
                    'cancelButtonText' => $cancelButtonText
                ]) ?>
            </div>
        <?php } ?>
    

</div>