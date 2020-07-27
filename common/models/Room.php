<?php

namespace common\models;

use yii\db\ActiveQuery;
use yii\db\Query;
use yii\db\ActiveRecord;

/**
 *
 * @property int $id
 * @property string $room_number
 * @property int $type
 * @property int $campus
 * @property int $available
 *
 * @property Application[] $applications
 * @property RoomType $type0
 * @property-read array $statusBg
 * @property-read null|string $coloredStatusStr
 * @property-read null|string $statusStr
 * @property Campus $campus0
 */
class Room extends ActiveRecord
{
    const STATUS_AVAILABLE = 1;
    const STATUS_UNAVAILABLE = 0;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'room';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['room_number', 'type', 'campus'], 'required'],
            [['type', 'campus'], 'integer'],
            ['available', 'default', 'value' => self::STATUS_AVAILABLE],
            ['available', 'in', 'range' => [self::STATUS_AVAILABLE, self::STATUS_UNAVAILABLE]],
            [['room_number'], 'string', 'max' => 10],
            [['type'], 'exist', 'skipOnError' => true, 'targetClass' => RoomType::class, 'targetAttribute' => ['type' => 'id']],
            [['campus'], 'exist', 'skipOnError' => true, 'targetClass' => Campus::class, 'targetAttribute' => ['campus' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'room_number' => '房间号',
            'type' => '类型',
            'campus' => '校区',
            'available' => '状态',
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function getApplications()
    {
        return $this->hasMany(Application::class, ['room_id' => 'id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getType0()
    {
        return $this->hasOne(RoomType::class, ['id' => 'type']);
    }

    /**
     * @return ActiveQuery
     */
    public function getCampus0()
    {
        return $this->hasOne(Campus::class, ['id' => 'campus']);
    }

    /**
     * 返回所有房间类型数组
     *
     * @return array
     */
    public static function getAllTypes()
    {
        return RoomType::find()
            ->select(['type_name', 'id'])
            ->orderBy('id')
            ->indexBy('id')
            ->column();
    }

    /**
     * 返回所有校区数组
     *
     * @return array
     */
    public static function getAllCampus()
    {
        return Campus::find()
            ->select(['campus_name', 'id'])
            ->orderBy('id')
            ->indexBy('id')
            ->column();
    }

    /**
     * 获取房间状态背景色
     *
     * @return array
     */
    public function getStatusBg()
    {
        $options = [];
        switch ($this->available) {
            case self::STATUS_AVAILABLE:
                $options['class'] = 'bg-success';
                break;
            case self::STATUS_UNAVAILABLE:
                $options['class'] = 'bg-danger';
                break;
        }
        return $options;
    }

    /**
     * 获取当前房间状态字符串
     *
     * @return string
     */
    public function getStatusStr()
    {
        return self::getAllStatus()[$this->available];
    }

    /**
     * 获取当前房间状态字符串（上色）
     *
     * @return string|null
     */
    public function getColoredStatusStr()
    {
        $statusStr = $this->getStatusStr();
        switch ($this->available) {
            case self::STATUS_AVAILABLE:
                return "<span class=\"text-success\">（{$statusStr}）</span>";
            case self::STATUS_UNAVAILABLE:
                return "<span class=\"text-danger\">（{$statusStr}）</span>";
        }
        return null;
    }

    /**
     * 获取所有房间状态数组
     *
     * @return array
     */
    public static function getAllStatus()
    {
        return [
            self::STATUS_AVAILABLE => '可用',
            self::STATUS_UNAVAILABLE => '不可用',
        ];
    }

    /**
     * 切换房间状态
     */
    public function changeStatus()
    {
        $this->available = !$this->available;
    }

    /**
     * 获取相应时间内某房间的排队人数
     *
     * @param string $startTime
     * @param string $endTime
     * @return int|string
     */
    public function getQueueCount($startTime, $endTime)
    {
        $startTime = strtotime($startTime);
        $endTime = strtotime($endTime);

        return (new Query())
            ->select('id')
            ->from('application')
            ->where("not (start_time >= $endTime or end_time <= $startTime)")
            ->andWhere(['status' => Application::STATUS_PENDING])
            ->andWhere(['room_id' => $this->id])
            ->count();
    }

    /**
     * 获取相应时间内某房间是否已被分配及背景颜色
     *
     * @param string $startTime
     * @param string $endTime
     * @return array
     */
    public function getApprovalStatus($startTime, $endTime)
    {
        $startTime = strtotime($startTime);
        $endTime = strtotime($endTime);

        $overlap = (new Query())
            ->select('id')
            ->from('application')
            ->where("not (start_time >= $endTime or end_time <= $startTime)")
            ->andWhere(['status' => Application::STATUS_APPROVED])
            ->andWhere(['room_id' => $this->id])
            ->count();

        if ($overlap > 0) {
            return ['text' => '已分配', 'class' => ['class' => 'bg-danger']];
        }
        return ['text' => '未分配', 'class' => ['class' => 'bg-success']];
    }
}
