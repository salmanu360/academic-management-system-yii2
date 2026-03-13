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

    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'fk_stop_id',
        'value'=>function($data){
            return $data->stop->title;
        }
    ],

    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'zone_id',
        'value'=>function($data){
            return $data->zone->title;
        }
    ],

    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'route_id',
        'value'=>function($data){
            return $data->fkRoute->title;
        }
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'discount_amount',
        'value'=>function($data){
            return (!empty($data->discount_amount))?$data->discount_amount:'N/A';
        }
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'stu_id',
        'value'=>function($data){
            return (!empty($data->stu_id))?ucfirst(\Yii::$app->common->getName($data->stu_id)):'N/A';
        }
    ],
    

    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'allotment_date',
        'value'=>function($data){
            return date('Y-m-d',strtotime($data->allotment_date));
        }
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