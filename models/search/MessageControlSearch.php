<?php

namespace app\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\MessageControl;

/**
 * MessageControlSearch represents the model behind the search form about `app\models\MessageControl`.
 */
class MessageControlSearch extends MessageControl
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'branch_id', 'created_by'], 'integer'],
            [['message_id', 'message', 'created_at'], 'safe'],
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
        $query = MessageControl::find();

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
            'branch_id' => $this->branch_id,
            'created_at' => $this->created_at,
            'created_by' => $this->created_by,
        ]);

        $query->andFilterWhere(['like', 'message_id', $this->message_id])
            ->andFilterWhere(['like', 'message', $this->message]);

        return $dataProvider;
    }
}
