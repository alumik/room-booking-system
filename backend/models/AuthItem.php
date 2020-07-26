<?php

namespace app\models;

use yii\base\InvalidConfigException;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * @author 钟震宇 <nczzy1997@gmail.com>
 *
 * 后台 权限项目 模型
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
        $privileges = AuthItemChild::find()->select(['child'])
            ->where(['auth_item_child.parent' => $this->name])
            ->all();

        $privilegesArray = array();
        foreach ($privileges as $privilege) {
            /* @var AuthItemChild $privilege */
            array_push($privilegesArray, $privilege->child);
        }

        return $privilegesArray;
    }

    /**
     * 获取 priManageRoom
     *
     * @return string
     */
    public function getPriManageRoom()
    {
        return in_array('manageRoom', $this->getPrivileges()) ? '●' : '-';
    }

    /**
     * 获取 priManageAdmin
     *
     * @return string
     */
    public function getPriManageAdmin()
    {
        return in_array('manageAdmin', $this->getPrivileges()) ? '●' : '-';
    }

    /**
     * 获取 priManageStudent
     *
     * @return string
     */
    public function getPriManageStudent()
    {
        return in_array('manageStudent', $this->getPrivileges()) ? '●' : '-';
    }

    /**
     * 获取 priViewAdminList
     *
     * @return string
     */
    public function getPriViewAdminList()
    {
        return in_array('viewAdminList', $this->getPrivileges()) ? '●' : '-';
    }

    /**
     * 获取 priViewStudentList
     *
     * @return string
     */
    public function getPriViewStudentList()
    {
        return in_array('viewStudentList', $this->getPrivileges()) ? '●' : '-';
    }

    /**
     * 获取 priManagePermission
     *
     * @return string
     */
    public function getPriManagePermission()
    {
        return in_array('managePermission', $this->getPrivileges()) ? '●' : '-';
    }
}
