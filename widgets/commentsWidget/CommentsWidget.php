<?php
namespace rangeweb\comments\widgets\commentsWidget;

use yii\base\Widget;
use rangeweb\comments\models\Comments;
use rangeweb\comments\widgets\commentsWidget\CommentsAsset;
use rangeweb\comments\widgets\commentsWidget\CommentsGuestAsset;

class CommentsWidget extends Widget
{
    /**
     * @var yii\db\ActiveRecord Экземпляр модели к которой привязываются комментарии.
     */
    public $model;
    /**
     * @var string Заголовок блока комментариев.
     */
    public $title;
    /**
     * @var string Текст кнопки отправки комментария.
     */
    public $sendButtonText;
    /**
     * @var string Текст кнопки отмены комментирования.
     */
    public $cancelButtonText;
    /**
     * @var string Текст кнопки добавления нового комментария.
     */
    public $createButtonTxt;
    /**
     * @var integer Масимальный визуальный уровень вложености комментариев.
     */
    const MAX_LEVEL = 1;

    public $showPublic = true;

    /**
     * @inheritdoc
     */
    public function init()
    {
        if ($this->title === null) {
            $this->title = 'Комментарии';
        }
        if ($this->sendButtonText === null) {
            $this->sendButtonText = 'Отправить';
        }
        if ($this->createButtonTxt === null) {
            $this->createButtonTxt = 'Добавить комментарий';
        }
        if ($this->cancelButtonText === null) {
            $this->cancelButtonText = 'Отмена';
        }
    }
    /**
     * @inheritdoc
     */
    public function run()
    {
        $model = self::baseComment();
        $model->showPublic = $this->showPublic;

        $models = $model->getComments();
        $this->registerClientScript();

        // Рендерим представление
        echo $this->render('index', [
            'id' => $this->getId(),
            'model' => $model,
            'models' => $models,
            'title' => $this->title,
            'level' => 0,
            'maxLevel' => self::MAX_LEVEL,
            'sendButtonText' => $this->sendButtonText,
            'cancelButtonText' => $this->cancelButtonText,
            'createButtonTxt' => $this->createButtonTxt
        ]);
    }

    protected function baseComment()
    {
        $model = new Comments(['scenario' => 'create']);
        $model->object = \Yii::$app->controller->module->id;
        $model->object_id = $this->model->id;
        return $model;
    }

    public function registerClientScript()
    {
        $view = $this->getView();

        if (!\Yii::$app->user->isGuest) {
            CommentsAsset::register($view);
            $view->registerJs("jQuery('#comment-form').comments();");
        } else {
            CommentsGuestAsset::register($view);
        }
    }
} 