<?php

namespace app\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Visitors;

/**
 * VisitorsSearch represents the model behind the search form of `app\models\Visitors`.
 */
class VisitorsSearch extends Visitors
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'to_meet', 'branch_id'], 'integer'],
            [['name', 'email', 'phone', 'cnic', 'company', 'representing', 'address', 'date'], 'safe'],
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
        $query = Visitors::find();

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
            'to_meet' => $this->to_meet,
            'date' => $this->date,
            'branch_id' => $this->branch_id,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'phone', $this->phone])
            ->andFilterWhere(['like', 'cnic', $this->cnic])
            ->andFilterWhere(['like', 'company', $this->company])
            ->andFilterWhere(['like', 'representing', $this->representing])
            ->andFilterWhere(['like', 'address', $this->address]);

        return $dataProvider;
    }
}
