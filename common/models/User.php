<?php

namespace common\models;

use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

/**
 * 公共 学生 模型
 *
 * @property integer $id
 * @property string $student_id
 * @property string $username
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $email
 * @property string $auth_key
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $password write-only password
 */
class User extends ActiveRecord implements IdentityInterface
{
    const STATUS_DELETED = 0;
    const STATUS_ACTIVE = 10;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%user}}';
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['status', 'default', 'value' => self::STATUS_ACTIVE],
            ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_DELETED]],

            ['email', 'required'],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            ['email', 'unique', 'targetClass' => '\common\models\User', 'message' => '该邮箱已注册！'],

            ['student_id', 'required'],
            ['student_id', 'string', 'length' => 7],
            ['student_id', 'unique', 'targetClass' => '\common\models\User', 'message' => '该账号已注册！'],

            ['username', 'trim'],
            ['username', 'required'],
            ['username', 'string', 'max' => 255],
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
            'status' => '状态',
            'created_at' => '注册时间',
            'updated_at' => '修改时间',
        ];
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * {@inheritdoc}
     * @throws NotSupportedException
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('对不起，该功能暂未实现。');
    }

    /**
     * 根据student_id寻找学生
     *
     * @param string $student_id
     * @return static|null
     */
    public static function findByStudentId($student_id)
    {
        return static::findOne(['student_id' => $student_id, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * 根据password_reset_token寻找学生
     *
     * @param string $token
     * @return static|null
     */
    public static function findByPasswordResetToken($token)
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::findOne([
            'password_reset_token' => $token,
            'status' => self::STATUS_ACTIVE,
        ]);
    }

    /**
     * 检查密码重置密钥是否有效
     *
     * @param string $token
     * @return bool
     */
    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }

        $timestamp = (int) substr($token, strrpos($token, '_') + 1);
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        return $timestamp + $expire >= time();
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * {@inheritdoc}
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * 检查密码是否有效
     *
     * @param string $password
     * @return bool
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * 为密码生成散列值（password_hash）并保存
     *
     * @param string $password
     * @throws \Exception
     */
    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * 生成“保持登录状态”用的鉴权码（auth_key）
     *
     * @throws \Exception
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * 生成新的密码重置密钥
     *
     * @throws \Exception
     */
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * 清除密码重置密钥
     */
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }

    /**
     * 获取所有状态列表
     *
     * @return array
     */
    public static function allStatus()
    {
        return [self::STATUS_ACTIVE => '正常', self::STATUS_DELETED => '已禁用'];
    }

    /**
     * 获取当前学生状态字符串
     *
     * @return string
     */
    public function getStatusStr() {
        return $this->status == self::STATUS_ACTIVE ? '正常' : '已禁用';
    }

    /**
     * 保存前自动生成修改时间
     *
     * @param bool $insert
     * @return bool
     */
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            $this->updated_at = time();
            return true;
        }
        return false;
    }

    /**
     * 切换学生状态
     *
     * @return bool
     */
    public function changeStatus()
    {
        if ($this->status == self::STATUS_ACTIVE) {
            $this->status = self::STATUS_DELETED;
        } else {
            $this->status = self::STATUS_ACTIVE;
        }

        return true;
    }
}
