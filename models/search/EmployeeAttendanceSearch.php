<?php

namespace app\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\EmployeeAttendance;

/**
 * EmployeeAttendanceSearch represents the model behind the search form of `app\models\EmployeeAttendance`.
 */
class EmployeeAttendanceSearch extends EmployeeAttendance
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'fk_empl_id', 'fk_branch_id'], 'integer'],
            [['date', 'leave_type', 'remarks', 'time'], 'safe'],
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
        $query = EmployeeAttendance::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
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
            'fk_empl_id' => $this->fk_empl_id,
            'date' => $this->date,
            'fk_branch_id' => $this->fk_branch_id,
        ]);

        $query->andFilterWhere(['like', 'leave_type', $this->leave_type])
            ->andFilterWhere(['like', 'remarks', $this->remarks])
            ->andFilterWhere(['like', 'time', $this->time]);

        return $dataProvider;
    }
}
