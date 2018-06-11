<?php

namespace common\models;

use Yii;
use yii\db\Query;

/**
 * This is the model class for table "application".
 *
 * @property int $id
 * @property int $applicant_id
 * @property string $organization_name
 * @property int $room_id
 * @property string $start_time
 * @property string $end_time
 * @property string $event
 * @property int $status
 *
 * @property User $applicant
 * @property Room $room
 */
class Application extends \yii\db\ActiveRecord
{
    const STATUS_PENDDING = 0;
    const STATUS_APPROVED = 1;
    const STATUS_REJECTED = 2;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'application';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['applicant_id', 'room_id', 'event', 'status'], 'required'],
            [['applicant_id', 'room_id'], 'integer'],
            [['start_time', 'end_time'], 'safe'],
            [['event'], 'string'],
            [['organization_name'], 'string', 'max' => 64],
            ['status', 'default', 'value' => self::STATUS_PENDDING],
            ['status', 'in', 'range' => [self::STATUS_PENDDING, self::STATUS_APPROVED, self::STATUS_REJECTED]],
            [['applicant_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['applicant_id' => 'id']],
            [['room_id'], 'exist', 'skipOnError' => true, 'targetClass' => Room::className(), 'targetAttribute' => ['room_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '编号',
            'applicant_id' => '申请人ID',
            'organization_name' => '组织名',
            'room_id' => '房间ID',
            'start_time' => '开始时间',
            'end_time' => '结束时间',
            'event' => '申请理由',
            'status' => '审批状态',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getApplicant()
    {
        return $this->hasOne(User::className(), ['id' => 'applicant_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRoom()
    {
        return $this->hasOne(Room::className(), ['id' => 'room_id']);
    }

    /**
     * 获取所有状态列表
     *
     * @return array
     */
    public static function getAllStatus()
    {
        return [
            self::STATUS_PENDDING => '待审核',
            self::STATUS_APPROVED => '已批准',
            self::STATUS_REJECTED => '已拒绝',
        ];
    }

    /**
     * 获取当前申请状态字符串
     *
     * @return string|null
     */
    public function getStatusStr() {
        switch ($this->status) {
            case self::STATUS_PENDDING:
                return '待审核';
            case self::STATUS_APPROVED:
                return '已批准';
            case self::STATUS_REJECTED:
                return '已拒绝';
        }
        return null;
    }

    public static function getPendingApplicationCount() {
        return Application::find()->where(['status' => 0])->count();
    }

    public function getStatusBg()
    {
        $options = [];
        switch ($this->status) {
            case self::STATUS_PENDDING:
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

    public function getActionBg()
    {
        $tmp = self::STATUS_APPROVED;

        $overlap = (new Query())
            ->select('id')
            ->from('application')
            ->where("not (start_time >= $this->end_time or end_time <= $this->start_time)")
            ->andWhere("status = $tmp")
            ->andWhere("id != $this->id")
            ->count();

        if ($overlap > 0 || !$this->room->available) {
            return ['class' => 'bg-warning'];
        }

        return [];
    }
}
