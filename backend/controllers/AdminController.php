<?php

namespace backend\controllers;

use Yii;
use yii\db\StaleObjectException;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\ForbiddenHttpException;
use backend\models\AuthAssignment;
use common\models\Admin;
use common\models\AdminSearch;
use backend\models\ResetPasswordForm;
use backend\models\SignupForm;
use backend\models\AuthItemSearch;
use yii\web\Response;

class AdminController extends Controller
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
     * 列出所有管理员
     *
     * @return string
     * @throws ForbiddenHttpException
     */
    public function actionIndex()
    {
        if (!Yii::$app->user->can('viewAdminList')) {
            throw new ForbiddenHttpException('对不起，你没有进行该操作的权限。');
        }

        $searchModel = new AdminSearch();

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $searchModel->search(Yii::$app->request->queryParams),
        ]);
    }

    /**
     * 查看一个管理员的详细信息
     *
     * @param integer $id
     * @return string
     * @throws ForbiddenHttpException
     * @throws NotFoundHttpException
     */
    public function actionView($id)
    {
        if (!Yii::$app->user->can('manageAdmin')) {
            throw new ForbiddenHttpException('对不起，你没有进行该操作的权限。');
        }

        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * 创建一个新管理员
     * 如果操作成功则跳转至详情页
     *
     * @return string|Response
     * @throws ForbiddenHttpException
     * @throws \Exception
     */
    public function actionCreate()
    {
        if (!Yii::$app->user->can('manageAdmin')) {
            throw new ForbiddenHttpException('对不起，你没有进行该操作的权限。');
        }

        $model = new SignupForm();

        if ($model->load(Yii::$app->request->post())) {
            if ($admin = $model->signup()) {
                return $this->redirect(['view', 'id' => $admin->id]);
            }
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * 重置管理员密码
     * 如果操作成功则跳转至管理员列表
     *
     * @param integer $id
     * @return string|Response
     * @throws ForbiddenHttpException
     * @throws \Exception
     */
    public function actionResetPassword($id)
    {
        if (!Yii::$app->user->can('manageAdmin')) {
            throw new ForbiddenHttpException('对不起，你没有进行该操作的权限。');
        }

        $model = new ResetPasswordForm();

        if ($model->load(Yii::$app->request->post())) {
            if ($admin = $model->resetPassword($id)) {
                return $this->redirect(['index']);
            }
        }

        return $this->render('reset_password', [
            'model' => $model,
        ]);
    }

    /**
     * 修改管理员信息
     * 如果操作成功则跳转至详情页
     *
     * @param integer $id
     * @return string|Response
     * @throws ForbiddenHttpException
     * @throws NotFoundHttpException
     */
    public function actionUpdate($id)
    {
        if (!Yii::$app->user->can('manageAdmin')) {
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
     * 删除管理员
     * 如果操作成功则跳转至管理员列表
     *
     * @param integer $id
     * @return Response
     * @throws ForbiddenHttpException
     * @throws NotFoundHttpException
     * @throws StaleObjectException
     * @throws \Exception|\Throwable
     */
    public function actionDelete($id)
    {
        if (!Yii::$app->user->can('manageAdmin')) {
            throw new ForbiddenHttpException('对不起，你没有进行该操作的权限。');
        }

        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * 修改管理员权限
     * 如果操作成功则跳转至管理员列表
     *
     * @param integer $id
     * @return string|Response
     * @throws ForbiddenHttpException
     * @throws NotFoundHttpException
     */
    public function actionPrivilege($id)
    {
        if (!Yii::$app->user->can('managePermission')) {
            throw new ForbiddenHttpException('对不起，你没有进行该操作的权限。');
        }

        $model = $this->findModel($id);
        $allRoles = Admin::getAllRoles();
        $roles = $model->getRoles();

        if (isset($_POST['newRoles'])) {
            $model->clearRoles();
            AuthAssignment::deleteAll('user_id=:id', [':id' => $id]);

            $newRoles = $_POST['newRoles'];
            foreach ($newRoles as $role) {
                $aRole = new AuthAssignment();
                $aRole->item_name = $role;
                $aRole->user_id = $id;
                $aRole->created_at = time();
                $aRole->save();

                switch ($role) {
                    case 'superAdmin':
                        $model->super_admin = true;
                        break;
                    case 'webAdmin':
                        $model->web_admin = true;
                        break;
                    case 'studentAdmin':
                        $model->student_admin = true;
                        break;
                    case 'roomAdmin':
                        $model->room_admin = true;
                }
            }

            $model->save();

            return $this->redirect(['index']);
        }

        return $this->render('privilege', [
            'model' => $model,
            'roles' => $roles,
            'allRoles' => $allRoles,
        ]);
    }

    /**
     * 权限对照表
     *
     * @return string
     * @throws ForbiddenHttpException
     */
    public function actionViewPrivilege()
    {
        if (!Yii::$app->user->can('manageAdmin')) {
            throw new ForbiddenHttpException('对不起，你没有进行该操作的权限。');
        }

        $searchModel = new AuthItemSearch();

        return $this->render('view_privilege', [
            'searchModel' => $searchModel,
            'dataProvider' => $searchModel->search(Yii::$app->request->queryParams),
        ]);
    }

    /**
     * 根据主键寻找管理员模型
     * 如果未找到模型，抛出404异常
     *
     * @param integer $id
     * @return Admin
     * @throws NotFoundHttpException
     */
    protected function findModel($id)
    {
        if (($model = Admin::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('你所请求的页面不存在。');
    }
}
