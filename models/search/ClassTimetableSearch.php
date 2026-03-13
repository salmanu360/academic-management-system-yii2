<?php

namespace app\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\ClassTimetable;

/**
 * ClassTimetableSearch represents the model behind the search form of `app\models\ClassTimetable`.
 */
class ClassTimetableSearch extends ClassTimetable
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'fk_branch_id', 'class_id', 'group_id', 'subject_id'], 'integer'],
            [['day', 'start_date', 'end_date'], 'safe'],
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
        $query = ClassTimetable::find();

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
            'fk_branch_id' => $this->fk_branch_id,
            'class_id' => $this->class_id,
            'group_id' => $this->group_id,
            'subject_id' => $this->subject_id,
        ]);

        $query->andFilterWhere(['like', 'day', $this->day])
            ->andFilterWhere(['like', 'start_date', $this->start_date])
            ->andFilterWhere(['like', 'end_date', $this->end_date]);

        return $dataProvider;
    }
}
