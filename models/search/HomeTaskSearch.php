<?php

namespace app\models\search; 

use Yii; 
use yii\base\Model; 
use yii\data\ActiveDataProvider; 
use app\models\HomeTask; 

/** 
 * HomeTaskSearch represents the model behind the search form of `app\models\HomeTask`. 
 */ 
class HomeTaskSearch extends HomeTask 
{ 
    /** 
     * @inheritdoc 
     */ 
    public function rules() 
    { 
        return [ 
            [['id', 'class_id', 'group_id', 'subject_id', 'teacher_id', 'fk_branch_id', 'user_id'], 'integer'],
            [['class_work', 'home_task', 'remarks', 'date'], 'safe'], 
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
        $query = HomeTask::find(); 

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
            'subject_id' => $this->subject_id,
            'teacher_id' => $this->teacher_id,
            'date' => $this->date,
            'fk_branch_id' => $this->fk_branch_id,
            'user_id' => $this->user_id,
        ]);

        $query->andFilterWhere(['like', 'class_work', $this->class_work])
            ->andFilterWhere(['like', 'home_task', $this->home_task])
            ->andFilterWhere(['like', 'remarks', $this->remarks]);

        return $dataProvider; 
    } 
} 