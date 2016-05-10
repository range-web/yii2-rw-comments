<?php
namespace rangeweb\comments\actions;
use Yii;
use frontend\widgets\comments\models\Comment;
use yii\base\Action;
use yii\web\Response;


class DeleteAction extends Action
{
    /**
     * @inheritdoc
     */
    public function run()
    {
        $model = Comment::findOne(Yii::$app->request->get()['id']);

        if (Yii::$app->user->id == $model->author_id) {
            $model->setScenario('delete');
            if ($model->save(false)) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return ['success' => 'Комментарий удален'];
            }
        } else {
            throw new \HttpException(403);
        }
    }
}
