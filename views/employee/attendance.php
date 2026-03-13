<?php
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use kartik\date\DatePicker;
?>
<?php if(count($getEmplpoyeedate)>0){?>
          <div class="alert alert-success">
              <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
              Today Attendance has been taken..!
          </div>
        <?php }?>
<div class="box box-default">
        <div class="box-header with-border">
          <h3 class="box-title">Employee Attendance (<?= Date('d M,Y')?>)</h3>

          <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
          </div>
        </div>
        <!-- /.box-header -->
        <div class="box-body">
     <?php 
    $form = ActiveForm::begin([
    'action'=>'save-attendance',
    'id' => 'myform',
    'class'=>'mform',
   // 'enableAjaxValidation' => true,
    ]);
     ?>
     <div class="row">
       <div class="col-md-4">
        <!--  <?php 
         /*echo  $form->field($attendanceModel, 'date')->widget(DatePicker::classname(), [
                         'options' => ['value' => date('Y-m-d')],
                         'pluginOptions' => [
                             'autoclose'=>true,
                             'format' => 'yyyy-mm-dd',
                             'todayHighlight' => true,
                             'endDate' => '+0d',
                             //'startDate' => '-2y',
                         ]
                     ])->label('Select Date');*/ ?> -->

       </div>
       <div class="col-md-1">
       <div style="height: 24px"></div>
         <!-- <a href="javascript:Void(0)" id="searchEmployeeAttendance" style="background: #727272;border-color: #525252" class="btn btn-primary"><i class="fa fa-search"></i> Search</a> -->
       </div>
     </div>
     <!-- <div class="employeeAttendanceTable" style="display: none"> -->
     <div class="employeeAttendanceTable">
       <table class="table table-bordered">
           <tbody><tr>
               <th class="ng-binding">Teacher</th>
               <th class="ng-binding">Attendance</th>
               <th class="ng-binding">Remarks</th>
           </tr>
           <?php 
           $a=0;
           foreach ($getEmplpoyee as $employee) {?>
           <tr ng-repeat="teacher in teachers | object2Array | orderBy:'studentRollId'" class="ng-scope">
               <td class="ng-binding">
               <?= Yii::$app->common->getName($employee->user_id); ?>
                 <?= $form->field($attendanceModel, 'emp_id[]')->hiddenInput(['value'=>$employee->emp_id])->label(false); ?>
               </td>
               <td>
                 <div>
					     	 <label id="example">
                  <?php
                   $attendance_array= ['present' => 'Present','absent' => 'Absent', 'leave' => 'Leave','shortleave'=>'Short Leave','late' => 'Late'];
                  echo Html::activeDropDownList($attendanceModel, 'leave_type[]',$attendance_array,['class'=>'form-control categoryVisits','id'=>'employeeAttendancedropdown','data-id'=>$employee->emp_id]);
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
               <td><?= $form->field($attendanceModel, 'remarks[]')->textarea(['rows'=>1])->label(false); ?></td>
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
        <?php if(count($getEmplpoyeedate)>0){}else{ ?>
           <button type="submit" class="btn btn-success ng-binding">Save attendance</button>
           <?php } ?>
           <a href="" class="btn btn-danger">Cancel</a>
         </div>
       </div>
       </div>
       </div>
        <?php ActiveForm::end(); ?>
        </div>
        </div>