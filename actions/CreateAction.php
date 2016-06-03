<?php

namespace rangeweb\comments\actions;


use common\models\Settings;
use yii\base\Action;
use Yii;
use yii\helpers\Url;
use yii\web\BadRequestHttpException;
use yii\web\Response;
use yii\widgets\ActiveForm;
use rangeweb\comments\widgets\commentsWidget\CommentsWidget;
use rangeweb\comments\models\Comments;

class CreateAction extends Action
{
    public $callback;
    
    /**
     * @inheritdoc
     */
    public function run()
    {

        $model = new Comments(['scenario' => 'create']);
        Yii::$app->response->format = Response::FORMAT_JSON;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $level = Yii::$app->request->get('level');
            if ($level !== null) {
                $level = $level < CommentsWidget::MAX_LEVEL ? $level + 1 : CommentsWidget::MAX_LEVEL;
            } else {
                $level = 0;
            }

            if ($this->callback != null) {
                $this->callback($model);
            }
            return [
                'status' => true
            ];
        } else {
            return ['status' => false, 'errors' => ActiveForm::validate($model)];
        }
    }


    /**
     * @param $model
     * @return mixed
     * @throws InvalidConfigException
     */
    protected function callback($model)
    {
        if (!is_callable($this->callback)) {
            throw new InvalidConfigException('"' . get_class($this) . '::callback" should be a valid callback.');
        }
        $response = call_user_func($this->callback, $model);
        return $response;
    }
    
    
    /*public function run()
    {

        $model = new Comments(['scenario' => 'create']);
        Yii::$app->response->format = Response::FORMAT_JSON;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $level = Yii::$app->request->get('level');
            if ($level !== null) {
                $level = $level < CommentsWidget::MAX_LEVEL ? $level + 1 : CommentsWidget::MAX_LEVEL;
            } else {
                $level = 0;
            }
            return [
                    'success' => $this->controller->renderAjax('@vendor/range-web/yii2-rw-comments/widgets/commentsWidget/views/_index_item', [
                    'model' => $model,
                    'level' => $level,
                    'maxLevel' => CommentsWidget::MAX_LEVEL
                ])
            ];
        } else {
            return ['errors' => ActiveForm::validate($model)];
        }
    }*/
}
