<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

use yii\bootstrap\Modal;

?>
<?php
$form = ActiveForm::begin([
    'id' => 'comment-form',
    'action' => ['/site/add-comment'],
    'fieldConfig' => [
        'template' => "{input}\n{hint}\n{error}"
    ],
    'enableClientValidation' => true,
]);
?>
<?php Modal::begin([
    'header' => '<h4>Новый отзыв</h4>',
    'footer' => Html::button($cancelButtonText, [
        'class' => 'btn btn-warning cancel pull-right',
            'data-dismiss' => 'modal'
    ]).' '.Html::submitInput($sendButtonText, [
            'class' => 'btn btn-primary pull-right'
        ]),
    'id' =>'modal-new-comment',
]);?>
    <div class="row">
        <div class="col-md-12">
            <?=$form->field($model, 'text')->textarea(['rows'=>6,'maxlength'=> $this->context->maxLength])?>
            <?=Html::activeHiddenInput($model, 'object')?>
            <?=Html::activeHiddenInput($model, 'object_id')?>
            <?=Html::activeHiddenInput($model, 'parent_id')?>
            <?=Html::hiddenInput('level', null, ['id' => 'comment-level'])?>
        </div>
    </div>

<?php Modal::end(); ?>

<?php
ActiveForm::end();
?>




