<?php

namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\models\User;
use common\models\UserSearch;
use yii\web\Response;

class UserController extends Controller
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
        ];
    }

    /**
     * 列出所有学生
     *
     * @return string
     * @throws ForbiddenHttpException
     */
    public function actionIndex()
    {
        if (!Yii::$app->user->can('viewStudentList')) {
            throw new ForbiddenHttpException('对不起，你没有进行该操作的权限。');
        }

        $searchModel = new UserSearch();

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $searchModel->search(Yii::$app->request->queryParams),
        ]);
    }

    /**
     * 修改学生状态（正常/已禁用）
     * 如果操作成功则跳转至学生列表
     *
     * @param integer $id
     * @return Response
     * @throws ForbiddenHttpException
     * @throws NotFoundHttpException
     */
    public function actionChangeStatus($id)
    {
        if (!Yii::$app->user->can('manageStudent')) {
            throw new ForbiddenHttpException('对不起，你没有进行该操作的权限。');
        }

        $model = $this->findModel($id);
        $model->changeStatus();
        $model->save();

        return $this->redirect(Yii::$app->request->referrer);
    }

    /**
     * 根据主键寻找学生模型
     * 如果未找到模型，抛出404异常
     *
     * @param integer $id
     * @return User
     * @throws NotFoundHttpException
     */
    protected function findModel($id)
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('你所请求的页面不存在。');
    }
}
