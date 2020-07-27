<?php

namespace common\models;

use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\Query;
use yii\web\IdentityInterface;
use backend\models\AuthAssignment;
use backend\models\AuthItem;

/**
 *
 * @property integer $id
 * @property string $admin_id
 * @property string $admin_name
 * @property string $password_hash
 * @property string $email
 * @property string $auth_key
 * @property integer $created_at
 * @property integer $updated_at
 * @property boolean $super_admin
 * @property boolean $web_admin
 * @property boolean $student_admin
 * @property boolean $room_admin
 * @property-read string $studentAdminIndicator
 * @property-read mixed $rolesDescription
 * @property-read array $roles
 * @property-read string $webAdminIndicator
 * @property-read string $superAdminIndicator
 * @property-read string $roomAdminIndicator
 * @property-read string $authKey
 * @property-write string $password write-only password
 */
class Admin extends ActiveRecord implements IdentityInterface
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'admin';
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::class,
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
            ['email', 'unique', 'targetClass' => '\common\models\Admin', 'message' => '该电子邮箱已注册。'],

            ['admin_id', 'required'],
            ['admin_id', 'string', 'length' => 7],
            ['admin_id', 'unique', 'targetClass' => '\common\models\Admin', 'message' => '该工号已注册。'],

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
            'admin_id' => '工号',
            'admin_name' => '姓名',
            'email' => '电子邮箱',
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
     * 根据 [[admin_id]] 寻找管理员
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
     * 为密码生成散列值并保存
     *
     * @param string $password
     * @throws \Exception
     */
    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * 生成保持登录状态用的鉴权码
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

    /**
     * 获取所有角色列表
     *
     * @return array
     */
    public static function getAllRoles()
    {
        return AuthItem::find()
            ->select(['description', 'name'])
            ->where(['type' => 1])
            ->orderBy('description')
            ->indexBy('name')
            ->column();
    }

    /**
     * 获取已选中角色列表
     *
     * @return array
     */
    public function getRoles()
    {
        return AuthAssignment::find()
            ->select('item_name')
            ->where(['user_id' => $this->id])
            ->column();
    }

    /**
     * 获取管理员角色名
     *
     * @string array
     */
    public function getRolesDescription()
    {
        $roles = (new Query())
            ->select('description')
            ->from('auth_assignment')
            ->join('inner join', 'auth_item', 'auth_item.name = auth_assignment.item_name')
            ->where(['user_id' => $this->id])
            ->column();

        return implode('，', $roles);
    }

    /**
     * 清空管理员角色
     */
    public function clearRoles()
    {
        $this->super_admin = false;
        $this->web_admin = false;
        $this->student_admin = false;
        $this->room_admin = false;
        $this->save();
    }

    /**
     * 获取角色指示符
     *
     * @param $roleName
     * @return string
     */
    private function getRoleIndicator($roleName)
    {
        return $this->attributes[$roleName] ? '●' : '-';
    }

    /**
     * 获取 [[superAdminIndicator]]
     *
     * @return string
     */
    public function getSuperAdminIndicator()
    {
        return $this->getRoleIndicator('super_admin');
    }

    /**
     * 获取 [[webAdminIndicator]]
     *
     * @return string
     */
    public function getWebAdminIndicator()
    {
        return $this->getRoleIndicator('web_admin');
    }

    /**
     * 获取 [[studentAdminIndicator]]
     *
     * @return string
     */
    public function getStudentAdminIndicator()
    {
        return $this->getRoleIndicator('student_admin');
    }

    /**
     * 获取 [[roomAdminIndicator]]
     *
     * @return string
     */
    public function getRoomAdminIndicator()
    {
        return $this->getRoleIndicator('room_admin');
    }
}
