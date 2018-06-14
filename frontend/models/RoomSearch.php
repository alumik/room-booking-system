<?php

namespace frontend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Room;

/**
 * @author 钟震宇 <nczzy1997@gmail.com>
 *
 * 前台 房间 筛选模型
 */
class RoomSearch extends Room
{
    public $start_time_str;
    public $end_time_str;
    public $queue_count;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'type', 'campus', 'available'], 'integer'],
            [['room_number', 'start_time_str', 'end_time_str'], 'safe'],
            ['start_time_str', 'validateStartTime'],
            ['end_time_str', 'validateEndTime'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'start_time_str' => '开始时间',
            'end_time_str' => '结束时间',
        ];
    }

    /**
     * 判断输入的开始时间是否合法
     *
     * @param $attribute
     * @param $params
     */
    public function validateStartTime($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $s_time = strtotime($this->start_time_str);
            if ($s_time < time()) {
                $this->addError($attribute, '开始时间必须大于现在时间。');
            } else if ($s_time > time() + 3600 * 24 * 30) {
                $this->addError($attribute, '开始时间必须在一个月内。');
            }
        }
    }

    /**
     * 判断输入的结束时间是否合法
     *
     * @param $attribute
     * @param $params
     */
    public function validateEndTime($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $s_time = strtotime($this->start_time_str);
            $e_time = strtotime($this->end_time_str);
            if ($s_time > $e_time) {
                $this->addError($attribute, '结束时间必须大于开始时间。');
            } else if ($e_time - $s_time > 3600 * 12) {
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
     * 根据查询条件生成dataProvider
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
            'pagination' => ['pageSize' => 30],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            $query->where('0 = 1');
            return $dataProvider;
        }

        if (empty($this->start_time_str) || empty($this->end_time_str)) {
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
