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
        'attribute'=>'class_id',  
        'value'=>function($data){ 
            $class = \app\models\RefClass::find()->select(['title'])->where(['fk_branch_id'=>Yii::$app->common->getBranch(),'class_id'=>$data->class_id,'status'=>'active'])->one();
              
            if($class){
                return ucfirst($class->title);
            }else{
                return 'N/A';
            }
             
        }
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'amount',
    ],
    
     [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'status',  
        'value'=>function($data){  
           return ucfirst($data->status);   
        }
    ],
    // [
        // 'class'=>'\kartik\grid\DataColumn',
        // 'attribute'=>'created_at',
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