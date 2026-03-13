<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\LeaveDetails */
?>
<div class="leave-details-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'leave_category',
        'value'=>function($data){
            if(count($data)>0){
                return $data->leaveCategory->leave_category;
            }else{
                return 'N/A';
            }
        }
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'designation',
        'value'=>function($data){
            if(count($data)>0){
                return $data->leaveDesignation->Title;
            }else{
                return 'N/A';
            }
        }
    ],
            'count',
        ],
    ]) ?>

</div>
