<?php

namespace common\models;

use Yii;

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
            [['status'], 'string', 'max' => 1],
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
            'id' => 'ID',
            'applicant_id' => 'Applicant ID',
            'organization_name' => 'Organization Name',
            'room_id' => 'Room ID',
            'start_time' => 'Start Time',
            'end_time' => 'End Time',
            'event' => 'Event',
            'status' => 'Status',
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
}
