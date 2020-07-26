<?php

namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\AccessControl;
use common\models\User;
use frontend\models\LoginResetPasswordForm;
use yii\web\Response;

/**
 * @author 钟震宇 <nczzy1997@gmail.com>
 *
 * 前台 学生 控制器
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
                'class' => AccessControl::class,
                'only' => ['view', 'update', 'reset-password'],
                'rules' => [
                    [
                        'actions' => ['view', 'update', 'reset-password'],
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
     * @throws NotFoundHttpException
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
     * @return mixed
     * @throws NotFoundHttpException
     */
    public function actionUpdate()
    {
        $model = $this->findModel(Yii::$app->user->identity->getId());

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view']);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * 登录后修改密码
     *
     * @return string|Response
     * @throws \Exception
     */
    public function actionResetPassword()
    {
        $model = new LoginResetPasswordForm();

        if ($model->load(Yii::$app->request->post())) {
            if ($model->resetPassword(Yii::$app->user->identity->getId())) {
                return $this->redirect(['view']);
            }
        }

        return $this->render('reset_password', [
            'model' => $model,
        ]);
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
