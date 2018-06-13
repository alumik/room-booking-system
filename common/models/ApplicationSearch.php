<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Application;

/**
 * ApplicationSearch represents the model behind the search form of `common\models\Application`.
 */
class ApplicationSearch extends Application
{
    public $room_number;
    public $campus;
    public $applicant_student_id;
    public $applicant_name;
    public $start_time_picker;
    public $end_time_picker;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'applicant_id', 'room_id'], 'integer'],
            [['organization_name', 'start_time', 'end_time', 'event', 'status'], 'safe'],
            [['room_number', 'campus', 'applicant_student_id', 'applicant_name'], 'safe'],
            [['start_time_picker', 'end_time_picker'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Application::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => ['pageSize' => 30],
            'sort' => [
                'attributes' => [
                    'id',
                    'room_number',
                    'campus',
                    'applicant_student_id',
                    'applicant_name',
                    'organization_name',
                    'start_time',
                    'end_time',
                    'status',
                ],
                'defaultOrder' => [
                    'id' => SORT_DESC,
                ],
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'applicant_id' => $this->applicant_id,
            'room_id' => $this->room_id,
            'application.status' => $this->status,
        ]);

        $query->join('INNER JOIN', 'room', 'room.id = application.room_id')
            ->join('INNER JOIN', 'user', 'user.id = application.applicant_id');

        $query->andFilterWhere(['like', 'room.room_number', $this->room_number])
            ->andFilterWhere(['like', 'room.campus', $this->campus])
            ->andFilterWhere(['like', 'user.student_id', $this->applicant_student_id])
            ->andFilterWhere(['like', 'user.username', $this->applicant_name]);

        $s_time = strtotime($this->start_time_picker);
        $e_time = strtotime($this->end_time_picker);

        $query->andWhere("not(end_time < $s_time or start_time > $e_time)");

        $query->andFilterWhere(['like', 'organization_name', $this->organization_name])
            ->andFilterWhere(['like', 'event', $this->event]);

        return $dataProvider;
    }
}
