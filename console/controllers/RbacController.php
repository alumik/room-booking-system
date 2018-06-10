<?php
/**
 * Created by PhpStorm.
 * User: Zhong
 * Date: 2018/6/10
 * Time: 14:27
 */

namespace console\controllers;

use Yii;
use yii\console\Controller;

class RbacController extends Controller
{
    public function actionInit()
    {
        $auth = Yii::$app->authManager;

        $manageAdmin = $auth->createPermission('manageAdmin');
        $manageAdmin->description = '修改管理员';
        $auth->add($manageAdmin);

        $viewAdminList = $auth->createPermission('viewAdminList');
        $viewAdminList->description = '浏览管理员列表';
        $auth->add($viewAdminList);

        $managePermission = $auth->createPermission('managePermission');
        $managePermission->description = '分配权限';
        $auth->add($managePermission);

        $manageStudent = $auth->createPermission('manageStudent');
        $manageStudent->description = '修改学生';
        $auth->add($manageStudent);

        $viewStudentList = $auth->createPermission('viewStudentList');
        $viewStudentList->description = '浏览学生列表';
        $auth->add($viewStudentList);

        $manageRoom = $auth->createPermission('manageRoom');
        $manageRoom->description = '管理图书馆';
        $auth->add($manageRoom);

        $superAdmin = $auth->createRole('superAdmin');
        $auth->add($superAdmin);
        $auth->addChild($superAdmin, $manageAdmin);
        $auth->addChild($superAdmin, $viewAdminList);
        $auth->addChild($superAdmin, $managePermission);
        $auth->addChild($superAdmin, $manageStudent);
        $auth->addChild($superAdmin, $viewStudentList);
        $auth->addChild($superAdmin, $manageRoom);

        $webAdmin = $auth->createRole('webAdmin');
        $auth->add($webAdmin);
        $auth->addChild($webAdmin, $manageAdmin);
        $auth->addChild($webAdmin, $viewAdminList);

        $studentAdmin = $auth->createRole('studentAdmin');
        $auth->add($studentAdmin);
        $auth->addChild($studentAdmin, $manageStudent);
        $auth->addChild($studentAdmin, $viewStudentList);

        $libAdmin = $auth->createRole('libAdmin');
        $auth->add($libAdmin);
        $auth->addChild($libAdmin, $manageRoom);
        $auth->addChild($libAdmin, $viewStudentList);

        $auth->assign($superAdmin, 5);
        $auth->assign($webAdmin, 2);
        $auth->assign($studentAdmin, 3);
        $auth->assign($libAdmin, 4);
    }
}