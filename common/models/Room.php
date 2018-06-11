<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "room".
 *
 * @property int $id
 * @property string $room_number
 * @property int $type
 * @property int $campus
 *
 * @property Application[] $applications
 * @property RoomType $type0
 * @property Campus $campus0
 */
class Room extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'room';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['room_number', 'type', 'campus'], 'required'],
            [['type', 'campus'], 'integer'],
            [['room_number'], 'string', 'max' => 10],
            [['type'], 'exist', 'skipOnError' => true, 'targetClass' => RoomType::className(), 'targetAttribute' => ['type' => 'id']],
            [['campus'], 'exist', 'skipOnError' => true, 'targetClass' => Campus::className(), 'targetAttribute' => ['campus' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'room_number' => '房间号',
            'type' => '房间类型',
            'campus' => '校区',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getApplications()
    {
        return $this->hasMany(Application::className(), ['room_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getType0()
    {
        return $this->hasOne(RoomType::className(), ['id' => 'type']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCampus0()
    {
        return $this->hasOne(Campus::className(), ['id' => 'campus']);
    }

    public static function getAllTypes()
    {
        return RoomType::find()
            ->select(['type_name', 'id'])
            ->orderBy('id')
            ->indexBy('id')
            ->column();
    }

    public static function getAllCampus()
    {
        return Campus::find()
            ->select(['campus_name', 'id'])
            ->orderBy('id')
            ->indexBy('id')
            ->column();
    }
}
