<?php

namespace backend\controllers;

use Yii;
use yii\db\StaleObjectException;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\models\Room;
use common\models\Application;
use common\models\ApplicationSearch;
use backend\models\RoomSearch;
use yii\web\Response;

/**
 * @author 钟震宇 <nczzy1997@gmail.com>
 *
 * 后台 房间 控制器
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
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * 列出所有房间
     *
     * @return mixed
     * @throws ForbiddenHttpException
     */
    public function actionIndex()
    {
        if (!Yii::$app->user->can('manageRoom')) {
            throw new ForbiddenHttpException('对不起，你没有进行该操作的权限。');
        }

        $searchModel = new RoomSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * 显示一个房间详细信息
     *
     * @param integer $id
     * @return string
     * @throws ForbiddenHttpException
     * @throws NotFoundHttpException
     */
    public function actionView($id)
    {
        if (!Yii::$app->user->can('manageRoom')) {
            throw new ForbiddenHttpException('对不起，你没有进行该操作的权限。');
        }

        $searchModel = new ApplicationSearch();
        $searchModel->start_time_picker = date('Y-m-d H:i', time());
        $searchModel->end_time_picker = date('Y-m-d H:i', time() + 3600 * 24 * 30);
        $searchModel->room_id = $id;
        $searchModel->status = Application::STATUS_APPROVED;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('view', [
            'model' => $this->findModel($id),
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * 创建一个新房间
     * 如果操作成功则跳转至详情页
     *
     * @return mixed
     * @throws ForbiddenHttpException
     */
    public function actionCreate()
    {
        if (!Yii::$app->user->can('manageRoom')) {
            throw new ForbiddenHttpException('对不起，你没有进行该操作的权限。');
        }

        $model = new Room();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * 修改房间信息
     * 如果操作成功则跳转至详情页
     *
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException
     * @throws ForbiddenHttpException
     */
    public function actionUpdate($id)
    {
        if (!Yii::$app->user->can('manageRoom')) {
            throw new ForbiddenHttpException('对不起，你没有进行该操作的权限。');
        }

        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * 删除一个房间
     * 如果操作成功则跳转至房间列表
     *
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException
     * @throws ForbiddenHttpException
     * @throws StaleObjectException
     * @throws \Exception|\Throwable
     */
    public function actionDelete($id)
    {
        if (!Yii::$app->user->can('manageRoom')) {
            throw new ForbiddenHttpException('对不起，你没有进行该操作的权限。');
        }

        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * 切换房间状态
     *
     * @param string $id
     * @return Response
     * @throws ForbiddenHttpException
     * @throws NotFoundHttpException
     */
    public function actionChangeStatus($id)
    {
        if (!Yii::$app->user->can('manageRoom')) {
            throw new ForbiddenHttpException('对不起，你没有进行该操作的权限。');
        }

        $model = $this->findModel($id);
        $model->changeStatus();
        $model->save();

        return $this->redirect(Yii::$app->request->referrer);
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
