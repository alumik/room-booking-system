<?php

namespace frontend\controllers;

use Yii;
use common\models\User;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\AccessControl;
use frontend\models\LoginResetPasswordForm;

/**
 * UserController User模型类的控制器
 */
class UserController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['view', 'update', 'resetpwd'],
                'rules' => [
                    [
                        'actions' => ['view', 'update', 'resetpwd'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    /**
     * 显示账号信息
     *
     * @return mixed
     * @throws NotFoundHttpException 如果模型找不到
     */
    public function actionView()
    {
        return $this->render('view', [
            'model' => $this->findModel(Yii::$app->user->identity->getId()),
        ]);
    }

    /**
     * 修改账号信息
     * 如果操作成功则跳转至详情页
     *
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException 如果模型找不到
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * 登录后修改密码
     *
     * @param integer $id
     * @return string|\yii\web\Response
     * @throws \Exception
     */
    public function actionResetpwd($id)
    {
        $model = new LoginResetPasswordForm();

        if ($model->load(Yii::$app->request->post())) {
            if ($admin = $model->resetPassword($id)) {
                return $this->redirect(['view']);
            }
        }

        return $this->render('resetpwd', [
            'model' => $model,
        ]);
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
