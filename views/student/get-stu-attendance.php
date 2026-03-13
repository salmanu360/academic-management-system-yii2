<?php
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use app\models\StudentInfo;
use kartik\date\DatePicker;
?>
<style>
  .blink_me {
  animation: blinker 1s linear infinite;
}
@keyframes blinker {  
  100% { opacity: 0; }
}
</style>
<div class="box box-default">
        <div class="box-body">
     <?php 
    $form = ActiveForm::begin([
    'action'=>'save-attendance',
    'id' => 'myform',
    'class'=>'mform',
    ]);
  $matchingTodayAttendance=\app\models\StudentAttendance::find()->where([
    'fk_branch_id'  =>Yii::$app->common->getBranch(),
    'class_id'   => $class_id,
    'group_id'   => ($group_id)?$group_id:null,
    'section_id' => $section_id,
    'date(date)'=>date('Y-m-d')
    ])->all();
  if(count($matchingTodayAttendance)>0){
    echo "<div class='row col-md-10'><div class='Alert alert-warning blink_me'><strong><center> Today Attendance has already been taken !</center></strong></div> </div>";
    echo '<br>';
  } ?>
      <div class="row">
       <div class="col-md-4">
        <?= $form->field($model, 'date')->hiddenInput(['value'=>date('Y-m-d')])->label(false); ?>

       </div>
     </div>
       <table class="table table-bordered">
           <tbody><tr class="info">
               <th class="ng-binding">Sr.</th>
               <th class="ng-binding">Roll #</th>
               <th class="ng-binding">Student</th>
               <th class="ng-binding">Attendance</th>
               <th class="ng-binding">Remarks</th>
           </tr>
           <?php 
          $emp=StudentInfo::find()->where([
                        'fk_branch_id'  =>Yii::$app->common->getBranch(),
                        'is_active'=>1,
                        'class_id'   => $class_id,
                        'group_id'   => ($group_id)?$group_id:null,
                        'section_id' => $section_id,
                ])->orderBy(['roll_no'=>SORT_ASC])->All();
          $count=0;
          foreach ($emp as $q) { $count++;?>
           <tr ng-repeat="teacher in teachers | object2Array | orderBy:'studentRollId'" class="ng-scope">
               <td><?php echo $count; ?></td>
               <td><?php echo ($q->roll_no)?$q->roll_no:'N/A'; ?></td>
               <td class="ng-binding">
               <?php echo Yii::$app->common->getName($q->user_id);?>
                 <?= $form->field($model, 'fk_stu_id[]')->hiddenInput(['value'=>$q->stu_id])->label(false); ?>
               </td>
               <td>
                 <div>
					     	 <label id="example">
                  <?php
                   $attendance_array= ['present' => 'Present','absent' => 'Absent', 'leave' => 'Leave','late' => 'Late','shortleave'=>'Short Leave'];
                  echo Html::activeDropDownList($model, 'leave_type[]',$attendance_array,['class'=>'form-control categoryVisits']);
                  ?>
					           </label>
                     <label for=""></label> 
                     <label for=""></label> 
                     <label for=""></label> 
                     <label for="">
                     </label>                    
                   </div>
                 </div>
               </td>
               <td>
                       <?= $form->field($model, 'remarks[]')->textarea(['rows'=>1])->label(false); ?>
                       <?= $form->field($model, 'class_id[]')->hiddenInput(['value'=>$class_id])->label(false); ?>
                       <?= $form->field($model, 'group_id[]')->hiddenInput(['value'=>$group_id])->label(false); ?>
                       <?= $form->field($model, 'section_id[]')->hiddenInput(['value'=>$section_id])->label(false); ?>
               </td>
           </tr>
           <tr>
           </tr>
            <?php } ?>
         </tbody>
       </table>
       <br>
       <div class="row">
         <div class="col-sm-6">
       <div class="form-group">
       <?php
       if(count($matchingTodayAttendance)>0){}else{
        ?>
           <button type="submit" class="btn btn-success ng-binding">Save attendance</button>
           <?php } ?>
           <a href="" class="btn btn-danger">Cancel</a>
         </div>
       </div>
       </div>
        <?php ActiveForm::end(); ?>
        </div>
        </div>