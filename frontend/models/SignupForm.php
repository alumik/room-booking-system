<?php

namespace frontend\models;

use yii\base\Model;
use common\models\User;

/**
 * @author 钟震宇 <nczzy1997@gmail.com>
 *
 * 前台 学生注册 表单模型
 */
class SignupForm extends Model
{
    public $student_id;
    public $username;
    public $email;
    public $password;
    public $password2;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['student_id', 'trim'],
            ['student_id', 'required'],
            ['student_id', 'unique', 'targetClass' => '\common\models\User', 'message' => '该学号已注册。'],
            ['student_id', 'string', 'length' => 7],

            ['username', 'trim'],
            ['username', 'required'],
            ['username', 'string', 'max' => 255],

            ['email', 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            ['email', 'unique', 'targetClass' => '\common\models\User', 'message' => '该邮箱已注册。'],

            [['password', 'password2'], 'required'],
            ['password', 'string', 'min' => 6],

            ['password2', 'compare', 'compareAttribute' => 'password', 'message' => '两次输入密码不一致。'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'student_id' => '学号',
            'username' => '姓名',
            'email' => 'Email',
            'password' => '密码',
            'password2' => '重新输入密码',
        ];
    }

    /**
     * 注册学生
     *
     * @return User|null
     * @throws \Exception
     */
    public function signup()
    {
        if (!$this->validate()) {
            return null;
        }
        
        $user = new User();
        $user->student_id = $this->student_id;
        $user->username = $this->username;
        $user->email = $this->email;
        $user->setPassword($this->password);
        $user->generateAuthKey();
        
        return $user->save() ? $user : null;
    }
}
