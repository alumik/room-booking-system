<?php

namespace frontend\models;

use common\models\Application;
use common\models\Room;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * 房间筛选模型
 */
class RoomSearch extends Room
{
    public $start_time;
    public $end_time;
    public $queue_count;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'type', 'campus', 'available'], 'integer'],
            [['room_number', 'start_time', 'end_time'], 'safe'],
            //[['start_time', 'end_time'], 'required'],
            [['start_time'], 'validateStartTime'],
            [['end_time'], 'validateEndTime'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'start_time' => '开始时间',
            'end_time' => '结束时间',
        ];
    }

    public function validateStartTime($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $s_time = strtotime($this->start_time);
            if ($s_time < time()) {
                $this->addError($attribute, '开始时间必须大于现在时间。');
            } else if ($s_time > time() + 3600 * 24 * 30) {
                $this->addError($attribute, '开始时间必须在一个月内。');
            }
        }
    }

    public function validateEndTime($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $s_time = strtotime($this->start_time);
            $e_time = strtotime($this->end_time);
            if ($s_time > $e_time) {
                $this->addError($attribute, '结束时间必须大于开始时间。');
            } else if ($e_time - $s_time > 3600 * 12) {
                $this->addError($attribute, '持续时间不能超过十二小时。');
            }
        }
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        return Model::scenarios();
    }

    /**
     * 根据查询条件生成dataProvider
     *
     * @param array $params
     *
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
