<?php

namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\Application;
use common\models\ApplicationSearch;

/**
 * 前台 申请 控制器
 */
class ApplicationController extends Controller
{
    /**
     * {@inheritdoc}
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
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['view', 'index', 'update', 'delete', 'applicationdetail'],
                'rules' => [
                    [
                        'actions' => ['view', 'index', 'update', 'delete', 'applicationdetail'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    /**
     * 列出“我的预约”
     *
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ApplicationSearch();
        $searchModel->start_time_picker = date('Y-m-d H:i', time());
        $searchModel->end_time_picker = date('Y-m-d H:i', time() + 3600 * 24 *30);
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->query->andFilterWhere(['applicant_id' => Yii::$app->user->identity->getId()]);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * 查看与自己申请相冲突的申请详情
     *
     * @param integer $id
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionApplicationdetail($id)
    {
        return $this->render('application_detail', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * 我的预约申请详情
     *
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * 修改我的申请
     * 如果操作成功转到详情页
     *
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $model->start_time = date('Y-m-d H:i', $model->start_time);
        $model->end_time = date('Y-m-d H:i', $model->end_time);

        if ($model->load(Yii::$app->request->post())) {
            $model->start_time = strtotime($model->start_time);
            $model->end_time = strtotime($model->end_time);
            $model->status = Application::STATUS_PENDDING;
            if ($model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
            $model->start_time = date('Y-m-d H:i', $model->start_time);
            $model->end_time = date('Y-m-d H:i', $model->end_time);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * 撤销我的申请
     * 如果操作成功转到我的申请列表
     *
     * @param integer $id
     * @return \yii\web\Response
     * @throws NotFoundHttpException
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * 根据主键寻找申请模型
     * 如果未找到模型，抛出404异常
     *
     * @param integer $id
     * @return Application
     * @throws NotFoundHttpException
     */
    protected function findModel($id)
    {
        if (($model = Application::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('你所请求的页面不存在。');
    }
}
