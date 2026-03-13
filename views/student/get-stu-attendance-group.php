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
        <!-- /.box-header -->
        <div class="box-body">
     <?php 
    $form = ActiveForm::begin([
    'action'=>'save-attendance',
    'id' => 'myform',
    'class'=>'mform',
   // 'enableAjaxValidation' => true,
    ]);


  $matchingTodayAttendance=\app\models\StudentAttendance::find()->where([
    'fk_branch_id'  =>Yii::$app->common->getBranch(),
    'class_id'   => $class_id,
    'group_id'   => ($group_id)?$group_id:null,
    'date(date)'=>date('Y-m-d')
    ])->all();
  if(count($matchingTodayAttendance)>0){
    echo "<div class='row col-md-10'><div class='Alert alert-warning blink_me'><strong><center> Today Attendance has already been taken !</center></strong></div> </div>";
    echo '<br>';
  }

     ?>


      <div class="row">
       <div class="col-md-4">
         <?php 
         echo  $form->field($model, 'date')->widget(DatePicker::classname(), [
                         'options' => ['value' => date('Y-m-d')],
                         'pluginOptions' => [
                             'autoclose'=>true,
                             'format' => 'yyyy-mm-dd',
                             'todayHighlight' => true,
                             'endDate' => '+0d',
                             //'startDate' => '-2y',
                         ]
                     ])->label('Select Date'); ?>

       </div>
     </div>
       <table class="table table-bordered">
           <tbody><tr class="info">
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
                ])->All();
          $count=0;
          foreach ($emp as $q) {
            $count++;
           ?>
         
           <tr ng-repeat="teacher in teachers | object2Array | orderBy:'studentRollId'" class="ng-scope">
               <td class="ng-binding">
               <?php echo $count; ?> :
               <?php echo Yii::$app->common->getName($q->user_id);?>
                 <?= $form->field($model, 'fk_stu_id[]')->hiddenInput(['value'=>$q->stu_id])->label(false); ?>
               </td>
               <td>
                 <div>
                 <label id="example">
                  <?php
                   $attendance_array= ['present' => 'present','absent' => 'Absent', 'leave' => 'Leave','late' => 'Late','Latewithexcuse' => 'Late with excuse'];
                  echo Html::activeDropDownList($model, 'leave_type[]',$attendance_array,['class'=>'form-control categoryVisits']);
                  ?>
                     </label>
                     <label for=""></label> 
                     <label for=""></label> 
                     <label for=""></label> 
                     <label for="">
                      <!--  <textarea name="" id="" cols="10" rows="2"></textarea> -->
                     </label>                    
                   </div>
                 </div>
               </td>
               <td>
               
                       <?= $form->field($model, 'remarks[]')->textarea(['rows'=>1])->label(false); ?>
                       <?= $form->field($model, 'class_id[]')->hiddenInput(['value'=>$class_id])->label(false); ?>
                       <?= $form->field($model, 'group_id[]')->hiddenInput(['value'=>$group_id])->label(false); ?>
                 <!-- <textarea id="employeeattendance-remarks" class="form-control" name="EmployeeAttendance[remarks]" aria-invalid="false"></textarea> -->
               </td>
           </tr><!-- end ngRepeat: teacher in teachers | object2Array | orderBy:'studentRollId' -->
           <tr>
             
           </tr>



           <?php } 
           ?>
           

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