<?php

namespace common\models;

use yii\db\ActiveRecord;

/**
 * @author 钟震宇 <nczzy1997@gmail.com>
 *
 * 公共 房间类型 模型
 *
 * @property int $id
 * @property string $type_name
 *
 * @property Room[] $rooms
 */
class RoomType extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'room_type';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['type_name'], 'required'],
            [['type_name'], 'string', 'max' => 64],
            [['type_name'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'type_name' => 'Type Name',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRooms()
    {
        return $this->hasMany(Room::className(), ['type' => 'id']);
    }
}
