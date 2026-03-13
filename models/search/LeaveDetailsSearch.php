<?php

namespace app\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\LeaveDetails;

/**
 * LeaveDetailsSearch represents the model behind the search form about `app\models\LeaveDetails`.
 */
class LeaveDetailsSearch extends LeaveDetails
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'leave_category', 'designation', 'count', 'fk_branch_id'], 'integer'],
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
        $query = LeaveDetails::find();

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
            'leave_category' => $this->leave_category,
            'designation' => $this->designation,
            'count' => $this->count,
            'fk_branch_id' => $this->fk_branch_id,
        ]);

        return $dataProvider;
    }
}
