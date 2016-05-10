<?php
namespace rangeweb\comments\widgets\commentsWidget;
use yii\web\AssetBundle;

class CommentsAsset extends AssetBundle
{
    public $sourcePath = '@vendor/range-web/yii2-rw-comments/widgets/commentsWidget/assets';
    public $js = [
        'js/comments.js'
    ];
    public $depends = [
        'yii\web\JqueryAsset'
    ];
}