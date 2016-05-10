<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$form = ActiveForm::begin([
    'id' => 'comment-form',
    'action' => ['/site/add-comment'],
    'fieldConfig' => [
        'template' => "{input}\n{hint}\n{error}"
    ],
    'enableClientValidation' => false,
    'enableAjaxValidation' => false,
    'validateOnChange' => false
]);
?>
<div class="col-md-12 mb-30">
    <?=$form->field($model, 'text')->textarea(['rows'=>6])?>
    <?=Html::activeHiddenInput($model, 'object')?>
    <?=Html::activeHiddenInput($model, 'object_id')?>
    <?=Html::activeHiddenInput($model, 'parent_id')?>
    <?=Html::hiddenInput('level', null, ['id' => 'comment-level'])?>

    <?=Html::button($cancelButtonText, [
        'class' => 'btn btn-warning cancel pull-right'
    ]) ?>
    <?=Html::submitInput($sendButtonText, [
        'class' => 'btn btn-primary pull-right'
    ])?>
</div>


<?php
ActiveForm::end();