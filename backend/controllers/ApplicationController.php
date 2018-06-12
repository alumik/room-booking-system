<?php

namespace backend\controllers;

use Yii;
use common\models\Application;
use common\models\ApplicationSearch;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ApplicationController implements the CRUD actions for Application model.
 */
class ApplicationController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Application models.
     * @return mixed
     */
    public function actionIndex()
    {
        if (!Yii::$app->user->can('manageRoom')) {
            throw new ForbiddenHttpException('对不起，你没有进行该操作的权限。');
        }

        $searchModel = new ApplicationSearch();
        $searchModel->start_time_picker = date('Y-m-d H:i', time());
        $searchModel->end_time_picker = date('Y-m-d H:i', time() + 3600 * 24 *30);
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Application model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        if (!Yii::$app->user->can('manageRoom')) {
            throw new ForbiddenHttpException('对不起，你没有进行该操作的权限。');
        }

        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Finds the Application model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Application the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Application::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionApprove($id)
    {
        $model = $this->findModel($id);

        $model->status = Application::STATUS_APPROVED;

        try {
            $model->save();
        } catch (yii\db\Exception $e) {
            return $this->render('sqlerror');
        }

        return $this->redirect(['index']);
    }

    public function actionReject($id)
    {
        $model = $this->findModel($id);

        $model->status = Application::STATUS_REJECTED;

        $model->save();

        return $this->redirect(['index']);
    }
}
