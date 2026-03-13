<?php

namespace app\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\LeaveApplication;

/**
 * LeaveApplicationSearch represents the model behind the search form of `app\models\LeaveApplication`.
 */
class LeaveApplicationSearch extends LeaveApplication
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'login_id', 'leave_category', 'fk_branch_id'], 'integer'],
            [['from_date', 'to_date', 'reason', 'approval_status'], 'safe'],
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
        $query = LeaveApplication::find();

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
            'login_id' => $this->login_id,
            'leave_category' => $this->leave_category,
            'fk_branch_id' => $this->fk_branch_id,
        ]);

        $query->andFilterWhere(['like', 'from_date', $this->from_date])
            ->andFilterWhere(['like', 'to_date', $this->to_date])
            ->andFilterWhere(['like', 'reason', $this->reason]);

        return $dataProvider;
    }
}
