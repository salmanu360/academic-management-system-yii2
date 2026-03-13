<?php

namespace app\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\BookIssue;

/**
 * BookIssueSearch represents the model behind the search form of `app\models\BookIssue`.
 */
class BookIssueSearch extends BookIssue
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'book_id', 'class_id', 'group_id', 'section_id', 'user_id', 'fk_branch_id'], 'integer'],
            [['issue_date', 'due_date', 'return_date', 'fine', 'remarks', 'status'], 'safe'],
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
        $query = BookIssue::find();

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
            'book_id' => $this->book_id,
            'class_id' => $this->class_id,
            'group_id' => $this->group_id,
            'section_id' => $this->section_id,
            'user_id' => $this->user_id,
            'issue_date' => $this->issue_date,
            'due_date' => $this->due_date,
            'return_date' => $this->return_date,
            'fk_branch_id' => $this->fk_branch_id,
        ]);

        $query->andFilterWhere(['like', 'fine', $this->fine])
            ->andFilterWhere(['like', 'remarks', $this->remarks])
            ->andFilterWhere(['like', 'status', $this->status]);

        return $dataProvider;
    }
}
