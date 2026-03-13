<?php

namespace app\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\FeeArears;

/**
 * FeeArearsSearch represents the model behind the search form about `app\models\FeeArears`.
 */
class FeeArearsSearch extends FeeArears
{
    public $class;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'stu_id', 'fee_head_id', 'arears', 'status', 'branch_id','class'], 'integer'],
            [['date'], 'safe'],
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
        /*$class = $params['FeeArearsSearch']['class'];  */
        $query = FeeArears::find(); 
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
            'stu_id' => $this->stu_id,
            'fee_head_id' => $this->fee_head_id,
            'arears' => $this->arears,
            'date' => $this->date,
            'status' => $this->status,
            'branch_id' => $this->branch_id,
        ]);

        return $dataProvider;
    }
}
