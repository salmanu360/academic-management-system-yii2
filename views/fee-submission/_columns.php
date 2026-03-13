<?php
use yii\helpers\Url;
use yii\bootstrap\Modal;

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
        'label'=>'Registeration #',
        'value'=>function($data){
          $studentInfo = Yii::$app->common->getStudent($data->stu_id);
           return Yii::$app->common->getUserName($studentInfo->user_id);
        }
    ],
    [
        'label'=>'Roll #',
        'value'=>function($data){
            $student_id = \app\models\StudentInfo::find()->where(['stu_id'=>$data->stu_id])->one();
            return (!empty($student_id->roll_no))?$student_id->roll_no:'N/A';
        }
    ],

    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'stu_id',
        'label'=>'Student',
        'value'=>function($data){
            $studentInfo = Yii::$app->common->getStudent($data->stu_id);
            return Yii::$app->common->getName($studentInfo->user_id);
        }
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'label'=>'Parent',
        'value'=>function($data){
            return (!empty($data->stu_id))?ucfirst(\Yii::$app->common->getParentName($data->stu_id)):'N/A';
        }
    ],

    [
        'label'=>'Class',
        'value'=>function($data){
            $user_id = \app\models\StudentInfo::find()->select(['class_id'])->where(['stu_id'=>$data->stu_id])->one();
            return (!empty($data->stu_id))?$user_id->class->title:'N/A';
        }
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'fee_head_id',
        'label'=>'Fee Head',
        'value'=>function($data){
           $getHeadName=\app\models\FeeHead::find()->where(['branch_id'=>yii::$app->common->getBranch(),'id'=>$data->fee_head_id])->one();  
           return $getHeadName->title;
        }
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'head_recv_amount',
        'label'=>'Fee'

    ],
    [
        'label'=>'Arears',
        'value'=>function($data){
          $stu_id=$data->stu_id;
          $fee_head_id=$data->fee_head_id;
           $feeArrears=\app\models\FeeArears::findOne(['stu_id'=>$stu_id,'fee_head_id'=>$fee_head_id,'status'=>1]);
           return (!empty($feeArrears->arears)?'Rs.'.$feeArrears->arears:'Rs. 0');
        }
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'transport_amount',
        'label'=>'Transport Fee',
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'transport_arrears',
    ],
  
     [
         'class'=>'\kartik\grid\DataColumn',
         'attribute'=>'from_date',
     ],
     [
         'class'=>'\kartik\grid\DataColumn',
         'attribute'=>'to_date',
     ],
     [
         'class'=>'\kartik\grid\DataColumn',
         'attribute'=>'recv_date',
     ],
     [
         'class'=>'\kartik\grid\DataColumn',
         'attribute'=>'fee_status',
         'label'=>'Status',
         'value'=>function($data){
            if($data->fee_status ==1){
                return 'Last Fee';
            }else{
                return 'Previous';
            }
         }
     ],
    
    [
        'class' => 'kartik\grid\ActionColumn',
        'dropdown' => false,
        'vAlign'=>'middle',
        'template'=> '{update}',
        'buttons' => [
            'update' => function ($url, $model, $key)
            {
                    // return \Yii\helpers\Html::a('<span class="glyphicon glyphicon-pencil"></span>', ['update','id'=>$key]); 
              $headAmount= '<i value="'.Url::to(['update','id'=>$key]).'" class="fa fa-money modalButton" style="color:red;cursor:pointer" title="Update Head Fee"></i>';
              $transport= '<i value="'.Url::to(['update-transport','id'=>$key]).'" class="fa fa-truck modalButton" style="color:green;cursor:pointer" title="Update Transport Fee"></i>';
              return $headAmount.' | '.$transport;
             //return \Yii\helpers\Html::button(['value'=>Url::to(['update','id'=>$key]),'class'=>'btn btn-success','id'=>'modalButton']);

            },
        ],
        /*'urlCreator' => function($action, $model, $key, $index) { 
                return Url::to([$action,'id'=>$key]);
        },*/
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