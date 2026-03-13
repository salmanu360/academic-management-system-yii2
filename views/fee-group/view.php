<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\FeeGroup */
?>
<div class="fee-group-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'fk_class_id',
        'value'=>function($data){
            if($data){
                return $data->class->title;
            }else{
                return "N/A";
            }
        }
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'fk_group_id',
        'value'=>function($data){
            if($data){
                return $data->fkGroup->title;
            }else{
                return "N/A";
            }
        }
    ],

    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'fk_fee_head_id',
        'value'=>function($data){
            if($data){
                return $data->fkFeeHead->title;
            }else{
                return "N/A";
            }
        }
    ],
            'created_date',
            'updated_date',
            'updated_by',
            'is_active',
            'amount',
        ],
    ]) ?>

</div>
