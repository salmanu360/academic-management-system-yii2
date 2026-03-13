<?php

namespace app\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\FeeSubmission;

/**
 * FeeSubmissionSearch represents the model behind the search form about `app\models\FeeSubmission`.
 */
class FeeSubmissionSearch extends FeeSubmission
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'stu_id', 'fee_head_id', 'head_recv_amount', 'transport_amount', 'hostel_amount', 'transport_arrears', 'hostel_arrears', 'absent_fine', 'sibling_discount', 'fee_status', 'branch_id'], 'integer'],
            [['from_date', 'to_date', 'year_month_interval', 'recv_date'], 'safe'],
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
        $query = FeeSubmission::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'stu_id' => $this->stu_id,
            'fee_head_id' => $this->fee_head_id,
            'head_recv_amount' => $this->head_recv_amount,
            'transport_amount' => $this->transport_amount,
            'hostel_amount' => $this->hostel_amount,
            'transport_arrears' => $this->transport_arrears,
            'hostel_arrears' => $this->hostel_arrears,
            'absent_fine' => $this->absent_fine,
            'sibling_discount' => $this->sibling_discount,
            'fee_status' => $this->fee_status,
            'branch_id' => $this->branch_id,
        ]);

        $query->andFilterWhere(['like', 'from_date', $this->from_date])
            ->andFilterWhere(['like', 'to_date', $this->to_date])
            ->andFilterWhere(['like', 'year_month_interval', $this->year_month_interval])
            ->andFilterWhere(['like', 'recv_date', $this->recv_date]);

        return $dataProvider;
    }
}
