<?php
/**
 * Представление одного комментария в цикле всех записей.
 */
use yii\helpers\Html;
$author = $model->author;
$class = 'lvl-' . $level;
?>


    <li>
        <div class="comment <?= $class ?>" id="comment-<?= $model['id'] ?>" data-parent="<?= $model['parent_id'] ?>">
            <div class="comment-author">
                <?= Html::a('', '/profile/'.$model->user_id, [
                    'style' => "background-image: url('".$author->getAvatarImageUrl([250, 250])."')"
                ]) ?>
            </div>

            <div class="comment-content">
                <div class="comment-meta">
                    <div class="comment-meta-author">
                        <?= Html::a($author->getFio(), '/profile/'.$model->user_id) ?>
                    </div>
                    <div class="comment-meta-date">
                        <span><?= date('d.m.Y', strtotime($model->createTime)) ?></span>
                    </div>
                </div>

                <div class="comment-body">
                    <?= Html::encode($model->contentText); ?>
                </div>
            </div>
        </div>
    </li>