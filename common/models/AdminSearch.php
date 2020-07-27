<?php

namespace common\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;

class AdminSearch extends Admin
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'created_at', 'updated_at'], 'integer'],
            [['super_admin', 'web_admin', 'student_admin', 'room_admin',
                'admin_id', 'admin_name', 'auth_key', 'password_hash',
                'password_reset_token', 'email'], 'safe'],
        ];
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
        $query = Admin::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['admin_id' => SORT_ASC]],
            'pagination' => ['pageSize' => 30],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'super_admin' => $this->super_admin,
            'web_admin' => $this->web_admin,
            'student_admin' => $this->student_admin,
            'room_admin' => $this->room_admin,
        ]);

        $query->andFilterWhere(['like', 'admin_id', $this->admin_id])
            ->andFilterWhere(['like', 'admin_name', $this->admin_name])
            ->andFilterWhere(['like', 'email', $this->email]);

        return $dataProvider;
    }
}
