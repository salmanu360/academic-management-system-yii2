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
$this->registerJsFile(Yii::getAlias('@web').'/js/highcharts.js',['depends' => [yii\web\JqueryAsset::className()]]);
$this->registerJsFile(Yii::getAlias('@web').'/js/data.js',['depends' => [yii\web\JqueryAsset::className()]]);
$this->registerJsFile(Yii::getAlias('@web').'/js/exporting.js',['depends' => [yii\web\JqueryAsset::className()]]);
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
             $total_students=StudentInfo::find()->where(['is_active'=>1,'fk_branch_id'=>yii::$app->common->getBranch()])->count(); ?>
     <section class="content">
        
      <div class="row">
      <div class="form-inline">
    <div class="col-xs-4 col-sm-4 col-md-2 btn btn-default" style="background: #008B8B"> 
  <a href="<?php echo Url::to(['employee/view/','id'=>$EmployeeInfo->emp_id])?>" style="color:white">Profile</a>
    </div>
    <div class="col-xs-4 col-sm-4 col-md-2 btn btn-default" style="background: #778899">
     <a href="<?php echo Url::to(['class-timetable/searchtimetable'])?>" style="color:white;font-size: 12px">
      Class TimeTable</a>
    </div>
    <?php if(yii::$app->user->identity->fk_role_id == 5){?>
    <div class="col-xs-4 col-sm-4 col-md-2 btn btn-default" style="background: #B0C4DE">
  <a style="color:white" class="dropdown-toggle" type="button" id="dropdownMenu2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
    Exams
  </a>
  <div class="dropdown-menu" aria-labelledby="dropdownMenu2">
    <a class="dropdown-item btn btn-info btn-xs" href="<?php echo Url::to(['exams/exam-details'])?>" style="color:black;font-size: 10px">
      Schedule</a>
    <a class="dropdown-item btn btn-warning btn-xs" href="<?php echo Url::to(['exams/award-list'])?>" style="color:black;font-size: 9px">
      Award List</a>
  </div>
    </div>
    <?php }?>
    <div class="col-xs-4 col-sm-4 col-md-2 btn btn-default" style="background: #9ACD32">
     <a href="<?php echo Url::to(['site/attendance-employee'])?>" style="color:white;font-size: 10px;margin-left:-9px">
      Attendance Calendar</a>
    </div>
    <div class="col-xs-4 col-sm-4 col-md-2 btn btn-default" style="background: #EE82EE">
     <a href="<?php echo Url::to(['site/parent-noticeboard'])?>" style="color:white;">
      NoticeBoard</a>
    </div>
 
</div>
</div><br>
<?php 
if(yii::$app->user->identity->fk_role_id == 5){?>
<div class="row">
       <div class="col-xs-4 col-sm-4 col-md-2 btn btn-default" style="background: #008B8B;color:white;font-size: 12px">
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

        <div class="col-xs-4 col-sm-4 col-md-2 btn btn-default" style="background: #778899;color:white;font-size: 12px">
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

        <div class="col-xs-4 col-sm-4 col-md-2 btn btn-default" style="background: #B0C4DE;color:black;font-size: 12px">
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

        <div class="col-xs-4 col-sm-4 col-md-2 btn btn-default" style="background: #FF8C00;color:black;font-size: 12px">
        <span style="margin-left:-10px">Today Fee Rcv</span><br />
        <span style="margin-left:-10px;font-size: 11px">
          <?php  echo ($todayFeeCollected)?$todayFeeCollected:0; ?>
        </span>
        </div>
        <div class="col-xs-4 col-sm-4 col-md-2 btn btn-default" style="background: #BC8F8F;color:white;font-size: 12px">
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
    </div><br>
  <?php }?>
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
      <!-- <div class="row"> 
        <div class="col-md-11 col-sm-11"> 
            <div id="fee_monthly" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
        </div>
      </div>
      <br>  -->
      <!-- employee attendance graph -->
      <!-- <div class="row">
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
                    //foreach ($attendance_employee_dates as $key => $dateEmpl) {
                   ?>
                    <tr>
                        <th> <?//=date('d-m-Y',strtotime($dateEmpl));?> </th>
                        <th> <?php  //echo $empl_att_array[$dateEmpl]['absentCount']?></th>
                        <th> <?php  //echo $empl_att_array[$dateEmpl]['presentCount']?></th>
                        <th> <?php  //echo $empl_att_array[$dateEmpl]['leaveCount']?></th>
                        <th> <?php  //echo $empl_att_array[$dateEmpl]['lateCount']?></th>
                    </tr>
                    <?php //} ?>
                </tbody>
            </table>
        </div>
      </div><br> -->
       <!-- start of attendance graph  -->
       <!-- <div class="row">
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
                    //foreach ($attendance_month_dates as $key => $date) {
                   ?>
                    <tr>
                        <th> <?//=date('d-m-Y',strtotime($date));?> </th>
                        <th> <?php  //echo $studentAttendanceQueryThirtyDays[$date]['absentCount']?></th>
                        <th> <?php  //echo $studentAttendanceQueryThirtyDays[$date]['presentCount']?></th>
                        <th> <?php  //echo $studentAttendanceQueryThirtyDays[$date]['leaveCount']?></th>
                        <th> <?php  //echo $studentAttendanceQueryThirtyDays[$date]['lateCount']?></th>
                    </tr>
                    <?php //} ?>
                </tbody>
            </table>
        </div>
             </div><br> -->
      <!-- end of attendance graph -->
      <!-- start of calendar end of high chart for class -->
    </section>


 <?php   
$this->registerJs("$(document).ready(function() {

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
});
});
");
?>