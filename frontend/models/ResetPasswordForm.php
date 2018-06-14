<?php

namespace frontend\models;

use yii\base\Model;
use yii\base\InvalidArgumentException;
use common\models\User;

/**
 * @author 钟震宇 <nczzy1997@gmail.com>
 *
 * 前台 学生登录前修改密码 表单模型
 */
class ResetPasswordForm extends Model
{
    public $password;
    public $password2;

    /**
     * @var \common\models\User
     */
    private $_user;

    /**
     * 根据给定的密码重置密钥创建表单
     *
     * @param string $token
     * @param array $config
     * @throws \yii\base\InvalidArgumentException 如果密码重置密钥无效
     */
    public function __construct($token, $config = [])
    {
        if (empty($token) || !is_string($token)) {
            throw new InvalidArgumentException('密码重置密钥不能为空！');
        }
        $this->_user = User::findByPasswordResetToken($token);
        if (!$this->_user) {
            throw new InvalidArgumentException('密码重置密钥无效！');
        }
        parent::__construct($config);
    }

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
     * @return bool
     * @throws \Exception
     */
    public function resetPassword()
    {
        $user = $this->_user;
        $user->setPassword($this->password);
        $user->removePasswordResetToken();

        return $user->save(false);
    }
}
