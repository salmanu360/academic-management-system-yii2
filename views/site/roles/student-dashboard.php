<?php
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
$this->title = 'Dashboard';
$this->registerCssFile(Yii::getAlias('@web').'/css/fullcalendar/fullcalendar.min.css',['depends' => [yii\web\JqueryAsset::className()]]);
$this->registerJsFile(Yii::getAlias('@web').'/js/fullcalendar/moment.min.js',['depends' => [yii\web\JqueryAsset::className()]]);
$this->registerJsFile(Yii::getAlias('@web').'/js/fullcalendar/fullcalendar.min.js',['depends' => [yii\web\JqueryAsset::className()]]);
$this->registerJsFile(Yii::getAlias('@web').'/js/highcharts.js',['depends' => [yii\web\JqueryAsset::className()]]);
$this->registerJsFile(Yii::getAlias('@web').'/js/data.js',['depends' => [yii\web\JqueryAsset::className()]]);
$this->registerJsFile(Yii::getAlias('@web').'/js/exporting.js',['depends' => [yii\web\JqueryAsset::className()]]);
$dahboardSettings = Yii::$app->common->getDashboardSettings();
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
<?php if (Yii::$app->session->hasFlash('success')): ?>
           <div class="alert alert-success">
              <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
              <?= Yii::$app->session->getFlash('success') ?>
           </div>
            <?php endif;
             $studentsGet=StudentInfo::find()->where(['is_active'=>1,'fk_branch_id'=>yii::$app->common->getBranch(),'user_id'=>Yii::$app->user->identity->id])->one(); ?>


     <section class="content">
      <!-- mini tab -->
      <div class="row">
      <div class="form-inline">
    <!-- <div class="form-group">
        <input class="form-control" type="text">
    </div> -->
    <div class="col-xs-4 col-sm-4 col-md-2 btn btn-default" style="background: #00a65a;color:white;">Last Fee<br/>Rs. 
  <?php 
  if(count($previousFeeTakenMonth) > 0){ 
   echo $previousFeeTakenMonth['total_amount_receive']+$previousFeeTakenMonth['transport_amount_rcv']+$previousFeeTakenMonth['hostel_amount_rcv']+$previousFeeTakenMonth['absent_fine_rcv']+$fee_arrears_rcv;
  }
   ?>
    </div>
    <div class="col-xs-4 col-sm-4 col-md-2 btn btn-default" style="background: #BC8F8F;color:white">Last Fee<br/>
      <span style="font-size: 9px">
  <?php echo (date('M-Y',strtotime($previousFeeTakenMonth['from_date'])) == date('M-Y',strtotime($previousFeeTakenMonth['to_date'])))? date('M-Y',strtotime($previousFeeTakenMonth['from_date'])) : date('M-Y',strtotime($previousFeeTakenMonth['from_date'])) .' - '. date('M-Y',strtotime($previousFeeTakenMonth['to_date'])) ?>
</span>
    </div>
    <div class="col-xs-4 col-sm-4 col-md-2 btn btn-default" style="background: #808080;color:white">Arrears <br />
    <span style="color:white"><?php 
              if(count($totalArrear)>0){
              echo 'Rs. '; 
              echo $totalArrear +$totalFeeSumissionArrears; 
              }else{
                echo 'Rs: 0';
              }
               ?>
        </span>
             </div>
    <div class="col-xs-4 col-sm-4 col-md-2 btn btn-default" style="background: #00c0ef;color:white">Fee Rcv.<br>
    <span style="color:white"><?php 
            if(count($totalFeeCollected)>0){
              echo 'Rs. ';
              echo $totalFeeCollected + $fee_arrears_rcv; 
            }else{
              echo 'Rs: 0';
            }
            ?>
          </span>
    </div>
    
    
     <div class="col-xs-4 col-sm-4 col-md-2 btn btn-default" style="background: #BC8F8F;color:white">Attendance <br/>
      <span style="color:white"><?php
      if(count($today_attendance)>0){
        if($today_attendance->leave_type='absent'){
          echo 'A';
        }else if($today_attendance->leave_type='leave'){
          echo 'L';
        }else if($today_attendance->leave_type='late'){
          echo 'Late';
        }
      }else{
        echo 'P';
      }

       ?></span>
     </div>
    <!--  <div class="col-xs-4 col-sm-4 col-md-2 btn btn-default" style="background: #B8860B"><span style="font-size: 11px;color:white">Upcomming Exam </span><br/>
     <span style="color:white">Final Term</span>
    </div> -->
</div>
</div><br>
<!-- end of first row and start of second row -->
<div class="row">
      <div class="form-inline">
    <!-- <div class="form-group">
        <input class="form-control" type="text">
    </div> -->
    <div class="col-xs-4 col-sm-4 col-md-2 btn btn-default" style="background: #008B8B"> 
  <a href="<?php echo Url::to(['student/profile/','id'=>$studentsGet->stu_id])?>" style="color:white">Profile</a>
    </div>
    <div class="col-xs-4 col-sm-4 col-md-2 btn btn-default" style="background: #778899">
     <a href="<?php echo Url::to(['class-timetable/searchtimetable'])?>" style="color:white;font-size: 12px">
      Class TimeTable</a>
    </div>
    <?php if($dahboardSettings->parent_portal_exam_result == 'yes'){ ?>
   <div class="col-xs-4 col-sm-4 col-md-2 btn btn-default" style="background: #B0C4DE">
     <a style="color:white" class="dropdown-toggle" type="button" id="dropdownMenu2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
   Exams
     </a>
     <div class="dropdown-menu" aria-labelledby="dropdownMenu2">
   <a class="dropdown-item btn btn-info btn-xs" href="<?php echo Url::to(['exams/dmc-parent'])?>" style="color:black;font-size: 12px">
     Schedule</a>
   <a class="dropdown-item btn btn-warning btn-xs" href="<?php echo Url::to(['exams/dmc-index'])?>" style="color:black;font-size: 12px">
     DMC</a>
     </div>
   </div> 
 <?php } ?>
    <div class="col-xs-4 col-sm-4 col-md-2 btn btn-default" style="background: #A0522D">
     <a href="<?php echo Url::to(['exams/student-quiz-portal'])?>" style="color:white;">
      Quiz</a>
    </div>
    <div class="col-xs-4 col-sm-4 col-md-2 btn btn-default" style="background: #008B8B">
     <a href="<?php echo Url::to(['general/parent-task'])?>" style="color:white;font-size: 12px;margin-left:-9px">
      Home Task</a>
    </div>
    
</div>
</div><br>
<div class="row">
  <div class="col-xs-4 col-sm-4 col-md-2 btn btn-default" style="background: #EE82EE">
     <a href="<?php echo Url::to(['site/parent-noticeboard'])?>" style="color:white;">
      NoticeBoard</a>
    </div>
    <div class="col-xs-4 col-sm-4 col-md-2 btn btn-default" style="background: #9ACD32">
     <a href="<?php echo Url::to(['site/attendance-parent-cal'])?>" style="color:white;font-size: 10px;margin-left:-9px">
      Attendance Calendar</a>
    </div>
</div><br>
<!-- end of second row and start of third row -->
<!-- //form inline end -->
      <!-- fee graph -->
      <div class="row"> 
        <div class="col-xs-4 col-sm-4 col-md-12">  <!-- overflow-x:scroll -->
            <div id="fee_monthly" style="min-width: 310px; height: 400px; margin: 0 auto;"></div>
        </div>
      </div>
      <br> 
       <!-- start of attendance graph  -->
       <div class="responsive">
       <div class="row">
        <div class="col-md-12 col-sm-12">
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
        </div>
      </div><br>
      <!-- end of attendance graph -->
      
    </section>
<div id='calendar'></div>
<div id='calendars'></div>
<?php $studentName= Yii::$app->user->identity->first_name; ?>
 <?php   
$this->registerJs("$(document).ready(function() {
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
        text: 'Student Attendance last 8 days'
    },
    yAxis: {
        allowDecimals: false,
        title: {
            text: '$studentName'
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
});
});

");
?>