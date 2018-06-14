<?php

namespace frontend\models;

use yii\base\Model;
use common\models\User;

/**
 * @author 钟震宇 <nczzy1997@gmail.com>
 *
 * 前台 学生登录后修改密码 表单模型
 */
class LoginResetPasswordForm extends Model
{
    public $password;
    public $password2;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['password', 'required'],
            ['password', 'string', 'min' => 6],

            ['password2', 'compare', 'compareAttribute' => 'password', 'message' => '两次输入密码不一致！'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'password' => '密码',
            'password2' => '重新输入密码',
        ];
    }

    /**
     * 修改密码
     *
     * @param integer $id
     * @return bool
     * @throws \Exception
     */
    public function resetPassword($id)
    {
        if (!$this->validate()) {
            return null;
        }
        
        $user = User::findOne($id);
        $user->setPassword($this->password);
        
        return $user->save() ? true : false;
    }
}
