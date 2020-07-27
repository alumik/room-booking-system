<?php

namespace common\models;

use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 *
 * @property int $id
 * @property int $applicant_id
 * @property string $organization_name
 * @property int $room_id
 * @property string $start_time
 * @property string $end_time
 * @property string $event
 * @property int $status
 * @property int $created_at
 * @property int $updated_at
 *
 * @property User $applicant
 * @property-read array $statusBg
 * @property-read array $conflictId
 * @property-read null|string $statusStr
 * @property-read array|string[] $actionBg
 * @property Room $room
 */
class Application extends ActiveRecord
{
    const STATUS_PENDING = 0;
    const STATUS_APPROVED = 1;
    const STATUS_REJECTED = 2;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'application';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['applicant_id', 'room_id', 'event', 'status'], 'required'],
            [['applicant_id', 'room_id'], 'integer'],

            [['start_time'], 'validateStartTime'],
            [['end_time'], 'validateEndTime'],
            [['start_time', 'end_time'], 'required'],

            [['created_at', 'updated_at'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],

            [['event'], 'string'],

            [['organization_name'], 'string', 'max' => 64],

            ['status', 'default', 'value' => self::STATUS_PENDING],
            ['status', 'in', 'range' => [self::STATUS_PENDING, self::STATUS_APPROVED, self::STATUS_REJECTED]],

            [['applicant_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['applicant_id' => 'id']],
            [['room_id'], 'exist', 'skipOnError' => true, 'targetClass' => Room::class, 'targetAttribute' => ['room_id' => 'id']],
        ];
    }

    /**
     * 判断输入的开始时间是否合法
     *
     * @param $attribute
     */
    public function validateStartTime($attribute)
    {
        if (!$this->hasErrors()) {
            $startTime = $this->start_time;
            if ($startTime < time()) {
                $this->addError($attribute, '开始时间必须大于现在时间。');
            } else if ($startTime > time() + 3600 * 24 * 30) {
                $this->addError($attribute, '开始时间必须在一个月内。');
            }
        }
    }

    /**
     * 判断输入的结束时间是否合法
     *
     * @param $attribute
     */
    public function validateEndTime($attribute)
    {
        if (!$this->hasErrors()) {
            $startTime = $this->start_time;
            $endTime = $this->end_time;
            if ($startTime > $endTime) {
                $this->addError($attribute, '结束时间必须大于开始时间。');
            } else if ($endTime - $startTime > 3600 * 12) {
                $this->addError($attribute, '持续时间不能超过十二小时。');
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => '申请编号',
            'applicant_id' => '申请人ID',
            'organization_name' => '组织名',
            'room_id' => '房间ID',
            'start_time' => '开始时间',
            'end_time' => '结束时间',
            'event' => '申请理由',
            'status' => '审批状态',
            'created_at' => '创建时间',
            'updated_at' => '修改时间',
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function getApplicant()
    {
        return $this->hasOne(User::class, ['id' => 'applicant_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getRoom()
    {
        return $this->hasOne(Room::class, ['id' => 'room_id']);
    }

    /**
     * 获取所有状态列表
     *
     * @return array
     */
    public static function getAllStatus()
    {
        return [
            self::STATUS_PENDING => '待审核',
            self::STATUS_APPROVED => '已批准',
            self::STATUS_REJECTED => '已拒绝',
        ];
    }

    /**
     * 获取当前申请状态字符串
     *
     * @return string
     */
    public function getStatusStr()
    {
        return self::getAllStatus()[$this->status];
    }

    /**
     * 获取从当前时间开始待审批的申请数
     *
     * @return int|string
     */
    public static function getPendingApplicationCount()
    {
        $time = time();
        return Application::find()
            ->where(['status' => 0])
            ->andWhere("start_time > $time")
            ->count();
    }

    /**
     * 获取审批状态背景色
     *
     * @return array
     */
    public function getStatusBg()
    {
        $options = [];
        switch ($this->status) {
            case self::STATUS_PENDING:
                $options['class'] = 'bg-info';
                break;
            case self::STATUS_APPROVED:
                $options['class'] = 'bg-success';
                break;
            case self::STATUS_REJECTED:
                $options['class'] = 'bg-danger';
                break;
        }
        return $options;
    }

    /**
     * 获取操作栏背景色
     *
     * @return array
     */
    public function getActionBg()
    {
        $overlap = Application::find()
            ->select('id')
            ->where("not (start_time >= $this->end_time or end_time <= $this->start_time)")
            ->andWhere(['status' => self::STATUS_APPROVED])
            ->andWhere(['room_id' => $this->room_id])
            ->andWhere("id != $this->id")
            ->count();

        if (($overlap > 0 && $this->status == self::STATUS_PENDING)
            || (!$this->room->available && $this->status != self::STATUS_REJECTED)) {
            return ['class' => 'bg-warning'];
        }

        return [];
    }

    /**
     * 判断是否应该显示修改或审批按钮
     *
     * @return bool
     */
    public function canUpdate()
    {
        if ($this->start_time < time() || $this->status == self::STATUS_REJECTED) {
            return false;
        }
        return true;
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
            if ($insert) {
                $this->created_at = time();
                $this->updated_at = time();
            } else {
                $this->updated_at = time();
            }
            return true;
        }
        return false;
    }

    /**
     * 获取冲突申请 ID
     *
     * @return array
     */
    public function getConflictId()
    {
        return Application::find()
            ->select('id')
            ->where("not (start_time >= $this->end_time or end_time <= $this->start_time)")
            ->andWhere(['status' => self::STATUS_APPROVED])
            ->andWhere(['room_id' => $this->room_id])
            ->andWhere("id != $this->id")
            ->column();
    }
}
