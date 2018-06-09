<?php
namespace frontend\models;

use yii\base\Model;
use common\models\User;

/**
 * Signup form
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
            ['student_id', 'unique', 'targetClass' => '\common\models\User', 'message' => '该学号已注册！'],
            ['student_id', 'string', 'length' => 7],

            ['username', 'trim'],
            ['username', 'required'],
            ['username', 'string', 'max' => 255],

            ['email', 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            ['email', 'unique', 'targetClass' => '\common\models\User', 'message' => '该邮箱已注册！'],

            ['password', 'required'],
            ['password', 'string', 'min' => 6],

            ['password2', 'compare', 'compareAttribute' => 'password'],
        ];
    }

    /**
     * @inheritdoc
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
     * Signs user up.
     *
     * @return User|null the saved model or null if saving fails
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
