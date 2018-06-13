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

/**
 * 前台 房间 控制器
 */
class RoomController extends Controller
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
                'only' => ['index', 'approvedapplication', 'order'],
                'rules' => [
                    [
                        'actions' => ['index', 'approvedapplication', 'order'],
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
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new RoomSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * 查看与我的申请冲突的申请列表
     *
     * @param integer $id
     * @param string $s_time_str
     * @param string $e_time_str
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionApprovedapplication($id, $s_time_str, $e_time_str)
    {
        $searchModel = new ApplicationSearch();
        $searchModel->start_time_picker = $s_time_str;
        $searchModel->end_time_picker = $e_time_str;
        $searchModel->room_id = $id;
        $searchModel->status = Application::STATUS_APPROVED;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('approved_application', [
            'model' => $this->findModel($id),
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * 提交新申请
     * 如果操作成功转到详情页
     *
     * @param integer $id
     * @param integer $s_time
     * @param integer $e_time
     * @return string|\yii\web\Response
     */
    public function actionOrder($id, $s_time, $e_time)
    {
        $model = new Application();
        $model->room_id = $id;
        $model->start_time = $s_time;
        $model->end_time = $e_time;
        $model->status = Application::STATUS_PENDDING;
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
     * @return Room the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Room::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('你所请求的页面不存在。');
    }
}
