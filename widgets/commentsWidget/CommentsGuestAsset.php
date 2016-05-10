<?php
namespace rangeweb\comments\widgets\commentsWidget;
use yii\web\AssetBundle;

class CommentsGuestAsset extends AssetBundle
{
    public $sourcePath = '@vendor/range-web/yii2-rw-comments/widgets/commentsWidget/assets\';';
    public $css = [
        'css/comments.css'
    ];
}