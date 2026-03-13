<?php

namespace app\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\ExamGrading as ExamGradingModel;

/**
 * ExamGrading represents the model behind the search form about `app\models\ExamGrading`.
 */
class ExamGrading extends ExamGradingModel
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'updated_id', 'branch_id'], 'integer'],
            [['grade', 'marks_obtain_from', 'marks_obtain_to', 'grade_name', 'created_by', 'created_date', 'updated_date'], 'safe'],
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
        $query = ExamGradingModel::find();

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
            'created_by' => $this->created_by,
            'updated_id' => $this->updated_id,
            'created_date' => $this->created_date,
            'updated_date' => $this->updated_date,
            'branch_id' => $this->branch_id,
        ]);

        $query->andFilterWhere(['like', 'grade', $this->grade])
            ->andFilterWhere(['like', 'marks_obtain_from', $this->marks_obtain_from])
            ->andFilterWhere(['like', 'marks_obtain_to', $this->marks_obtain_to])
            ->andFilterWhere(['like', 'grade_name', $this->grade_name]);

        return $dataProvider;
    }
}
