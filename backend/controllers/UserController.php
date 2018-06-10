<?php

namespace backend\controllers;

use Yii;
use common\models\User;
use common\models\UserSearch;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * UserController User模型类的控制器
 */
class UserController extends Controller
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
     * 列出所有学生
     *
     * @return mixed
     * @throws ForbiddenHttpException 如果没有权限
     */
    public function actionIndex()
    {
        if (!Yii::$app->user->can('viewStudentList')) {
            throw new ForbiddenHttpException('对不起，你没有进行该操作的权限。');
        }

        $searchModel = new UserSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * 修改学生状态（正常/已删除）
     * 如果操作成功则跳转至学生列表
     *
     * @param integer $id
     * @return mixed
     * @throws ForbiddenHttpException 如果没有权限
     * @throws NotFoundHttpException 如果模型找不到
     */
    public function actionChangestatus($id)
    {
        if (!Yii::$app->user->can('manageStudent')) {
            throw new ForbiddenHttpException('对不起，你没有进行该操作的权限。');
        }

        $model = $this->findModel($id);
        $model->changeStatus();
        $model->save();

        return $this->redirect(['index']);
    }

    /**
     * 根据主键寻找学生模型
     * 如果未找到模型，抛出404异常
     *
     * @param integer $id
     * @return User
     * @throws NotFoundHttpException 如果模型找不到
     */
    protected function findModel($id)
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('对不起，未找到你查询的数据');
    }
}
