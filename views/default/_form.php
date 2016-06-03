<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use rangeweb\comments\models\Comments;
?>

<div class="comments-admin-form">

    <?php $form = ActiveForm::begin([
        'enableClientValidation' => true,
        'options' => [
            'class' => 'modal-ajax-update',
            'data-list' => 'comments-list'
        ]
    ]); ?>
    <div class="row">
        <div class="col-lg-12">
            <?= $form->field($model, 'object')->dropDownList(Comments::getObjects(1), array('prompt'=>'')) ?>
            <?= $form->field($model, 'object_id')->textInput() ?>
            <?= $form->field($model, 'user_id')->dropDownList(\common\modules\users\models\User::getUsersRoleUser(), ['prompt'=>'Выберите пользователя']) ?>
            <?= $form->field($model, 'date_create')->textInput(['class' => 'datepicker form-control']) ?>
            <?= $form->field($model, 'text')->textarea(['rows' => 5]) ?>
            <?= $form->field($model, 'show_main')->checkbox() ?>
            <?= $form->field($model, 'note')->textarea(['rows' => 3]) ?>
            <?= $form->field($model, 'status')->dropDownList(Comments::getStatusList(), array('prompt'=>'')) ?>
        </div>
    </div>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-primary']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>
