<?php

use yii\helpers\Html;

/* @var $this yii\web\View */

$this->title = 'Новый отзыв';
$this->params['breadcrumbs'][] = ['label' => 'Отзывы', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="comments-admin-update">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>
