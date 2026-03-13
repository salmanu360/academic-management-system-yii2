<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\bootstrap\Modal;
use yii\widgets\ActiveForm;
use app\widgets\Alert;
use yii\widgets\Pjax;
use yii\widgets\LinkPager;
use app\models\StudentInfo;
use app\models\StudentParentsInfo;
$getClass=\app\models\RefClass::find()->where(['class_id'=>$class_val])->one();
$getGroup=\app\models\RefGroup::find()->where(['group_id'=>$groupval])->one();
if(count($getGroup) > 0){
    $groupname=$getGroup->title;
}else{
$groupname='';
}
$getSection=\app\models\RefSection::find()->where(['section_id'=>$sectionval])->one();
$branch_details = Yii::$app->common->getBranchDetail();
?>
<style type="text/css">
  *{ margin:0; padding:0;}
  th, tr, td  {
    border:0.8px solid black;
    padding:8px;
    font-size:2em;
  }

  tr:nth-child(even){background-color: #f2f2f2}
  .first{ min-width: 40px; width: 40px; }
</style>
<h2 style="text-align: center;padding-top: -50px; font-weight:700; color:#000000; text-transform:capitalize;">
    <?=Yii::$app->common->getBranchDetail()->address?>
  </h2>
<h3 style='text-align:center;color: black;padding-top: -10px'>Class Report of <?= $getClass->title.' '. $groupname .' '.$getSection->title ?></h3>
<?= GridView::widget([
    'dataProvider' => $dataProvider,
    'layout' => '{items}{pager}',
    'columns' => [
    ['class' => 'yii\grid\SerialColumn'],
    [
                            //'attribute'=>'stu_id',
    'filter'=>null,
    'label'     =>'Registration No.',
    'value'     => function($data){
        return $data->user->username;
    }
    ],
    [
    'label'=>'Roll No.',
    'format' => 'raw',
    'contentOptions'=>['style'=>'width: 200px;'],
    'value'     => function($data){
        if(!empty($data->roll_no)){
            return $data->roll_no;
        }else{
            return 'N/A';
        }
    }

],
    [
    'label'=>'Name',
    'format'=>'raw',
    'contentOptions'=>['style'=>'width: 200px;'],
    'value'=>function($data){
      return Yii::$app->common->getName($data->user_id);
  }

  ],
  [
  'label'=>'Father Name',
  'format' => 'raw',
    'contentOptions'=>['style'=>'width: 200px;'],
  'value' =>function($data){
    if(Yii::$app->common->getParentName($data->stu_id)){
        return Yii::$app->common->getParentName($data->stu_id);
    }else{
        return 'N/A';
    }
}
],

[
'label'=>'Contact',
'format' => 'raw',
    'contentOptions'=>['style'=>'width: 200px;'],
'value' =>function($data,$key){
   $p_contact = StudentParentsInfo::find()->where(['stu_id'=>$key])->one();

   if($p_contact->contact_no){
    return $p_contact->contact_no;
}else{
    return 'N/A';
}
}
],
[
  'label'=>'CNIC',
  'value' =>function($data,$key){
   $p_contact = StudentParentsInfo::find()->where(['stu_id'=>$key])->one();

      if($p_contact->cnic){
          return $p_contact->cnic;
      }else{
          return 'N/A';
      }
  }
],
[
'label'=>'Address',
'value'=>function($data){
  return $data->location1;
}
],
[
'filter'=>'',
'format' => 'raw',
'contentOptions'=>['style'=>'width: 200px;'],
'label'     =>'DOB',
'value'     => function($data){
    return date('d M,Y',strtotime($data->dob));
}
],
[
'label'=>'Registration Date',
'filter'=>'',
'format' => 'raw',
'contentOptions'=>['style'=>'width: 200px;'],
'value'     => function($data){
    return date('d M,Y',strtotime($data->registration_date));
}
],
[
    'label'=>'Session',
    'value'     => function($data){
        return $data->session->title;
    }
],
[
    'label'=>'Profession',
    'value'     => function($data){

        $profession = \app\models\Profession::find()->where(['id'=>$data->parentsInfo->profession])->one();
        if($profession){
        return $profession->title;
      }else{
        return 'N/A';
      }
    }
],
],
]); ?>