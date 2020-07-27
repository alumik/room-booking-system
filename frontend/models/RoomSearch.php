<?php

namespace frontend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Room;

class RoomSearch extends Room
{
    public $start_time;
    public $end_time;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'type', 'campus', 'available'], 'integer'],
            [['room_number', 'start_time', 'end_time'], 'safe'],
            ['start_time', 'validateStartTime'],
            ['end_time', 'validateEndTime'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'start_time' => '开始时间',
            'end_time' => '结束时间',
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
            $startTime = strtotime($this->start_time);
            if ($startTime < time()) {
                $this->addError($attribute, '开始时间必须大于现在时间。');
            } else if ($startTime > time() + 3600 * 24 * 30) {
                $this->addError($attribute, '开始时间必须在距今一个月内。');
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
            $startTime = strtotime($this->start_time);
            $endTime = strtotime($this->end_time);
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
    public function scenarios()
    {
        return Model::scenarios();
    }

    /**
     * 根据查询条件进行搜索
     *
     * @param array $params
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Room::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'attributes' => [
                    'campus' => [
                        'asc' => ['campus' => SORT_ASC, 'room_number' => SORT_ASC],
                        'desc' => ['color' => SORT_DESC, 'room_number' => SORT_DESC],
                    ],
                    'room_number',
                    'type',
                ],
                'defaultOrder' => [
                    'campus' => SORT_ASC,
                ]
            ],
            'pagination' => ['pageSize' => 15],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            $query->where('0 = 1');
            return $dataProvider;
        }

        if (empty($this->start_time) || empty($this->end_time)) {
            $query->where('0 = 1');
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'type' => $this->type,
            'campus' => $this->campus,
            'available' => Room::STATUS_AVAILABLE,
        ]);

        $query->andFilterWhere(['like', 'room_number', $this->room_number]);

        return $dataProvider;
    }
}
