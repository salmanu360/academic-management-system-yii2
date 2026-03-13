<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\FeePlan */
?>
<div class="fee-plan-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            //'id',
            [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'stu_id',
        'value'=>function($data){
            $user_id = \app\models\StudentInfo::find()->select(['user_id'])->where(['stu_id'=>$data->stu_id])->one();
            return (!empty($data->stu_id))?ucfirst(\Yii::$app->common->getName($user_id->user_id)):'N/A';
        }
    ],
    [
    'class'=>'\kartik\grid\DataColumn',
    'attribute'=>'fee_head_id',
    'filter'=>\Yii\helpers\ArrayHelper::map(\app\models\FeeHead::find()->select(['id','title'])->all(),'id','title'),
    'value'=>function($data){
    $fee_heads = \app\models\FeeHead::find()->select(['title'])->where(['id'=>$data->fee_head_id])->one();
    return (!empty($fee_heads->title))?ucfirst($fee_heads->title):'N/A';
    }
    ],
            'discount',
            //'status',
            'created_at',
            ///'branch_id',
        ],
    ]) ?>

</div>
