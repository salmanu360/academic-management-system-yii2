<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\widgets\Pjax;
use yii\widgets\ActiveForm;
use \app\widgets\Alert;
/* @var $this yii\web\View */
/* @var $searchModel app\models\StudentInfoSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Student Info';
$this->params['breadcrumbs'][] = $this->title;
?>
<?=Alert::widget()?>
    <div class="student-info-index fee-res-left cscroll "> 
     <?php Pjax::begin([
         'enablePushState' => false,
         'timeout' => false,
         'id'=>'pjax-feechallan-container'
    ]); ?>
    <?= GridView::widget([
        'id'=>'fee-challan-std-list-gridview',
        'dataProvider' => $dataProvider,
       /* 'filterModel' => $searchModel,*/
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
            'label'=>'Roll #',
            'value'=>function($data){
             if(!empty($data->roll_no)){
                return $data->roll_no;
            }else{
                return 'N/A';
            };
                
                }

            ],
            [
            'label'=>'Reg. No.',
            'value'=>function($data){
return strtoupper($data->user->username);
            }

            ],
             [
            'label'=>'Full Name',
            'value'=>function($data){
return strtoupper($data->user->first_name ." ".$data->user->middle_name  . " " . $data->user->last_name);

		  /* if($data->user){
             	   return strtoupper($data->user->first_name ." ".$data->user->middle_name  . " " . $data->user->last_name);
 }
else{
	return $data->stu_id;
}*/
            }

            ],
            [
                'label'=>'Father Name',
                'value' => function($data){
                    return Yii::$app->common->getParentName($data->stu_id);
                }
            ],
            //'fk_branch_id',
           /* 'cnic',
            [
                'attribute'=>'dob',
                'filter'=>'',
                'label'     =>'Date of birth',
                'value'     => function($data){
                    return date('d M,Y',strtotime($data->dob));
                }
            ],
            [
                'label'     =>'Email',
                'value'     => function($data){
                    return $data->user->email;
                }
            ],
            [
                'attribute'=>'registration_date',
                'filter'=>'',
                'value'     => function($data){
                    return date('d M,Y',strtotime($data->registration_date));
                }
            ],*/
            // 'contact_no',
            // 'emergency_contact_no',
            // 'gender_type',
            // 'guardian_type_id',
            // 'country_id',
            // 'province_id',
            // 'city_id',
            // 'session_id',
            // 'group_id',
            // 'shift_id',
            // 'class_id',
            // 'section_id',
            // 'location1',
            // 'location2',
            // 'withdrawl_no',
            // 'district_id',
            // 'religion_id',
            [
                'header'=>'Actions',
                'class' => 'yii\grid\ActionColumn',
                'template' => "{generateChallan} {submitChallan}",
                /*'buttons' => [
                    'generateChallan'=>function($url, $model, $key){
                        return Html::a('<span class="glyphicon glyphicon-send toltip" data-placement="bottom" width="20" id="gen-std-challan" data-url="'.Url::to(['fee/generate-challan-form']).'" data-stud_id="'.$key.'"  title="Generate Student Challan"></span>', 'javascript:void(0);');
                    },*/
                    'buttons' => [
                    'generateChallan'=>function($url, $model, $key){
                        return Html::a('<span class="glyphicon glyphicon-send toltip" data-placement="bottom" width="20" id="gen-std-challan" data-url="'.Url::to(['fee/generate-student-fee']).'" data-stud_id="'.$key.'" data-classid="'.$model->class_id.'" data-groupid="'.$model->group_id.'" data-sectionid="'.$model->section_id.'"  title="Generate Student Challan"></span>', 'javascript:void(0);');
                    },
                    /*'submitChallan'=>function($url, $model, $key){
                        return Html::a('<span class="glyphicon glyphicon-new-window toltip" data-placement="bottom" width="20" data-stud_id="'.$key.'"  title="Submit Student Challan"></span>', 'javascript:void(0);');
                    },*/

                ],
            ],

            //['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
    <?php Pjax::end() ?> 
     <?php
		$this->registerJS("$('.cscroll').mCustomScrollbar({theme:'minimal-dark'});", \yii\web\View::POS_LOAD); 
	 ?>
</div>
