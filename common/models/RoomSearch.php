<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * 房间筛选模型
 */
class RoomSearch extends Room
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'type', 'campus'], 'integer'],
            [['room_number'], 'safe'],
        ];
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

        $query->andFilterWhere([
            'id' => $this->id,
            'type' => $this->type,
            'campus' => $this->campus,
        ]);

        $query->andFilterWhere(['like', 'room_number', $this->room_number]);

        return $dataProvider;
    }
}
