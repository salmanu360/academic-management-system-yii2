<?php

namespace app\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\FeePlan as FeePlanModel;

/**
 * FeePlan represents the model behind the search form about `app\models\FeePlan`.
 */
class FeePlan extends FeePlanModel
{
    /**
     * @inheritdoc
     */
    public $fk_class_id;
    public function rules()
    {
        return [
            [['id', 'discount', 'status', 'branch_id'], 'integer'],
            [['created_at','fee_head_id','stu_id'], 'safe'],
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
     $query = FeePlanModel::find();
     $query->joinWith('head');
     $query->joinWith('student');
            //echo '<pre>';print_r($query);die;
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        /*$getData=Yii::$app->request->get('FeePlan');
        $name=$getData['stu_id'];
        $usertable=\app\models\User::find()->where(['first_name'=>$name])->one();
        $gname=$usertable->first_name;*/
    
        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            // 'stu_id' => $this->stu_id,
            //'fee_head_id' => $this->fee_head_id,
            'discount' => $this->discount,
            //'status' => $this->status,
            'status' => 1,
            'created_at' => $this->created_at,
            //'branch_id' => \Yii::$app->common->getBranch(),//$this->branch_id,
        ]);
        $query->andFilterWhere(['like', 'fee_head.title', $this->fee_head_id]);
         //$usertable->andFilterWhere(['like', 'first_name', $name]);

        return $dataProvider;
    }
}
