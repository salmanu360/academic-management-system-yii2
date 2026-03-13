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
         'attribute'=>'addlibrary_category_id',
         'value'=>function($data){
            return $data->category->category_name;
         }
     ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'book_isbn_no',
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'book_no',
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'title',
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'author',
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'edition',
    ],
    
    // [
        // 'class'=>'\kartik\grid\DataColumn',
        // 'attribute'=>'publisher',
    // ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'no_of_copies',
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'remaining_copies',
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'rack_no',
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'shelf_no',
    ],
    // [
        // 'class'=>'\kartik\grid\DataColumn',
        // 'attribute'=>'book_position',
    // ],
    // [
        // 'class'=>'\kartik\grid\DataColumn',
        // 'attribute'=>'book_cost',
    // ],
    // [
        // 'class'=>'\kartik\grid\DataColumn',
        // 'attribute'=>'language',
    // ],
    // [
        // 'class'=>'\kartik\grid\DataColumn',
        // 'attribute'=>'book_condition',
    // ],
    // [
        // 'class'=>'\kartik\grid\DataColumn',
        // 'attribute'=>'fk_branch_id',
    // ],
    // [
        // 'class'=>'\kartik\grid\DataColumn',
        // 'attribute'=>'status',
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