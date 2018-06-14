<?php

namespace common\models;

use yii\db\ActiveRecord;

/**
 * @author 钟震宇 <nczzy1997@gmail.com>
 *
 * 公共 校区 模型
 *
 * @property int $id
 * @property string $campus_name
 *
 * @property Room[] $rooms
 */
class Campus extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'campus';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['campus_name'], 'required'],
            [['campus_name'], 'string', 'max' => 64],
            [['campus_name'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'campus_name' => 'Campus Name',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRooms()
    {
        return $this->hasMany(Room::className(), ['campus' => 'id']);
    }
}
