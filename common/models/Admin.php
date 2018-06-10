<?php

namespace common\models;

use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

/**
 * 管理员模型
 *
 * @property integer $id
 * @property string $admin_id
 * @property string $admin_name
 * @property string $password_hash
 * @property string $email
 * @property string $auth_key
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $password write-only password
 */
class Admin extends ActiveRecord implements IdentityInterface
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%admin}}';
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
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            ['email', 'unique', 'targetClass' => '\common\models\Admin', 'message' => '该邮箱已注册！'],

            ['admin_id', 'required'],
            ['admin_id', 'string', 'length' => 7],
            ['admin_id', 'unique', 'targetClass' => '\common\models\Admin', 'message' => '该账号已注册！'],

            ['admin_name', 'trim'],
            ['admin_name', 'required'],
            ['admin_name', 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'admin_id' => '管理员账号',
            'admin_name' => '姓名',
            'email' => 'Email',
            'created_at' => '注册时间',
            'updated_at' => '修改时间',
        ];
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id]);
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
     * 根据admin_id寻找管理员
     *
     * @param string $admin_id
     * @return static|null
     */
    public static function findByAdminId($admin_id)
    {
        return static::findOne(['admin_id' => $admin_id]);
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
}
