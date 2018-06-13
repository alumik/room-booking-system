<?php

namespace common\models;

use app\models\AuthAssignment;
use app\models\AuthItem;
use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\Query;
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
 * @property boolean $super_admin
 * @property boolean $web_admin
 * @property boolean $student_admin
 * @property boolean $room_admin
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

    /**
     * 获取所有角色列表
     *
     * @return array
     */
    public static function getAllRoles() {
        $allRoles = AuthItem::find()->select(['name', 'description'])
            ->where(['type' => 1])
            ->orderBy('description')
            ->all();

        $allRolesArray = array();
        foreach ($allRoles as $role) {
            $allRolesArray[$role->name] = $role->description;
        }

        return $allRolesArray;
    }

    /**
     * 获取已选中角色列表
     *
     * @return array
     */
    public function getRoles() {
        $roles = AuthAssignment::find()->select(['item_name'])
            ->where(['user_id' => $this->id])
            ->all();

        $rolesArray = array();
        foreach ($roles as $role) {
            array_push($rolesArray, $role->item_name);
        }

        return $rolesArray;
    }

    /**
     * 获取管理员角色名
     *
     * @return array
     */
    public function getRolesDescription() {
        $roles = (new Query())->select(['description'])
            ->from('auth_assignment')
            ->join('INNER JOIN', 'auth_item', 'auth_item.name = auth_assignment.item_name')
            ->where(['user_id' => $this->id])
            ->all();

        $rolesArray = array();
        foreach ($roles as $role) {
            array_push($rolesArray, $role['description']);
        }

        return implode('，', $rolesArray);
    }

    /**
     * 清空管理员角色
     */
    public function resetRole() {
        $this->super_admin = false;
        $this->web_admin = false;
        $this->student_admin = false;
        $this->room_admin = false;
        $this->save();
    }

    /**
     * 获取SuperAdminStr
     *
     * @return string
     */
    public function getSuperAdminStr()
    {
        return $this->super_admin ? '●' : '-';
    }

    /**
     * 获取WebAdminStr
     *
     * @return string
     */
    public function getWebAdminStr()
    {
        return $this->web_admin ? '●' : '-';
    }

    /**
     * 获取StudentAdminStr
     *
     * @return string
     */
    public function getStudentAdminStr()
    {
        return $this->student_admin ? '●' : '-';
    }

    /**
     * 获取RoomAdminStr
     *
     * @return string
     */
    public function getRoomAdminStr()
    {
        return $this->room_admin ? '●' : '-';
    }
}
