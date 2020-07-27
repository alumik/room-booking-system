<?php

namespace backend\models;

use yii\base\InvalidConfigException;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 *
 * @property string $name
 * @property int $type
 * @property string $description
 * @property string $rule_name
 * @property resource $data
 * @property int $created_at
 * @property int $updated_at
 *
 * @property AuthAssignment[] $authAssignments
 * @property AuthItemChild[] $authItemChildren
 * @property AuthItemChild[] $authItemChildren0
 * @property AuthItem[] $children
 * @property-read string $priManageRoom
 * @property-read string $priManageStudent
 * @property-read string $priViewAdminList
 * @property-read string $priManagePermission
 * @property-read string $priViewStudentList
 * @property-read array $privileges
 * @property-read string $priManageAdmin
 * @property AuthItem[] $parents
 */
class AuthItem extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'auth_item';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'type'], 'required'],
            [['type', 'created_at', 'updated_at'], 'integer'],
            [['description', 'data'], 'string'],
            [['name', 'rule_name'], 'string', 'max' => 64],
            [['name'], 'unique'],
            [['rule_name'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'name' => 'Name',
            'type' => 'Type',
            'description' => 'Description',
            'rule_name' => 'Rule Name',
            'data' => 'Data',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function getAuthAssignments()
    {
        return $this->hasMany(AuthAssignment::class, ['item_name' => 'name']);
    }

    /**
     * @return ActiveQuery
     */
    public function getAuthItemChildren()
    {
        return $this->hasMany(AuthItemChild::class, ['parent' => 'name']);
    }

    /**
     * @return ActiveQuery
     */
    public function getAuthItemChildren0()
    {
        return $this->hasMany(AuthItemChild::class, ['child' => 'name']);
    }

    /**
     * @return ActiveQuery
     * @throws InvalidConfigException
     */
    public function getChildren()
    {
        return $this->hasMany(AuthItem::class, ['name' => 'child'])->viaTable('auth_item_child', ['parent' => 'name']);
    }

    /**
     * @return ActiveQuery
     * @throws InvalidConfigException
     */
    public function getParents()
    {
        return $this->hasMany(AuthItem::class, ['name' => 'parent'])->viaTable('auth_item_child', ['child' => 'name']);
    }

    /**
     * 获取角色对应的权限
     *
     * @return array
     */
    public function getPrivileges()
    {
        return AuthItemChild::find()
            ->select('child')
            ->where(['auth_item_child.parent' => $this->name])
            ->column();
    }

    /**
     * 获取权限标识符
     *
     * @param $privilege
     * @return string
     */
    private function getPrivilegeIndicator($privilege)
    {
        return in_array($privilege, $this->getPrivileges()) ? '●' : '-';
    }

    /**
     * 获取 [[priManageRoom]]
     *
     * @return string
     */
    public function getPriManageRoom()
    {
        return $this->getPrivilegeIndicator('manageRoom');
    }

    /**
     * 获取 [[priManageAdmin]]
     *
     * @return string
     */
    public function getPriManageAdmin()
    {
        return $this->getPrivilegeIndicator('manageAdmin');
    }

    /**
     * 获取 [[priManageStudent]]
     *
     * @return string
     */
    public function getPriManageStudent()
    {
        return $this->getPrivilegeIndicator('manageStudent');
    }

    /**
     * 获取 [[priViewAdminList]]
     *
     * @return string
     */
    public function getPriViewAdminList()
    {
        return $this->getPrivilegeIndicator('viewAdminList');
    }

    /**
     * 获取 [[priViewStudentList]]
     *
     * @return string
     */
    public function getPriViewStudentList()
    {
        return $this->getPrivilegeIndicator('viewStudentList');
    }

    /**
     * 获取 [[priManagePermission]]
     *
     * @return string
     */
    public function getPriManagePermission()
    {
        return $this->getPrivilegeIndicator('managePermission');
    }
}
