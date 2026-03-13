<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
$classArray = ArrayHelper::map(\app\models\RefClass::find()->where(['fk_branch_id'=>Yii::$app->common->getBranch(),'status'=>'active'])->all(),'class_id','title');
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
            $student_id = \app\models\StudentInfo::find()->select(['user_id'])->where(['stu_id'=>$data->stu_id])->one();
            $user_id = \app\models\User::find()->select(['username'])->where(['id'=>$student_id->user_id])->one();
            return (!empty($data->stu_id))?$user_id->username:'N/A';
        }
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        //'attribute'=>'stu_id',
        'label'=>'Student',
        'value'=>function($data){
            $user_id = \app\models\StudentInfo::find()->select(['user_id'])->where(['stu_id'=>$data->stu_id])->one();
            return (!empty($data->stu_id))?ucfirst(\Yii::$app->common->getName($user_id->user_id)):'N/A';
        }
    ],
    [
        'label'=>'Parent',
        'value'=>function($data){
            return (!empty($data->stu_id))?ucfirst(\Yii::$app->common->getParentName($data->stu_id)):'N/A';
        }
    ],
    /*[
           'attribute' => 'status',
           'label' => 'Status',
           'filter' => [ 'Present' => 'Present', 'Absent' => 'Absent', 'Leave' => 'Leave',]
        ],*/

    [   
        'class'=>'\kartik\grid\DataColumn',
       // 'attribute' => 'fk_class_id',
        'label'=>'Class',
        'filter'=>ArrayHelper::map(\app\models\RefClass::find()->asArray()->all(), 'class_id', 'title'),
        'value'=>function($data){
            $user_id = \app\models\StudentInfo::find()->select(['class_id'])->where(['stu_id'=>$data->stu_id])->one();
            return (!empty($data->stu_id))?$user_id->class->title:'N/A';
        }
    ],
    [
        'label'=>'Section',
        'value'=>function($data){
            $user_id = \app\models\StudentInfo::find()->select(['section_id'])->where(['stu_id'=>$data->stu_id])->one();
            return (!empty($data->stu_id))?$user_id->section->title:'N/A';
        }
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        //'attribute'=>'fee_head_id',
        'label'=>'Fee Head',
        'value'=>function($data){
            $fee_heads = \app\models\FeeHead::find()->select(['title'])->where(['id'=>$data->fee_head_id])->one();
            return (!empty($fee_heads->title))?ucfirst($fee_heads->title):'N/A';
        }
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'label'=>'Discount',
        'value'=>function($data){
            return $data->discount;
        }
    ],
    [
        'label'=>'Discount Type',
        'value'=>function($data){
          $feeDiscountTypes = \app\models\FeeDiscountTypes::find()->select(['title'])->where(['id'=>$data->fk_fee_discounts_type_id])->one();  
          if(count($feeDiscountTypes)>0){

           return $feeDiscountTypes->title;
          } else{
          return 'N/A';  
          }
        }
    ],
   /* [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'status',
    ],*/
    [
        'label'=>'Date',
        'value'=>function($data){
            return date('d M Y',strtotime($data->created_at));
        }
    ],
    /*[
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'branch_id',
    ],*/
    [
        'class' => 'kartik\grid\ActionColumn',
        'dropdown' => false,
        'vAlign'=>'middle',
        'template'=> '{update}',
        'buttons' => [
            'update' => function ($url, $model, $key)
            {
                $feeHead= \app\models\FeeHead::find()->where(['id'=>$model->fee_head_id,'one_time_payment'=>1])->count();
                if($feeHead >0){
                    return \Yii\helpers\Html::a('<span class="glyphicon glyphicon-pencil"></span>', ['fee-plan/update','id'=>$key],['role'=>'modal-remote','data-pjax'=>"0",'data-toggle'=>'tooltip']);
                }else{
                    return '';
                }

            },
        ],
        'urlCreator' => function($action, $model, $key, $index) {
            $feeHead= \app\models\FeeHead::find()->where(['id'=>$model->fee_head_id,'one_time_payment'=>1])->count();
            if($feeHead >0){
                return Url::to([$action,'id'=>$key]);
            }

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