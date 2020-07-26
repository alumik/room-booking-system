<?php

namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\ApplicationSearch;
use common\models\Application;
use common\models\Room;
use frontend\models\RoomSearch;
use yii\web\Response;

class RoomController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
            'access' => [
                'class' => AccessControl::class,
                'only' => ['index', 'view', 'order'],
                'rules' => [
                    [
                        'actions' => ['index', 'view', 'order'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    /**
     * 查看可预约房间列表
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new RoomSearch();

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $searchModel->search(Yii::$app->request->queryParams),
        ]);
    }

    /**
     * 查看该房间详情及与我的申请冲突的申请列表
     *
     * @param integer $id
     * @param string $startTime
     * @param string $endTime
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionView($id, $startTime, $endTime)
    {
        $searchModel = new ApplicationSearch();
        $searchModel->start_time_picker = $startTime;
        $searchModel->end_time_picker = $endTime;
        $searchModel->room_id = $id;
        $searchModel->status = Application::STATUS_APPROVED;

        return $this->render('view', [
            'model' => $this->findModel($id),
            'dataProvider' => $searchModel->search(Yii::$app->request->queryParams),
        ]);
    }

    /**
     * 提交新申请
     * 如果操作成功转到详情页
     *
     * @param integer $id
     * @param integer $startTime
     * @param integer $endTime
     * @return string|Response
     */
    public function actionOrder($id, $startTime, $endTime)
    {
        $model = new Application();
        $model->room_id = $id;
        $model->start_time = $startTime;
        $model->end_time = $endTime;
        $model->status = Application::STATUS_PENDING;
        $model->applicant_id = Yii::$app->user->identity->getId();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['application/view', 'id' => $model->id]);
        }

        return $this->render('order', [
            'model' => $model,
        ]);
    }

    /**
     * 根据主键寻找房间模型
     * 如果未找到模型，抛出404异常
     *
     * @param integer $id
     * @return Room
     * @throws NotFoundHttpException
     */
    protected function findModel($id)
    {
        if (($model = Room::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('你所请求的页面不存在。');
    }
}
