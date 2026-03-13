  <?php
  use yii\helpers\Html;
  use yii\grid\GridView;
  use yii\helpers\Url;
  use app\models\StudentInfo;
  use app\models\RefGroup;
  use app\models\RefClass;
  use yii\helpers\ArrayHelper; 
  use kartik\date\DatePicker;
  $this->title = 'Student Attendance';
  $classArray = ArrayHelper::map(\app\models\RefClass::find()->where(['fk_branch_id'=>Yii::$app->common->getBranch(),'status'=>'active'])->all(),'class_id','title');
  ?>
  <?php if (Yii::$app->session->hasFlash('success')): ?>
    <div class="alert alert-success">
      <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
      <?= Yii::$app->session->getFlash('success') ?>
    </div>
  <?php endif; ?>
  <div class="box box-warning">
    <div class="box-header with-border">
      <h3 class="box-title"><i class="fa fa-search"></i> Student Attendance List (<?= Date('d M,Y')?>)</h3>
      <?= Html::a('Take Attendance', ['attendance'], ['class' => 'btn btn-success pull-right']) ?>
    </div>
    <!-- <?php //if(count($todayAttendance) > 0){ ?> -->
    <div class="box-body">

      <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
        ['class' => 'yii\grid\SerialColumn'],
        [
        'label'=>'Full Name',
        'value'=>function($data){
         $StudentInfo=StudentInfo::find()->where(['stu_id'=>$data->fk_stu_id])->one();
         return Yii::$app->common->getName($StudentInfo->user_id);
       }

       ],
       [
       'label'=>'Parent Name',
       'contentOptions' => ['class' => 'parntClass'],
       'headerOptions' => ['class' => 'parntClassHeader'],
       'value'=>function($data){
         return Yii::$app->common->getParentName($data->fk_stu_id);
       }

       ],
       [
       'attribute'=>'class_id',
       'label'=>'Class',
       'contentOptions' => ['class' => 'parntClass'],
       'headerOptions' => ['class' => 'parntClassHeader'],
       'filter'=>Html::activeDropDownList ($searchModel,'class_id',$classArray,['prompt' => 'Select '.Yii::t('app','Class'),'class' => 'form-control']),
       'value' => function($model,$key){
        return ucfirst($model->fkClass->title);
      },
      ],
      [
      'label'=>'Group',
      'contentOptions' => ['class' => 'parntClass'],
      'headerOptions' => ['class' => 'parntClassHeader'],
      'value'=>function($data){
       $StudentInfo=StudentInfo::find()->where(['stu_id'=>$data->fk_stu_id])->one();
       if($StudentInfo->group_id == NULL){
        return "N/A";

      }else{
       $getGroup=RefGroup::find()->where(['group_id'=>$StudentInfo->group_id])->one();
       return $getGroup->title;
     }
   }
   ],
    
    [
       'label'=>'Date',
       'value'=>function($data){
         return $data->date;
       }

       ],
   'time',
   [
   'attribute'=>'leave_type',
   'filter'=>['present' => 'Present','absent' => 'Absent','leave' => 'Leave','late' => 'Late','shortleave' => 'Short Leave',],
   'value' => function($model,$key){
    return ucfirst($model->leave_type);
  },
  ],
  'remarks',
  [
  'header'=>'Actions',
  'class' => 'yii\grid\ActionColumn',
  'contentOptions' =>['style' => 'width:100px'],
  'template' => "{update}",
  'buttons' => [
  'update' => function ($url, $model, $key)
  {
    return Html::a('<span class="glyphicon glyphicon-pencil toltip" data-placement="bottom" width="20"  title="Edit Attendance"></span>',Url::to(['student/update-attendance','id'=>$key],['class'=>'btn btn-primary btn-xs']));
  }, 
  ],
  ],
  ],
  ]); ?>
</div>
<!-- <?php //}else{?>
<div class="alert alert-danger"> No Attendace has been taken today..!</div>
<?php //} ?> -->
</div>

