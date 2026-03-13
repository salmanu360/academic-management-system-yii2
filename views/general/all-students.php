<?php

use yii\helpers\Html; 
use yii\helpers\Url; 
// use yii\grid\GridView; 
use kartik\grid\GridView;
/* @var $this yii\web\View */ 
/* @var $dataProvider yii\data\ActiveDataProvider */ 

$this->title = 'Student Info'; 
//$this->params['breadcrumbs'][] = $this->title; 
?> 
<div class="student-info-index"> 

    <?= GridView::widget([ 
        'dataProvider' => $dataProvider, 
       // 'filterModel' => $searchModel,
        'responsive'=>true,
    	'hover'=>true,
    	'resizeStorageKey'=>Yii::$app->user->id . '-' . date("m"),
    	//'floatHeader'=>true,
       // 'floatHeaderOptions'=>['scrollingTop'=>'25'],
       
    'panel' => [
        'heading'=>'<h3 class="panel-title"><i class="glyphicon glyphicon-user"></i> All Students against registeration No. <a class="btn btn-warning btn-sm" href="'.Url::to(["all"]).'" style="color:white">Back</a></h3>',
        'type'=>'info',
        //'before'=>Html::a('<i class="glyphicon glyphicon-plus"></i> Create Country', ['create'], ['class' => 'btn btn-success']),
        //'before'=>Html::a('<i class="fas fa-redo"></i> Reset Grid', ['index'], ['class' => 'btn btn-info']),
        //'footer'=>true
    ],
    'replaceTags' => [
        '{custom}' => function($widget) {
            // you could call other widgets/custom code here
            if ($widget->panel === false) {
                return '';
            } else {
                return '';
            }
        }
    ],

        'columns' => [
            ['class' => 'yii\grid\SerialColumn'], 

           
             'username',
             [
             'label'=>'Name',
             'value'=>function($data){
                return Yii::$app->common->getName($data->id);
                

             }
            ],
            [
             'label'=>'Class',
             'value'=>function($data){
               $studentInfo=\app\models\StudentInfo::find()->where(['user_id'=>$data->id])->one();
               if(!empty($studentInfo->class_id)){
                $class_id= $studentInfo->class_id;
                $group_id= $studentInfo->group_id;
                $section_id= $studentInfo->section_id;
                return Yii::$app->common->getCGSName($class_id,$group_id,$section_id);
               }

             }
            ],
            [
             'label'=>'Father Name',
             'value'=>function($data){
               $studentInfo=\app\models\StudentInfo::find()->where(['user_id'=>$data->id])->one();
               if(!empty($studentInfo->stu_id)){
                $stuid= $studentInfo->stu_id;
                return Yii::$app->common->getParentName($stuid);
               }

             }
            ],
            /*[
             'label'=>'Father Name',
             'value'=>function($data){
             	$studentInfo=\app\models\StudentInfo::find()->where(['user_id'=>$data->id])->one();
             	return Yii::$app->common->getStudentByUserId($data->id);
             }
            ],*/
            // 'email:email',
            // 'auth_key',
            // 'password_hash',
            // 'password_reset_token',
            // 'pass',
            // 'avatar',
            // 'status',
            // 'fk_role_id',
            // 'last_ip_address',
            // 'last_login',
            // 'created_at',
            // 'updated_at',
            // 'name_in_urdu',
            // 'Image',

           // ['class' => 'yii\grid\ActionColumn'], 
        ], 
    ]); ?> 
</div> 