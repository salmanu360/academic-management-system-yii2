<?php

namespace app\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Expenses;

/**
 * ExpensesSearch represents the model behind the search form about `app\models\Expenses`.
 */
class ExpensesSearch extends Expenses
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'fk_branch_id', 'expense_category_id', 'payment_mehtod'], 'integer'],
            [['title', 'description', 'date', 'amount'], 'safe'],
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
        $query = Expenses::find();

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
            'fk_branch_id' => $this->fk_branch_id,
            'expense_category_id' => $this->expense_category_id,
            'payment_mehtod' => $this->payment_mehtod,
        ]);

        $query->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'date', $this->date])
            ->andFilterWhere(['like', 'amount', $this->amount]);

        return $dataProvider;
    }
}
