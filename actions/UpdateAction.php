<?php
namespace rangeweb\comments\actions;
use Yii;
use frontend\widgets\comments\models\Comment;
use yii\base\Action;
use yii\web\Response;
use yii\widgets\ActiveForm;

class UpdateAction extends Action
{
    /**
     * @inheritdoc
     */
    public function run()
    {
        $model = Comment::findOne(Yii::$app->request->get()['id']);

        if (Yii::$app->user->id == $model->author_id) {
            $model->setScenario('update');
            Yii::$app->response->format = Response::FORMAT_JSON;
            if ($model->load(Yii::$app->request->post()) && $model->save()) {


                return ['success' => $model['content']];
            } else {
                return ['errors' => ActiveForm::validate($model)];
            }
        } else {
            throw new \HttpException(403);
        }
    }
}
