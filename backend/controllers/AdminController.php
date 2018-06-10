<?php

namespace backend\controllers;

use Yii;
use app\models\AuthAssignment;
use backend\models\ResetPasswordForm;
use backend\models\SignupForm;
use common\models\Admin;
use common\models\AdminSearch;
use yii\db\StaleObjectException;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\ForbiddenHttpException;

/**
 * AdminController Admin模型类的控制器
 */
class AdminController extends Controller
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
     * 列出所有管理员
     *
     * @return mixed
     * @throws ForbiddenHttpException 如果没有权限
     */
    public function actionIndex()
    {
        if (!Yii::$app->user->can('viewAdminList')) {
            throw new ForbiddenHttpException('对不起，你没有进行该操作的权限。');
        }

        $searchModel = new AdminSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * 查看一个管理员的详细信息
     *
     * @param integer $id
     * @return mixed
     * @throws ForbiddenHttpException 如果没有权限
     * @throws NotFoundHttpException 如果模型找不到
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
     * @return mixed
     * @throws ForbiddenHttpException 如果没有权限
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
     * @return mixed
     * @throws ForbiddenHttpException 如果没有权限
     * @throws \Exception
     */
    public function actionResetpwd($id)
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

        return $this->render('resetpwd', [
            'model' => $model,
        ]);
    }

    /**
     * 修改管理员信息
     * 如果操作成功则跳转至详情页
     *
     * @param integer $id
     * @return mixed
     * @throws ForbiddenHttpException 如果没有权限
     * @throws NotFoundHttpException 如果模型找不到
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
     * @return mixed
     * @throws ForbiddenHttpException 如果没有权限
     * @throws NotFoundHttpException 如果模型找不到
     * @throws StaleObjectException 如果[[optimisticLock|optimistic locking]]已启用且待删除数据已过期
     * @throws \Exception|\Throwable 如果删除失败
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
     * @return mixed
     * @throws ForbiddenHttpException 如果没有权限
     * @throws NotFoundHttpException 如果模型找不到
     */
    public function actionPrivilege($id)
    {
        if (!Yii::$app->user->can('managePermission')) {
            throw new ForbiddenHttpException('对不起，你没有进行该操作的权限。');
        }

        $model = $this->findModel($id);
        $allRoles = Admin::getAllRoles();
        $roles = $model->roles;

        if (isset($_POST['newRoles'])) {
            AuthAssignment::deleteAll('user_id=:id', [':id' => $id]);
            $model->resetRole();

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
     * 根据主键寻找管理员模型
     * 如果未找到模型，抛出404异常
     *
     * @param integer $id
     * @return Admin
     * @throws NotFoundHttpException 如果模型找不到
     */
    protected function findModel($id)
    {
        if (($model = Admin::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
