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
        'label'=>'Registeration #',
        'value'=>function($data){
            $student_id = \app\models\StudentInfo::find()->select(['user_id'])->where(['stu_id'=>$data->stu_id])->one();
            $user_id = \app\models\User::find()->select(['username'])->where(['id'=>$student_id->user_id])->one();
            return (!empty($data->stu_id))?$user_id->username:'N/A';
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
        'label'=>'stu_id',
        'value'=>function($data){
            $user_id = \app\models\StudentInfo::find()->select(['user_id'])->where(['stu_id'=>$data->stu_id])->one();
            return (!empty($data->stu_id))?ucfirst(\Yii::$app->common->getName($user_id->user_id)):'N/A';
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
        'label'=>'Section',
        'value'=>function($data){
            $user_id = \app\models\StudentInfo::find()->select(['section_id'])->where(['stu_id'=>$data->stu_id])->one();
            return (!empty($data->stu_id))?$user_id->section->title:'N/A';
        }
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'label'=>'Fee Head',
       'value'=>function($data){
            $fee_heads = \app\models\FeeHead::find()->select(['title'])->where(['id'=>$data->fee_head_id])->one();
            return (!empty($fee_heads->title))?ucfirst($fee_heads->title):'N/A';
        }
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'label'=>'arears',
        'value'=>function($data){
            return $data->arears;
        }
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'label'=>'from_date',
        'value'=>function($data){
            return $data->from_date;
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
                    return \Yii\helpers\Html::a('<span class="glyphicon glyphicon-pencil"></span>', ['fee-arears/update','id'=>$key],['role'=>'modal-remote','data-pjax'=>"0",'data-toggle'=>'tooltip']);
            },
        ],
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