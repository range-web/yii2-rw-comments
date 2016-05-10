<?php
/**
 * Представление одного комментария в цикле всех записей.
 */
use yii\helpers\Html;

$author = $model->author;

$class = 'lvl-' . $level;
if ($model->isDeleted) {
    $class .= ' deleted';
}
if ($model->isBanned) {
    $class .= ' banned';
} ?>


    <li>
        <div class="comment <?= $class ?>" id="comment-<?= $model['id'] ?>" data-parent="<?= $model['parent_id'] ?>">
            <div class="comment-author">
                <a href="#" style="background-image: url('<?=$author->getAvatarImageUrl([250, 250])?>');"></a>
            </div>

            <div class="comment-content">
                <div class="comment-meta">
                    <div class="comment-meta-author">
                        <a href="#"><?= $author->getFio() ?></a>
                    </div>

                    <div class="comment-meta-date">
                        <span>
                            <time pubdate datetime="<?= $model->createTime ?>"><?= $model->createTime ?></time>
                        </span>
                    </div>
                </div>

                <div class="comment-body">
                    <?= $model->contentText ?>
                </div>
            </div>
        </div>
    </li>


    <div class="<?= $class ?>" id="comment-<?= $model['id'] ?>" data-parent="<?= $model['parent_id'] ?>">
        <div class="col-sm-2 small-avatar">
            <?//= Html::img($model->author->avatarLink) ?>
        </div>
        <div class="col-sm-10">
            <p class="author">
                <?= $model->author->getFio() ?> <time pubdate datetime="<?= $model->createTime ?>"><?= $model->createTime ?></time> <?php if ($model['parent_id']) { ?>
                    <?= Html::a('<span class="glyphicon glyphicon-arrow-up"></span>', '#comment-' . $model['parent_id'], ['class' => 'parent-link']) ?>
                <?php } ?>
            </p>

            <div class="content"><?= $model->contentText ?></div>
            <?php if ($model->isPublished && !\Yii::$app->user->isGuest) { ?>
                <p class="manage">

                    <a href="#" class="reply" data-id="<?= $model['id'] ?>" data-level="<?= $level ?>">
                        Ответить
                    </a>
                    <?php if (Yii::$app->user->id == $model->user_id) { ?>
                        <span>&nbsp;&nbsp;</span>
                        <a href="#" class="update" data-id="<?= $model['id']; ?>" data-href="<?= \yii\helpers\Url::to(['/site/update-comment', 'id'=>$model['id']]) ?>"><i class="fa fa-edit"></i></a>
                    <?php } ?>
                    <?php if (Yii::$app->user->id == $model->user_id) { ?>
                        <span>&nbsp;&nbsp;</span>
                        <a href="#" class="delete" data-id="<?= $model['id']; ?>" data-href="<?= \yii\helpers\Url::to(['/site/delete-comment', 'id'=>$model['id']]) ?>" data-confirm="Вы уверены что хотите удалить комментарий?"><i class="fa fa-trash-o"></i></a>
                    <?php } ?>
                </p>
            <?php } ?>
        </div>
    </div>

<?php if ($model->children) {
    if ($level < $maxLevel) {
        $level++;
    }
    echo $this->render('_index_loop', [
        'models' => $model->children,
        'level' => $level,
        'maxLevel' => $maxLevel
    ]);
}