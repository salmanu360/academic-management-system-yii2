<?php

namespace app\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\FeeHead;

/**
 * FeeHeadSearch represents the model behind the search form about `app\models\FeeHead`.
 */
class FeeHeadSearch extends FeeHead
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'extra_head', 'one_time_payment', 'branch_id'], 'integer'],
            [['title', 'date'], 'safe'],
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
        $query = FeeHead::find();

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
            'extra_head' => $this->extra_head,
            'one_time_payment' => $this->one_time_payment,
            'date' => $this->date,
            'branch_id' => $this->branch_id,
        ]);

        $query->andFilterWhere(['like', 'title', $this->title]);

        return $dataProvider;
    }
}
