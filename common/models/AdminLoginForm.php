<?php

namespace common\models;

use Yii;
use yii\base\Model;

/**
 *
 * @property-read null|Admin $admin
 */
class AdminLoginForm extends Model
{
    public $admin_id;
    public $password;
    public $rememberMe = true;

    private $_admin;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['admin_id', 'password'], 'required'],
            ['admin_id', 'string', 'length' => 7],

            ['rememberMe', 'boolean'],

            ['password', 'validatePassword'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'admin_id' => '工号',
            'password' => '密码',
            'rememberMe' => '保持登录状态',
        ];
    }

    /**
     * 验证密码是否正确
     *
     * @param string $attribute
     */
    public function validatePassword($attribute)
    {
        if (!$this->hasErrors()) {
            $admin = $this->getAdmin();
            if (!$admin || !$admin->validatePassword($this->password)) {
                $this->addError($attribute, '工号或密码不正确。');
            }
        }
    }

    /**
     * 根据给定的工号和密码登录管理员
     *
     * @return bool
     */
    public function login()
    {
        if ($this->validate()) {
            return Yii::$app->user->login($this->getAdmin(), $this->rememberMe ? 3600 * 24 * 30 : 0);
        }

        return false;
    }

    /**
     * 根据 [[admin_id]] 寻找管理员模型
     *
     * @return Admin|null
     */
    protected function getAdmin()
    {
        if ($this->_admin === null) {
            $this->_admin = Admin::findByAdminId($this->admin_id);
        }

        return $this->_admin;
    }
}
