<?php

namespace app\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\AddBooks;

/**
 * AddBooksSearch represents the model behind the search form about `app\models\AddBooks`.
 */
class AddBooksSearch extends AddBooks
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'addlibrary_category_id', 'no_of_copies', 'book_cost', 'language', 'fk_branch_id'], 'integer'],
            [['book_isbn_no', 'book_no', 'title', 'author', 'edition', 'publisher', 'rack_no', 'shelf_no', 'book_position', 'book_condition', 'status'], 'safe'],
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
        $query = AddBooks::find();

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
            'addlibrary_category_id' => $this->addlibrary_category_id,
            'no_of_copies' => $this->no_of_copies,
            'book_cost' => $this->book_cost,
            'language' => $this->language,
            'fk_branch_id' => $this->fk_branch_id,
            'status' => $this->status,
        ]);

        $query->andFilterWhere(['like', 'book_isbn_no', $this->book_isbn_no])
            ->andFilterWhere(['like', 'book_no', $this->book_no])
            ->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'author', $this->author])
            ->andFilterWhere(['like', 'edition', $this->edition])
            ->andFilterWhere(['like', 'publisher', $this->publisher])
            ->andFilterWhere(['like', 'rack_no', $this->rack_no])
            ->andFilterWhere(['like', 'shelf_no', $this->shelf_no])
            ->andFilterWhere(['like', 'book_position', $this->book_position])
            ->andFilterWhere(['like', 'book_condition', $this->book_condition])
            ->andFilterWhere(['like', 'status', $this->status]);

        return $dataProvider;
    }
}
