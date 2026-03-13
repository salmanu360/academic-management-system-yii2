<?php

namespace app\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\MesagesOther;

/**
 * MesagesOtherSearch represents the model behind the search form about `app\models\MesagesOther`.
 */
class MesagesOtherSearch extends MesagesOther
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'fk_branch_id'], 'integer'],
            [['name', 'designation', 'organization', 'date', 'address','contact'], 'safe'],
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
        $query = MesagesOther::find();

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
            'date' => $this->date,
            'fk_branch_id' => $this->fk_branch_id,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'designation', $this->designation])
            ->andFilterWhere(['like', 'organization', $this->organization])
            ->andFilterWhere(['like', 'contact', $this->contact])
            ->andFilterWhere(['like', 'address', $this->address]);

        return $dataProvider;
    }
}
