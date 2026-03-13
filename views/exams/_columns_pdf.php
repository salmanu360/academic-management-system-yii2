<?php
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\bootstrap\Html;

return [
    [
        'class' => 'kartik\grid\SerialColumn',
        'width' => '30px',
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'label'=>'Subject',
        'filter'=>'',
        'value'=>function($data){
            $subject = '';
            if($data->fk_subject_id){
                $subject = $data->fkSubject->title;
            }
            if($data->fk_subject_division_id !=''){
                $subject .= ' - '. $data->getFkSubjectDivision()->one()->title;
            }
            return $subject;
        }
    ],

    [
         'class'=>'\kartik\grid\DataColumn',
         'label'=>'Total Marks',
         'filter'=>'',
         'value'=>function($data){
            if($data->total_marks){
                return $data->total_marks;
            }else{
                return "N/A";
            }
        }
     ],
     [
        'class'=>'\kartik\grid\DataColumn',
        'label'=>'Passing Marks',
         'filter'=>'',
         'value'=>function($data){
            if($data->passing_marks){
                return $data->passing_marks;
            }else{
                return "N/A";
            }
        }
     ],
     
    [
        'class'=>'\kartik\grid\DataColumn',
        'label'=>'Start Date',
        'filter'=>'',
        'value'=>function($data){
            if($data->start_date){
                return date('D, d-m-Y H:i:s',strtotime($data->start_date));
            }else{
                return "N/A";
            }
        }
    ],
    
];   