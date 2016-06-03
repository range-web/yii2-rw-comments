<?php
namespace rangeweb\comments\widgets\commentsWidget;

use Yii;
use rangeweb\comments\models\search\CommentsSearch;
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

    public $object;
    public $object_id;

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
    public $showMain = false;
    public $maxLength = 255;
    
    public $createComment = true;
    
    public $titleTemplate = '<h2>{title}</h2>';
    public $createBtuttonPosition = 'top';

    public $paginationParams = [];
    
    public $orderBy;
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

        if ($this->model != null) {
            $class = get_class($this->model);
            $arClass = explode('\\', $class);
            $this->object = end($arClass);

            $this->object_id = $this->model->id;
        }
    }
    /**
     * @inheritdoc
     */
    public function run()
    {
        $model = self::baseComment();
        //$model->showPublic = $this->showPublic;

        //$models = $model->getComments();
        $this->registerClientScript();

        $searchModel = new CommentsSearch();
        $searchModel->object = $this->object;
        $searchModel->object_id = $this->object_id;

        if ($this->orderBy != null) {
            $searchModel->orderBy = $this->orderBy;
        }
        
        if ($this->showPublic) {
            $searchModel->status = Comments::STATUS_PUBLIC;
        }

        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, $this->paginationParams);
        
        // Рендерим представление
        echo $this->render('index', [
            'id' => $this->getId(),
            'model' => $model,
            //'models' => $models,
            'dataProvider' => $dataProvider,
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
        $model->object = $this->object;
        $model->object_id = $this->object_id;
        return $model;
    }

    public function registerClientScript()
    {
        $view = $this->getView();

        if (!\Yii::$app->user->isGuest) {
            CommentsAsset::register($view);
            //$view->registerJs("jQuery('#comment-form').comments();");
            
            $maxLength = $this->maxLength;
            
            $view->registerJs("jQuery(document).on('keyup', '#comments-text', function() {
                    if (this.value.length > {$maxLength}) {
                        this.value = this.value.substr(0, {$maxLength});
                    }
                    var maxLengthText = 'Осталось: <span>'+({$maxLength}-this.value.length)+'</span> зн.';
                    var maxLengthWrapper = jQuery('.max-length-count-wrapper');
                    
                    if (maxLengthWrapper.length == 0) {
                        jQuery('#comments-text').after('<div class=\"max-length-count-wrapper\">'+maxLengthText+'</div>');
                    } else {
                        maxLengthWrapper.html(maxLengthText);
                    }
            });");
        } else {
            //CommentsGuestAsset::register($view);
        }
    }
} 