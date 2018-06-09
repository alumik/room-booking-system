<?php
namespace common\models;

use Yii;
use yii\base\Model;

/**
 * Login form
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
            // admin_id and password are both required
            [['admin_id', 'password'], 'required'],
            ['admin_id', 'string', 'length' => 7],
            // rememberMe must be a boolean value
            ['rememberMe', 'boolean'],
            // password is validated by validatePassword()
            ['password', 'validatePassword'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'admin_id' => '账号',
            'password' => '密码',
            'rememberMe' => '保持登录状态',
        ];
    }

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $admin = $this->getAdmin();
            if (!$admin || !$admin->validatePassword($this->password)) {
                $this->addError($attribute, '账号或密码不正确！');
            }
        }
    }

    /**
     * Logs in a admin using the provided admin_id and password.
     *
     * @return bool whether the admin is logged in successfully
     */
    public function login()
    {
        if ($this->validate()) {
            return Yii::$app->user->login($this->getAdmin(), $this->rememberMe ? 3600 * 24 * 30 : 0);
        }
        
        return false;
    }

    /**
     * Finds admin by [[admin_id]]
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
