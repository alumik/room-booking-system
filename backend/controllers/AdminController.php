<?php

namespace backend\controllers;

use app\models\AuthAssignment;
use app\models\AuthItem;
use backend\models\ResetPasswordForm;
use backend\models\SignupForm;
use Yii;
use common\models\Admin;
use common\models\AdminSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\ForbiddenHttpException;

/**
 * AdminController implements the CRUD actions for Admin model.
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
     * Lists all Admin models.
     *
     * @throws ForbiddenHttpException
     * @return mixed
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
     * Displays a single Admin model.
     *
     * @param integer $id
     * @throws ForbiddenHttpException
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
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
     * Creates a new Admin model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     *
     * @throws ForbiddenHttpException
     * @return mixed
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
     * Updates an existing Admin model.
     * If update is successful, the browser will be redirected to the 'view' page.
     *
     * @throws ForbiddenHttpException
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
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
     * Deletes an existing Admin model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     *
     * @throws ForbiddenHttpException
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
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
     * Finds the Admin model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Admin the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Admin::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionPrivilege($id)
    {
        if (!Yii::$app->user->can('managePermission')) {
            throw new ForbiddenHttpException('对不起，你没有进行该操作的权限。');
        }

        $model = $this->findModel($id);

        $allPrivileges = AuthItem::find()->select(['name', 'description'])
            ->where(['type' => 1])
            ->orderBy('description')
            ->all();
        $allPrivilegesArray = array();

        foreach ($allPrivileges as $privilege) {
            $allPrivilegesArray[$privilege->name] = $privilege->description;
        }

        $authAssignments = AuthAssignment::find()->select(['item_name'])
            ->where(['user_id' => $id])
            ->all();
        $authAssignmentsArray = array();

        foreach ($authAssignments as $authAssignment) {
            array_push($authAssignmentsArray, $authAssignment->item_name);
        }

        if (isset($_POST['newPri'])) {
            AuthAssignment::deleteAll('user_id=:id', [':id' => $id]);

            $newPri = $_POST['newPri'];
            foreach ($newPri as $pri) {
                $aPri = new AuthAssignment();
                $aPri->item_name = $pri;
                $aPri->user_id = $id;
                $aPri->created_at = time();
                $aPri->save();
            }

            return $this->redirect(['index']);
        }

        return $this->render('privilege', ['model' => $model, 'authAssignmentsArray' => $authAssignmentsArray,
            'allPrivilegesArray' => $allPrivilegesArray]);
    }
}
