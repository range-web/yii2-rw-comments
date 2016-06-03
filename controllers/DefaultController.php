<?php
namespace rangeweb\comments\controllers;
use backend\components\AdminController;
use rangeweb\comments\models\Comments;
use rangeweb\comments\models\search\CommentsSearch;
use Yii;
use yii\web\Controller;
use rangeweb\comments\models\Comment;
use yii\web\Response;

class DefaultController extends AdminController
{
    public function actionIndex($status=null, $object=null)
    {
        $searchModel = new CommentsSearch();

        if ($status != null) {
            $searchModel->status = $status;
        }

        if ($object != null) {
            $searchModel->object = $object;
        }

        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Updates an existing Comments model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionCreate($user_id = null, $object = null, $object_id = null)
    {
        $model = new Comments();
        $model->setScenario('admin-update');

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return 'OK';
        } else {

            $model->date_create = date('d.m.Y H:i:s');

            if ($user_id != null) {
                $model->user_id = $user_id;
            }

            if ($object != null && $object_id != null) {
                $model->object = $object;
                $model->object_id = $object_id;
            }

            if (Yii::$app->request->isAjax) {
                return $this->renderAjax('create', [
                    'model' => $model,
                ]);
            }
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }



   

    /**
     * Updates an existing Comments model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $model->setScenario('admin-update');
        
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return 'OK';
        } else {
            if (Yii::$app->request->isAjax) {
                return $this->renderAjax('update', [
                    'model' => $model,
                ]);
            }
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Comments model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        if (Yii::$app->getModule('comments')->deleteMode == 'archive') {
            $model = $this->findModel($id);
            $model->status = Comments::STATUS_DELETE;
            $model->save();
        } else {
            $this->findModel($id)->delete();
        }
        

        return $this->redirect(['index']);
    }
    
    
    public function actionCountModerate()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        
        return Comments::countModerateComments();
    }

    /**
     * Finds the Comments model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Comments::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}