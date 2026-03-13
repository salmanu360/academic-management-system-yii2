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
        'attribute'=>'title',
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'extra_head',
        'value'=>function($data){
            if($data->extra_head == 0){
                return 'No';
            }else{
                return 'Yes';
            }
        }
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'one_time_payment',
        'value'=>function($data){
            if($data->one_time_payment == 0){
                return 'No';
            }else{
                return 'Yes';
            }
        }
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'promotion_head',
        'value'=>function($data){
            if($data->promotion_head == 0){
                return 'No';
            }else{
                return 'Yes';
            }
        }
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'date',
    ],
    // [
        // 'class'=>'\kartik\grid\DataColumn',
        // 'attribute'=>'branch_id',
    // ],
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