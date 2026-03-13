<?php

namespace app\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\StudentOutside;

/**
 * StudentOutsideSearch represents the model behind the search form of `app\models\StudentOutside`.
 */
class StudentOutsideSearch extends StudentOutside
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'class_id', 'group_id', 'section_id', 'branch_id'], 'integer'],
            [['name', 'regesteration_date'], 'safe'],
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
        $query = StudentOutside::find();

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
            'class_id' => $this->class_id,
            'group_id' => $this->group_id,
            'section_id' => $this->section_id,
            'parent_name' => $this->parent_name,
            'regesteration_date' => $this->regesteration_date,
            'contact_no' => $this->contact_no,
            'address' => $this->address,
            'branch_id' => $this->branch_id,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name]);

        return $dataProvider;
    }
}
