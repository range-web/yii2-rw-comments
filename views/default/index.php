<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\modules\users\models\User;
use rangeweb\comments\models\Comments;
use yii\bootstrap\Modal;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel backend\modules\users\models\search\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
Comments::countModerateComments();
$this->title = 'Отзывы';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="comments-index">

    <?php Modal::begin([
        'header' => '<h2></h2>',
        'id' =>'modal-new-item',
        //'size' => 'modal'
    ]);?>
    <div id="modal-content"></div>
    <?php Modal::end(); ?>

    <p>
        <?= Html::a('Добавить отзыв', ['create'], [
            'data-original-title'=>'Новый отзыв',
            'data-header'=>'Новый отзыв',
            'class' => 'btn btn-success btn-xs btn-open-modal'
        ]) ?>
    </p>

    <?php Pjax::begin(['id' => 'comments-list','timeout'=>10000]); ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute' => 'user_id',
                'format' => 'html',
                'value' => function($model){
                    $author = $model->author;
                    return Html::a($author->firstname .' '.$author->lastname, ['/users/default/update', 'id'=>$author->id]);
                }
            ],
            [
                'attribute' => 'object',
                'format' => 'html',
                'filter' => Html::activeDropDownList(
                    $searchModel,
                    'object',
                    Comments::getObjects(1),
                    ['class' => 'form-control', 'prompt' => 'Все']
                ),
                'value' => function($model){
                    return Html::a(Comments::getObjectTitle($model->object) . ' #'.$model->object_id, ['/'.$model->object.'/default/update', 'id'=>$author->id]);
                }
            ],
            'date_create',
            'text',
            'note',
            [
                'attribute' => 'status',
                'filter' => Html::activeDropDownList(
                    $searchModel,
                    'status',
                    Comments::getStatusList(),
                    ['class' => 'form-control', 'prompt' => 'Все']
                ),
                'value' => function($model){
                    return $model->getStatusTitle();
                }
            ],
            'show_main',
            /*[
                'attribute' => 'status',
                'filter' => Html::activeDropDownList(
                    $searchModel,
                    'status',
                    [],
                    ['class' => 'form-control', 'prompt' => 'Все']
                ),
                'value' => function($model){
                    //return $model->getRoleTitleByRole($model->role_id);
                }
            ],*/

            [
                'class' => 'yii\grid\ActionColumn',
                'headerOptions' => ['style'=>'width:70px'],
                'template' => '{update} {delete}',
                'buttons' => [
                    'update' => function($url, $model){
                        return Html::a('<i class="fa fa-edit"></i>',$url, ['data-original-title'=>'Редактировать','class'=>'btn btn-info btn-minier tooltip-info  btn-open-modal', 'data-header'=>'Редактирование', 'data-rel'=>'tooltip']);
                    },
                    'delete' => function($url, $model){
                        return Html::a('<i class="fa fa-trash"></i>',$url, ['data-original-title'=>'Удалить','class'=>'btn btn-danger btn-minier tooltip-error', 'data-rel'=>'tooltip', 'data-confirm'=>"Вы уверены, что хотите удалить этого пользователя?", 'data-method'=>'post', 'data-pjax'=>0]);
                    },
                ]
            ],        ],
    ]); ?>
    <?php Pjax::end(); ?>
</div>
