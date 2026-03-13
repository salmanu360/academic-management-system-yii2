<?php
error_reporting(0);
use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\Modal;
use yii\widgets\Pjax;
use app\models\User;
use app\models\StudentInfo;
use app\models\EmployeeInfo;
use app\models\EmployeeAttendance;
use app\models\Noticeboard;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;
$this->title = 'Dashboard';
$this->registerCssFile(Yii::getAlias('@web').'/css/fullcalendar/fullcalendar.min.css',['depends' => [yii\web\JqueryAsset::className()]]);
$this->registerJsFile(Yii::getAlias('@web').'/js/fullcalendar/moment.min.js',['depends' => [yii\web\JqueryAsset::className()]]);
$this->registerJsFile(Yii::getAlias('@web').'/js/fullcalendar/fullcalendar.min.js',['depends' => [yii\web\JqueryAsset::className()]]);
$this->registerJsFile(Yii::getAlias('@web').'/js/highcharts.js',['depends' => [yii\web\JqueryAsset::className()]]);
$this->registerJsFile(Yii::getAlias('@web').'/js/data.js',['depends' => [yii\web\JqueryAsset::className()]]);
$this->registerJsFile(Yii::getAlias('@web').'/js/exporting.js',['depends' => [yii\web\JqueryAsset::className()]]);
$total_staff=EmployeeInfo::find()->where(['is_active'=>1,'fk_branch_id'=>yii::$app->common->getBranch()])->all();
$outsider=\app\models\StudentOutside::find()->where(['branch_id'=>yii::$app->common->getBranch()])->count();
$totalAlumni=\app\models\StudentLeaveInfo::find()->where(['branch_id'=>yii::$app->common->getBranch()])->count();
$totalAbsenFine=\app\models\FeeSubmission::find()->where(['branch_id'=>yii::$app->common->getBranch()])->sum('absent_fine');
    ?>
  <link rel="stylesheet" href="plugins/jvectormap/jquery-jvectormap-1.2.2.css">
  <style>
    .flash {
    animation-name: flash;
    animation-duration: 0.3s;
    animation-timing-function: linear;
    animation-iteration-count: infinite;
    animation-direction: alternate;
    animation-play-state: running;
}
@keyframes flash {
    from {color: red;}
    to {color: black;}
}
  </style>
<!--<div class="alert alert-warning">Important Note ! System will be down on 20 Feb 2022, because you have not pay dues from last 2 years.. pay before 20 and send slip to our official email.Thanks</div>-->
<?php if (Yii::$app->session->hasFlash('success')): ?>
           <div class="alert alert-success">
              <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
              <?= Yii::$app->session->getFlash('success') ?>
           </div>
            <?php endif;
             $total_students=StudentInfo::find()->where(['is_active'=>1,'fk_branch_id'=>yii::$app->common->getBranch()])->count(); ?>
     <section class="content">
      <div class="container">
       <!-- <div class="row">
          <div class="col-md-10 alert alert-danger">
              SMS will be not working till tomarrow 3pm due to maintance,sorry for inconvenience
          </div>
        </div>-->
      <div class="row">
      <div class="form-inline">
        <!-- data-toggle="modal" data-target="#sendsmsWhole" -->
        <a href="<?php echo Url::to(['site/send-all-parents']); ?>">
        <div class="col-xs-4 col-sm-4 col-md-2 btn btn-default" style="background: #228B22 ;color:white;font-size: 12px">
        <span style="margin-left:-10px">SMS to Parents</span><br />
        <span style="margin-left:-10px;font-size: 11px"><?= (count($total_students)>0)?'Total Parents:'.$total_students:'No Parents Found'; ?></span>
        </div>
        </a>
      <!-- end of one column -->
       <div data-toggle="modal" data-target="#sendsmsTeacher" class="col-xs-4 col-sm-4 col-md-2 btn btn-default" style="background: #B22222;color:white;font-size: 12px">
        <span style="margin-left:-10px">SMS to Staff</span><br />
        <span style="margin-left:-10px;font-size: 11px"><?= (count($total_staff)>0)?'Total Staff:'.count($total_staff):'No Staff Found'; ?></span>
        </div>
      <!-- end of second column -->
      <div data-toggle="modal" data-target="#sendsmsOutsider" class="col-xs-4 col-sm-4 col-md-2 btn btn-default" style="background: #DC143C;color:white;font-size: 12px">
        <span style="margin-left:-10px">SMS to Outsider</span><br />
        <span style="margin-left:-10px;font-size: 11px"><?= $outsider; ?></span>
        </div>
        <!-- end of four column -->
         <div data-toggle="modal" data-target="#sendsmsAlumni" class="col-xs-4 col-sm-4 col-md-2 btn btn-default" style="background: #A0522D;color:white;font-size: 12px">
        <span style="margin-left:-10px">SMS to ALUMNI</span><br />
        <span style="margin-left:-10px;font-size: 11px"><?= $totalAlumni; ?></span>
        </div>
        <!-- end of 5 column -->
        <?php if(Yii::$app->user->identity->fk_role_id == 1){?>
        <div class="col-xs-4 col-sm-4 col-md-2 btn btn-default" style="background: #4B0082;color:white;font-size: 12px">
        <span style="margin-left:-10px">Total Fee</span><br />
        <span style="margin-left:-10px;font-size: 11px">
          <?php 
            if(count($totalFeeCollected)>0){
              echo 'Rs. ';
              echo $totalFeeCollected + $fee_arrears_rcv; 
            }else{
              echo 'Rs: 0';
            }
            ?>
        </span>
        </div>
        <?php }?>
      <!-- end of 6 column -->
      
    </div> <!-- //form inline-->
    </div>
    <div class="row">
        <?php if(Yii::$app->user->identity->fk_role_id == 1){?>
       <div class="col-xs-4 col-sm-4 col-md-2 btn btn-default" style="background: #8B008B;color:white;font-size: 12px">
        <span style="margin-left:-10px">Total Arrears</span><br />
        <span style="margin-left:-10px;font-size: 11px">
          <?php 
              if(count($totalArrear)>0){
              echo 'Rs. '; 
              echo $totalArrear +$totalFeeSumissionArrears; 
              }else{
                echo 'Rs: 0';
              }
               ?>
        </span>
        </div>
        <?php }?>
<?php if(Yii::$app->user->identity->fk_role_id == 1){?>
        <div class="col-xs-4 col-sm-4 col-md-2 btn btn-default" style="background: #808000;color:white;font-size: 12px">
        <span style="margin-left:-10px">Total Expenses</span><br />
        <span style="margin-left:-10px;font-size: 11px">
          <?php 
            if(count($totalExpenses)>0){
              echo 'Rs: '.$totalExpenses; 
            }else{
              echo 'Rs: 0';
            }
            ?>
        </span>
        </div>
        <?php }?>
        <?php if(Yii::$app->user->identity->fk_role_id == 1){?>
        <a href="<?php echo Url::to(['expenses/today-expense']); ?>">
        <div class="col-xs-4 col-sm-4 col-md-2 btn btn-default" style="background: #556B2F;color:white;font-size: 12px">
        <span style="margin-left:-10px">Today Expense</span><br />
        <span style="margin-left:-10px;font-size: 11px">
          <?php 
            if(count($todayExpenses)>0){
              echo 'Rs: '.$todayExpenses; 
            }else{
              echo 'Rs: 0';
            }
            ?>
        </span>
        </div>
      </a>
     
        <div class="col-xs-4 col-sm-4 col-md-2 btn btn-default" style="background: #BA55D3;color:black;font-size: 12px">
        <span style="margin-left:-10px">Absent Fine</span><br />
        <span style="margin-left:-10px;font-size: 11px">
          <?php 
            if(count($totalAbsenFine)>0){
              echo 'Rs: '.$totalAbsenFine; 
            }else{
              echo 'Rs: 0';
            }
            ?>
        </span>
        </div>
         <?php }?>
        
        <?php if(Yii::$app->user->identity->fk_role_id == 1){?>
        <a href="<?php echo Url::to(['reports/today-rcv']); ?>">
        <div class="col-xs-4 col-sm-4 col-md-2 btn btn-default" style="background: #FF8C00;color:black;font-size: 12px">
        <span style="margin-left:-10px">Today Fee Rcv</span><br />
        <span style="margin-left:-10px;font-size: 11px">
          <?php echo ($todayFeeCollected)?$todayFeeCollected:0; ?>
        </span>
        </div>
      </a>
      <?php }?>

    </div>
    <div class="row">
      <div class="col-xs-4 col-sm-4 col-md-2 btn btn-default" style="background: #FF1493 ;color:white;font-size: 12px">
          <span class="view-button" data-url="<?php echo Url::to(['general/today-fine'])?>">
        <span style="margin-left:-10px">Today Fine</span><br />
        <span style="margin-left:-10px;font-size: 11px"><?= (count($todayFine)>0)?'Rs. '.$todayFine:'Rs. 0 '; ?></span>
      </span>
        </div>
      <div class="col-xs-4 col-sm-4 col-md-2 btn btn-default" data-toggle="modal" data-target="#onlineUser" style="background: #4682B4;color:black;font-size: 12px">
        <span style="margin-left:-10px">Online Users</span><br />
        <span style="margin-left:-10px;font-size: 11px">
          <?php if(!empty($onlineUsers)){echo $onlineUsers;} ?>
        </span>
        </div>
      <div class="col-xs-4 col-sm-4 col-md-2 btn btn-default flash" style="background: #DB7093;color:black;font-size: 12px">
        <span style="margin-left:-10px">SMS Expiry</span><br />
        <span style="margin-left:-10px;font-size: 11px">
          <?php if($smssettings->status == 0){echo 'Not Active';}else{echo date('d M Y',strtotime($smssettings->sms_expiry_date));}; ?>
        </span>
        </div>
        <?php if(Yii::$app->user->identity->fk_role_id == 1){?>
        <div class="col-xs-4 col-sm-4 col-md-2 btn btn-default flash" style="background: #D2B48C;color:white;font-size: 12px">
        <span style="margin-left:-10px">Monthly Charges</span><br />
        <span style="margin-left:-10px;font-size: 11px">
          <?php echo date('d M Y',strtotime($smssettings->date)); ?>
        </span>
        </div>
        <?php }?>
    </div>

    
  </div>
  <!-- sms to parents modal -->
  <div class="modal fade" id="sendsmsWhole" role="dialog">
    <div class="modal-dialog">
    <?php Pjax::begin(['id' => 'pjax-container']) ?>
    
      
      
      <?php Pjax::end() ?>  
    </div>
  </div>
  <!-- end of sms to parents modal start of staff model-->
  <div class="modal fade" id="sendsmsTeacher" role="dialog">
    <div class="modal-dialog">
    <?php Pjax::begin(['id' => 'pjax-container']) ?>
    <?php $form = ActiveForm::begin(['action'=>'site/send-all-teacher']); ?>
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title" style="color: black">Send Sms To Staff Members</h4>
        </div>
        <div class="modal-body">
          <p>
          <label for="">All</label>
          <input type="checkbox" value="1" id="allDepartment" name="allDepartment">
          
          <div id="departmentMessage">
          <?php
           echo Html::label('Departments');
              $departmentArray=\app\models\RefDepartment::find()->where(['fk_branch_id'=>yii::$app->common->getBranch()])->all();
                    echo Html::dropDownList('department', null,
                     ArrayHelper::map(\app\models\RefDepartment::find()->where(['fk_branch_id'=>yii::$app->common->getBranch()])->all(), 'department_type_id', 'Title'),['prompt'=>'Select Department','class'=>'form-control departmentDesignation','data-url'=>url::to(['employee/get-designation'])]);?>
                     <br />
                <div class="form-group">
                <label>Designation</label>
                <select name="designation[]" class="getDesignation form-control select2" multiple="multiple" data-placeholder="Select" style="width: 100%;"></select>
                </div>
                       <br />
              </div>
              <label for="message">Message</label>
              <textarea class="form-control" name="smsWholeDepartment" id="smsWholeSchool"></textarea>
          </p>
        </div>
        <div class="modal-footer">
        <button class="btn btn-success">Send</button>
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
      <?php ActiveForm::end(); ?>
      <?php Pjax::end() ?>  
    </div>
  </div>
  <!-- end of sms to staff modal start of outsider model-->
  <div class="modal fade" id="sendsmsOutsider" role="dialog">
    <div class="modal-dialog">
    <?php Pjax::begin(['id' => 'pjax-container']) ?>
    <?php $form = ActiveForm::begin(['action'=>'site/send-outsider']); ?>
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title" style="color: black">Send Sms To Outsider</h4>
        </div>
        <div class="modal-body">
          <p>
            <label for="">Student</label>
              <input type="radio" name="smsSend" value="student">
              <label for="">Parent</label> 
              <input type="radio" name="smsSend" value="parent" checked="checked">
              <textarea class="form-control" name="smsOutsider" id="smsOutsider"></textarea>
          </p>
        </div>
        <div class="modal-footer">
        <button class="btn btn-success">Send</button>
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
      <?php ActiveForm::end(); ?>
      <?php Pjax::end() ?>  
    </div>
  </div>
  <!-- end of sms to Outsider modal start of alumni model-->
    <div class="modal fade" id="sendsmsAlumni" role="dialog">
    <div class="modal-dialog">
    <?php Pjax::begin(['id' => 'pjax-container']) ?>
    <?php $form = ActiveForm::begin(['action'=>'site/send-alumni']); ?>
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title" style="color: black">Send Sms To Alumni</h4>
        </div>
        <div class="modal-body">
          <p>
              <textarea class="form-control" name="smsalumni" id="smsalumni"></textarea>
          </p>
        </div>
        <div class="modal-footer">
        <button class="btn btn-success">Send</button>
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
      <?php ActiveForm::end(); ?>
      <?php Pjax::end() ?>  
    </div>
  </div><br />
  <!-- end of sms to alumni modal start of alumni model-->
      <!-- end of android version -->
      <!-- start of calendar -->
    <!-- end of sms start of high chart for class -->
      <div class="row">
        <div class="col-md-11 col-sm-11">
          <div id="container" style="min-width: 310px; height: 400px; margin: 0 auto"></div> 
      <table id="datatable" style="display: none;">
    <thead>
        <tr>
            <th></th>
            <th>Total Students (<?=$total_students; ?>)</th>
        </tr>
    </thead>
    <tbody>
      <?php foreach ($class as $classvalue) {
        $getStudntcount=StudentInfo::find()->where(['class_id'=>$classvalue->class_id,'fk_branch_id'=>yii::$app->common->getBranch(),'is_active'=>1])->count();
        ?>
        <tr>
            <th><?=$classvalue->title; ?></th>
            <td><?php echo $getStudntcount; ?></td>
            <!-- <td>4</td> -->
        </tr>
      <?php } ?>
    </tbody>
</table>
        </div>
      </div><br>
      <?php if(Yii::$app->user->identity->fk_role_id == 1){?>
      <div class="row"> 
        <div class="col-md-11 col-sm-11"> 
            <div id="fee_monthly" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
        </div>
      </div>
      <?php }?>
      <br> 
      <!-- employee attendance graph -->
      <div class="row">
        <div class="col-md-11 col-sm-11">
          <div id="attendanceGraphEmployee" style="min-width: 310px; height: 400px; margin: 0 auto"></div> 
            <table id="attendanceGraphEmployeetable" style="display: none;">
                <thead>
                    <tr>
                        <th></th> 
                        <th>Absent</th>
                        <th>Present</th>
                        <th>Leave</th>
                        <th>Late</th>
                    </tr>
                </thead>
                <tbody>
                  <?php
                    foreach ($attendance_employee_dates as $key => $dateEmpl) {
                   ?>
                    <tr>
                        <th> <?=date('d-m-Y',strtotime($dateEmpl));?> </th>
                        <th> <?php  echo $empl_att_array[$dateEmpl]['absentCount']?></th>
                        <th> <?php  echo $empl_att_array[$dateEmpl]['presentCount']?></th>
                        <th> <?php  echo $empl_att_array[$dateEmpl]['leaveCount']?></th>
                        <th> <?php  echo $empl_att_array[$dateEmpl]['lateCount']?></th>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
      </div><br>
       <!-- start of attendance graph  -->
       <div class="row">
        <div class="col-md-11 col-sm-11">
          <div id="attendanceGraphStudents" style="min-width: 310px; height: 400px; margin: 0 auto"></div> 
            <table id="attendanceGraphStudentsdatatable" style="display: none;">
                <thead>
                    <tr>
                        <th></th> 
                        <th>Absent</th>
                        <th>Present</th>
                        <th>Leave</th>
                        <th>Late</th>
                    </tr>
                </thead>
                <tbody>
                  <?php
                    foreach ($attendance_month_dates as $key => $date) {
                   ?>
                    <tr>
                        <th> <?=date('d-m-Y',strtotime($date));?> </th>
                        <th> <?php  echo $studentAttendanceQueryThirtyDays[$date]['absentCount']?></th>
                        <th> <?php  echo $studentAttendanceQueryThirtyDays[$date]['presentCount']?></th>
                        <th> <?php  echo $studentAttendanceQueryThirtyDays[$date]['leaveCount']?></th>
                        <th> <?php  echo $studentAttendanceQueryThirtyDays[$date]['lateCount']?></th>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
      </div><br>
      <!-- end of attendance graph -->
      <!-- start of calendar end of high chart for class -->
      <div class="row">
        <div class="col-md-6">
          <div class="box box-success">
            <div id="getcalendarajax"></div>
          </div>
        </div>
        <div class="col-md-6">
          <div class="box box-success">
            <div id="getcalendartodolist"></div>
          </div>
        </div>
      </div>
      <!--  online user modal-->
      <!-- Modal -->
  <div class="modal fade" id="onlineUser" role="dialog">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header alert-danger">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Online Users</h4>
        </div>
        <div class="modal-body">
          <p>
            <table class="table">
            <thead>
              <tr class="info">
                <th>#</th>
                <th>Name</th>
                <th>Reg. No.</th>
                <th>Role</th>
                <th>Browser</th>
                <th>IP</th>
                <th>Platform</th>
                <th>Country</th>
              </tr>
            </thead>
            <tbody>
              <?php 
              if(count($onlineUsersName) > 0){
                $i=0;
              foreach ($onlineUsersName as $key => $getOnlineuser) { $i++;?>
              <tr>
                <th><?= $i; ?></th>
                <th><?= Yii::$app->common->getName($getOnlineuser->user_id) ?></th>
                <th><?= $getOnlineuser->user->username ?></th>
                <th><?php
                 if($getOnlineuser->user->fk_role_id == 1){
                  echo 'Super Admin(owner)';
                }else if($getOnlineuser->user->fk_role_id == 3){
                  echo 'Parent';
                }else if($getOnlineuser->user->fk_role_id == 4){
                  echo 'Teacher';
                }else if($getOnlineuser->user->fk_role_id == 5){
                  echo 'Accountant';
                }else if($getOnlineuser->user->fk_role_id == 6){
                  echo 'Librarian';
                }else if($getOnlineuser->user->fk_role_id == 7){
                  echo 'Administrator';
                } 
                ?></th>
                <th><?php echo $getOnlineuser->browser ?></th>
                <th><?php echo $getOnlineuser->ip_address ?></th>
                <th><?php echo $getOnlineuser->platform ?></th>
                <th><?php echo $getOnlineuser->country ?></th>
              </tr>
              <?php  } }?>
            </tbody>
             </table>
          </p>
        </div>
        <div class="modal-footer alert-warning">
          <button type="button" class="btn btn-success" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>
      <!--  online user modal ends-->
    </section>
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-hidden="true">
</div>
<div id='calendar'></div>
<div id='calendars'></div>
<input type="hidden" data-url="<?= Url::to(['site/calendar-event'])?>" id="caledarurl">
<input type="hidden" data-url="<?= Url::to(['site/calendar-todo'])?>" id="caledartodourl">
 <?php   
$this->registerJs("$(document).ready(function() {

var url=$('#caledarurl').data('url');
 
 $.ajax
    ({
        type: \"POST\",
        dataType:\"JSON\",
        url: url,
        //data: string,
        success: function(data)
        {
           $('#getcalendarajax').html(data.cal);
           //alert('success');
        }
    });

  var url=$('#caledartodourl').data('url');
 $.ajax
    ({
        type: \"POST\",
        dataType:\"JSON\",
        url: url,
        success: function(data)
        {
           $('#getcalendartodolist').html(data.calendartodolist);
        }
    });
});

 Highcharts.chart('container', {
    data: {
        table: 'datatable'
    },
    chart: {
        type: 'column'
    },
    title: {
        text: 'Class Student Information'
    },
    yAxis: {
        allowDecimals: false,
        title: {
            text: 'Number of students'
        }
    },
    tooltip: {
        formatter: function () {
            return '<b>' + this.point.y + '</b> ' +
                ' ';
        }
    }
});

// student attendance graph
Highcharts.chart('attendanceGraphStudents', {
    colors: ['#AA4643','#4572A7' , '#89A54E','#00c0ef'],

    data: {
        table: 'attendanceGraphStudentsdatatable'
    },
    chart: {
        type: 'column'
    },
    title: {
        text: 'Student Attendance last 30 days'
    },
    yAxis: {
        allowDecimals: false,
        title: {
            text: 'Number of Students'
        }
            },
     xAxis: {
        type: 'category',
        labels: {
            rotation: -45,
            style: {
                fontSize: '13px',
                fontFamily: 'Verdana, sans-serif'
            }
        },
        categories:  ".json_encode($attendance_month_dates).",
    
    },
            /*formatter: function () {
            return '<b>' + this.series.name + '</b>:<b>' + this.point.y + '</b> ' +
                ' ';*/
          formatter: function () {
            var s = '<b>' + this.x + '</b>';

            $.each(this.points, function () {
                s += '<br/>' + this.series.name + ': ' +
                    this.y + ' students';
            });

            return s;
        },
        shared: true 
       

});
Highcharts.chart('attendanceGraphEmployee', {
    colors: ['#AA4643','#4572A7' , '#89A54E','#00c0ef'],

    data: {
        table: 'attendanceGraphEmployeetable'
    },
    chart: {
        type: 'column'
    },
    title: {
        text: 'Employee Attendance last 30 days'
    },
    yAxis: {
        allowDecimals: false,
        title: {
            text: 'Number of Employee'
        }
            },
     xAxis: {
        type: 'category',
        labels: {
            rotation: -45,
            style: {
                fontSize: '13px',
                fontFamily: 'Verdana, sans-serif'
            }
        },
        categories:  ".json_encode($attendance_month_dates).",
    
    },
            /*formatter: function () {
            return '<b>' + this.series.name + '</b>:<b>' + this.point.y + '</b> ' +
                ' ';*/
          formatter: function () {
            var s = '<b>' + this.x + '</b>';

            $.each(this.points, function () {
                s += '<br/>' + this.series.name + ': ' +
                    this.y + ' students';
            });

            return s;
        },
        shared: true 
       

});


Highcharts.chart('fee_monthly', {
  colors: ['#AA4643'],
    chart: {
        type: 'column'
    },
    title: {
        text: 'Monthly Fee Receive (Current Year)'
    },
    subtitle: {
        text: ''
    },
    xAxis: {
        type: 'category',
        labels: {
            rotation: -45,
            style: {
                fontSize: '13px',
                fontFamily: 'Verdana, sans-serif'
            }
        }
    },
    yAxis: {
        min: 0,
        title: {
            text: 'Rupees(Rs)'
        }
    },
    legend: {
        enabled: false
    },
    tooltip: {
        pointFormat: 'Fee Collection: <b>Rs.{point.y:.1f}</b>'
    },
    series: [{
        name: 'Population',
        data:  ".$monthly_fee_receive.",
        dataLabels: {
            enabled: true,
            rotation: -90,
            color: '#FFFFFF',
            align: 'right',
            format: '{point.y:.1f}', // one decimal
            y: 10, // 10 pixels down from the top
            style: {
                fontSize: '13px',
                fontFamily: 'Verdana, sans-serif'
            }
        }
    }]
});");
?>

