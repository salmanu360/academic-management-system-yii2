<?php

namespace app\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\TransportAllocation;

/**
 * TransportAllocationSearch represents the model behind the search form about `app\models\TransportAllocation`.
 */
class TransportAllocationSearch extends TransportAllocation
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'fk_stop_id', 'zone_id', 'route_id', 'stu_id', 'status', 'branch_id'], 'integer'],
            [['allotment_date', 'created_date'], 'safe'],
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
        $query = TransportAllocation::find();

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
            'fk_stop_id' => $this->fk_stop_id,
            'zone_id' => $this->zone_id,
            'route_id' => $this->route_id,
            'stu_id' => $this->stu_id,
            'status' => $this->status,
            'allotment_date' => $this->allotment_date,
            'created_date' => $this->created_date,
            'branch_id' => $this->branch_id,
        ]);

        return $dataProvider;
    }
}
