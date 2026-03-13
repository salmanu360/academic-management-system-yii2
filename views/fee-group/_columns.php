<?php
use yii\helpers\Url;

return [
    [
        'class' => 'kartik\grid\CheckboxColumn',
        'width' => '20px',
    ],
    [
        'class' => 'kartik\grid\SerialColumn',
        'width' => '30px',
    ],
        // [
        // 'class'=>'\kartik\grid\DataColumn',
        // 'attribute'=>'id',
    // ],
   
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
            if(count($data->fk_group_id) >0){
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
            if(count($data->fk_fee_head_id)>0){
                return $data->fkFeeHead->title;
            }else{
                return "N/A";
            }
        }
    ],
    /*[
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'fk_fee_head_id',
    ],*/
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'created_date',
    ],
    /*[
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'updated_date',
    ],*/
    // [
        // 'class'=>'\kartik\grid\DataColumn',
        // 'attribute'=>'updated_by',
    // ],
    // [
        // 'class'=>'\kartik\grid\DataColumn',
        // 'attribute'=>'is_active',
    // ],
    // [
        // 'class'=>'\kartik\grid\DataColumn',
        // 'attribute'=>'fk_group_id',
    // ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'amount',
    ],
    [
        'class' => 'kartik\grid\ActionColumn',
        'dropdown' => false,
        'vAlign'=>'middle',
        'urlCreator' => function($action, $model, $key, $index) { 
                return Url::to([$action,'id'=>$key]);
        },
        'viewOptions'=>['role'=>'modal-remote','title'=>'View','data-toggle'=>'tooltip'],
        'updateOptions'=>['role'=>'modal-remote','title'=>'Update', 'data-toggle'=>'tooltip'],
        'deleteOptions'=>['role'=>'modal-remote','title'=>'Delete', 
                          'data-confirm'=>false, 'data-method'=>false,// for overide yii data api
                          'data-request-method'=>'post',
                          'data-toggle'=>'tooltip',
                          'data-confirm-title'=>'Are you sure?',
                          'data-confirm-message'=>'Are you sure want to delete this item'], 
    ],

];   