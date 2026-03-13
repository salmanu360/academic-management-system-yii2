<?php 
use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\grid\GridView;
use app\models\StudentInfo;
use kartik\date\DatePicker;
use app\models\RefClass;
use app\models\RefSection;
use app\models\RefGroup;
use app\models\StudentLeaveInfo;
use yii\widgets\ActiveForm;
use kartik\depdrop\DepDrop; 
use kartik\select2\Select2;

$this->registerCssFile(Yii::getAlias('@web').'/css/site.css',['depends' => [yii\web\JqueryAsset::className()]]);

/* @var $this yii\web\View */
$this->title = 'General Reports';
?>
<section class="content-header" style="margin-top: -37px;">
      <h1>
    General Reports
      </h1>
      
    </section>
<div class="filter_wrap content_col tabs grey-form">
<div class="reports_wrap">
	<!--Reports Graphs-->
    <div class="rep_graphs" id="rep_graphs">
    	<img src="<?= Url::to('@web/img/graphs.png') ?>" alt="MIS">
    </div>  
    <div class="shade fee-gen none_c">  
    <div class="bhoechie-tab-container">
    <div class="bhoechie-tab-menu">
      <div class="list-group"> 
     
        <a href="/new-student"  class="list-group-item active text-center">All Students </a> 
        <a href="/new-student"  class="list-group-item text-center">Admission </a> 
        <a href="<?= url::to(['/reports/transport'])?>" class="list-group-item text-center">Transport </a> 
       <!--  <a href="/acadamic" class="list-group-item text-center"> Student Attendance </a>  -->
       <a href="/acadamic" class="list-group-item text-center"> Student Issued Leaving Certificate </a>  
      </div>

     
    </div>
    <div class="bhoechie-tab">

    <!-- start of all students in the school -->
    <br />
    <div class="bhoechie-tab-content active">
    <!-- <div class="row">
      <div class="col-md-5 callout callout-info">
      </div>
    </div> -->
    <div class="row" style="    padding: 15px;">
    <div class="col-md-2">
    <?php 
    $class_array = ArrayHelper::map(\app\models\RefClass::find()->where(['fk_branch_id'=>Yii::$app->common->getBranch(),'status'=>'active'])->all(), 'class_id', 'title'); 
     
    echo Html::label('Class');
    echo Html::activeDropDownList($model, 'classid',$class_array,['prompt'=>'Select Class','class'=>'form-control','id'=>'getdataclass','data-url'=>Url::to(['reports/class-data'])]);
    ?>

    </div>
    <div class="col-md-2">
    <label for="">Group</label>
      <select name="" id="classdatagroup" class="form-control" data-url="<?= Url::to(['reports/group-data']);?>"></select>
    </div>
    <div class="col-md-2">
    <label for="">Section</label>
      <select name="" id="classdatasection" class="form-control" data-url="<?= Url::to(['reports/section-data']);?>"></select>
    </div>
    </div>
    <div style="height: 20px"></div>
    <div class="row" style="    padding: 15px;">
    <div class="col-md-6">
    <a href="javaScript:void()" id="counStudent" style="font-size: 16px;
    font-weight: bold; text-decoration: none" data-url="<?= Url::to(['reports/student-data-classwise']);?>"></a>
      
      
    </div>
 
   
    </div>
    </div>
    <!-- end of all students in the school -->

    <!-- flight section -->
    <div class="bhoechie-tab-content">
    <!-- tab 1  content-->
    
    <ul class="nav nav-tabs">
      <li class="active"><a data-toggle="tab" href="#home">New Admission Class wise</a></li>
      <li><a data-toggle="tab" href="#menu1">Promoted Class Wise</a></li>
      <li><a data-toggle="tab" href="#YearlyAdmission">Yearly Admission</a></li>
      <!-- <li><a data-toggle="tab" href="#menu3">Promoted Student Percentage</a></li> -->
    </ul>
    <div class="tab-content">
    <div id="home" class="tab-pane fade in active">
      <p>
      <div class="row">
        <div class="col-md-12">
        <input type="submit" name="Generate Report" id="newAdmissionClassWise" class="btn btn-default pull-right" value="Generate Report" data-url=<?php echo Url::to(['reports/new-admission-classwise-pdf']) ?> />
          <table class="table table-striped">
            <thead>
              <tr>
                <th>Class</th>
                <th>No of Students</th>
                <th>Percentage</th>
              </tr>
            </thead>
            <tbody>
              <?php 
    
                $getAllClasses=RefClass::find()->where(['fk_branch_id'=>yii::$app->common->getBranch(),'status'=>'active'])->all();
               foreach ($getAllClasses as $allclass) {
                  // echo "<pre>";print_r($allclass);
                  //continue;
    
    //continue;
                
               $newadmisnAvg=yii::$app->db->createCommand("select abc.class_id,abc.class_name,abc.No_Of_Student as `No_of_student_newly_admitted`, ( (abc.No_Of_Student) / (SELECT count(*) FROM student_info si2 inner join ref_class rc on rc.class_id=si2.class_id where si2.fk_branch_id=".yii::$app->common->getBranch()." and rc.title='".$allclass->title."' and si2.is_active =1) ) * 100 as `Percentage_of_newly_admitted_student` from (select rc.class_id, rc.title as class_name, count(*) as `No_Of_Student` from student_info si inner join ref_class rc on rc.class_id=si.class_id where si.stu_id not in (select fk_stu_id from stu_reg_log_association) and title = '".$allclass->title."' and si.fk_branch_id=".yii::$app->common->getBranch()." and si.is_active = 1 GROUP by rc.class_id, rc.title) abc")->queryAll();
                  /// echo '<pre>';print_r($newadmisnAvg);
                   //continue;
               $admissionarray=[];
               foreach ($newadmisnAvg as $newadmisnAvgx) {?>
              <tr>
                <td><?php echo $admissionarray[]=$newadmisnAvgx['class_name'];?></td>
                <td><?php echo $admissionarray[]=$newadmisnAvgx['No_of_student_newly_admitted'];?></td>
                <td><?php echo round($admissionarray[]=$newadmisnAvgx['Percentage_of_newly_admitted_student'],2).'%';?></td>
              </tr>
              <?php }
             }
              // die;
             
              //die;
    
                //echo $admsnArray['class_id'];
               // echo '<pre>';print_r($admsnArray);
                //foreach ($newadmisnAvg as $newadmisnAvgx) { 
                  
    
                  ?>
              <!--<tr>
                     <td><?php //echo $newadmisnAvgx['class_name']; ?></td>
                    <td><?php //echo $newadmisnAvgx['No_of_student_newly_admitted']; ?></td>
                    
                    <td><?php //echo $newadmisnAvgx['Percentage_of_newly_admitted_student']; ?></td>
                    
                    
                                  </tr> -->
              <?php 
                 // } die;
                   ?>
            </tbody>
          </table>
          <!-- <table class="table table-striped">
                              <thead>
                  <tr>
                    <th>Class</th>
                    <th>No Of Confirmed New Admission</th>
                  </tr>
                              </thead>
                              <tbody>
                              <?php
                               //foreach ($getclaswise as $clswise) { ?>
                  <tr>
                    <td><?php //echo $clswise['title']; ?></td>
                    <td><?php //echo $clswise['No_of_students']; ?></td>
                  </tr>
                  <?php //} ?>
                              </tbody>
                            </table> --> 
        </div>
        <div class="col-md-6"> 
          <!-- <table class="table table-striped">
                              <thead>
                  <tr>
                  <th>Class</th>
                    <th>Not Confirmed</th>
                  </tr>
                              </thead>
                              <tbody>
                              <?php
                              // foreach ($getclaswiseDeactive as $clswiseDeactive) { ?>
                  <tr>
                    <td><?php //echo $clswiseDeactive['title']; ?></td>
                          
                    <td><?php //echo $clswiseDeactive['No_of_students']; ?></td>
                  </tr>
                  <?php //} ?>
                              </tbody>
                            </table> --> 
        </div>
      </div>
      </p>
    </div>
    <div id="menu1" class="tab-pane fade">
      <p>
       <input type="submit" name="Generate Report" id="promotedClassWise" class="btn btn-default pull-right" value="Generate Report" data-url=<?php echo Url::to(['reports/new-promotion-classwise-pdf']) ?> />
      <table class="table table-striped">
        <thead>
          <tr>
            <th>Class</th>
            <th>No of Students</th>
            <th>Percentage</th>
          </tr>
        </thead>
        <tbody>
          <?php 
    
                $getAllClass=RefClass::find()->where(['fk_branch_id'=>yii::$app->common->getBranch(),'status'=>'active'])->all();
                foreach ($getAllClass as $allclasx) {
                  // echo "<pre>";print_r($allclass);
                  //continue;
    
    //continue;
                
               $studentPercetn=yii::$app->db->createCommand("select abc.class_id,abc.class_name,abc.No_Of_Student as `No_of_student_newly_admitted`,
                 ((abc.No_Of_Student)/
                  (SELECT count(*) FROM  student_info si2 
                  inner join ref_class rc on rc.class_id=si2.class_id
                  where si2.fk_branch_id=9 and rc.title= '".$allclasx->title."' and si2.is_active =1))* 100
                  as `Percentage_of_newly_admitted_student`
                  from 
                  (select rc.class_id, rc.title as class_name, count(*) as `No_Of_Student` from student_info si
                  inner join ref_class rc on rc.class_id=si.class_id
                  where si.stu_id in (select fk_stu_id from stu_reg_log_association) and title = '".$allclasx->title."'  and si.fk_branch_id=".yii::$app->common->getBranch()." and si.is_active = 1
                  GROUP by rc.class_id, rc.title) abc")->queryAll();
                  /// echo '<pre>';print_r($newadmisnAvg);
                   //continue;
               $stuarray=[];
               foreach ($studentPercetn as $studentPercetx) {?>
          <tr>
            <td><?php echo $stuarray[]=$studentPercetx['class_name'];?></td>
            <td><?php echo $stuarray[]=$studentPercetx['No_of_student_newly_admitted'];?></td>
            <td><?php echo round($stuarray[]=$studentPercetx['Percentage_of_newly_admitted_student'],2).'%';?></td>
          </tr>
          <?php } } ?>
          <?php //foreach ($promtedclasswixeAvg as $promtedclasswixeAvg) { ?>
          <!-- <tr>
                    <td><?php //echo $promtedclasswixeAvg['class_name']; ?></td>
                    <td><?php //echo $promtedclasswixeAvg['No_Of_Student']; ?></td>
                    <td><?php //echo $promtedclasswixeAvg['Average_Promoted_Students_per_Class']; ?></td>
                  </tr> -->
          <?php //} ?>
        </tbody>
      </table>
      <!-- <table class="table table-striped">
                <thead>
                  <tr>
                    <th>Class</th>
                    <th>No of promoted class wise</th>
                  </tr>
                </thead>
                <tbody>
                <?php //foreach ($promotedclaswise as $promotedclaswis) { ?>
                  <tr>
                    <td><?php //echo $promotedclaswis['class_name']; ?></td>
                    <td><?php //echo $promotedclaswis['No_of_new_promoted_class_wise']; ?></td>
                  </tr>
                  <?php //} ?>
                </tbody>
                          </table> -->
      </p>
    </div>
    <div id="menu2" class="tab-pane fade">
      <p>
      <table class="table table-striped">
        <thead>
          <tr>
            <th>Class</th>
            <th>No of Students</th>
            <th>Percentage</th>
          </tr>
        </thead>
        <tbody>
          <?php 
    
                $getAllClasses=RefClass::find()->where(['fk_branch_id'=>yii::$app->common->getBranch(),'status'=>'active'])->all();
               foreach ($getAllClasses as $allclass) {
                  // echo "<pre>";print_r($allclass);
                  //continue;
    
    //continue;
                
               $newadmisnAvg=yii::$app->db->createCommand("select abc.class_id,abc.class_name,abc.No_Of_Student as `No_of_student_newly_admitted`, ( (abc.No_Of_Student) / (SELECT count(*) FROM student_info si2 inner join ref_class rc on rc.class_id=si2.class_id where si2.fk_branch_id=".yii::$app->common->getBranch()." and rc.title='".$allclass->title."' and si2.is_active =1) ) * 100 as `Percentage_of_newly_admitted_student` from (select rc.class_id, rc.title as class_name, count(*) as `No_Of_Student` from student_info si inner join ref_class rc on rc.class_id=si.class_id where si.stu_id not in (select fk_stu_id from stu_reg_log_association) and title = '".$allclass->title."' and si.fk_branch_id=".yii::$app->common->getBranch()." and si.is_active = 1 GROUP by rc.class_id, rc.title) abc")->queryAll();
                  /// echo '<pre>';print_r($newadmisnAvg);
                   //continue;
               $admissionarray=[];
               foreach ($newadmisnAvg as $newadmisnAvgx) {?>
          <tr>
            <td><?php echo $admissionarray[]=$newadmisnAvgx['class_name'];?></td>
            <td><?php echo $admissionarray[]=$newadmisnAvgx['No_of_student_newly_admitted'];?></td>
            <td><?php echo round($admissionarray[]=$newadmisnAvgx['Percentage_of_newly_admitted_student'],2).'%';?></td>
          </tr>
          <?php }
             }
              // die;
             
              //die;
    
                //echo $admsnArray['class_id'];
               // echo '<pre>';print_r($admsnArray);
                //foreach ($newadmisnAvg as $newadmisnAvgx) { 
                  
    
                  ?>
          <!--<tr>
                     <td><?php //echo $newadmisnAvgx['class_name']; ?></td>
                    <td><?php //echo $newadmisnAvgx['No_of_student_newly_admitted']; ?></td>
                    
                    <td><?php //echo $newadmisnAvgx['Percentage_of_newly_admitted_student']; ?></td>
                    
                    
                                  </tr> -->
          <?php 
                 // } die;
                   ?>
        </tbody>
      </table>
      </p>
    </div> 
    <!-- start of yearly admission -->
    <div id="YearlyAdmission" class="tab-pane fade">
    <p>
    <div class="row">
    <div class="col-md-3 yearCalendar">
    <select name="fromYear" class="form-control YearCal" data-url=<?php echo Url::to(['reports/yearly-admission'])?>>
    <option>Select Year</option>
    <?php
       '<select name="fromYear">';
       $starting_year  =date('Y', strtotime('-10 year'));
       $ending_year = date('Y', strtotime('+0 year'));
          for($starting_year; $starting_year <= $ending_year; $starting_year++) {
            echo '<option value="'.$starting_year.'">'.$starting_year.'</option>';
          }             
         //echo '</select>'; 
       ?>
    </select>
    </div>
    </div>
    <br />
    <div class="row getYearadmission table-responsive">
    </div>
    </p>
    </div>
    
    <!-- end of yearly admission -->
    <div id="menu3" class="tab-pane fade">
    <p>
    <table class="table table-striped">
    <thead>
    <tr>
    <th>
    
    Class
    
    </th>
    <th>
    
    No of Students
    
    </th>
    <th>
    
    Percentage
    
    </th>
    </tr>
    </thead>
    <tbody>
    <?php 
    
                $getAllClass=RefClass::find()->where(['fk_branch_id'=>yii::$app->common->getBranch(),'status'=>'active'])->all();
                foreach ($getAllClass as $allclasx) {
                  // echo "<pre>";print_r($allclass);
                  //continue;
    
    //continue;
                
               $studentPercetn=yii::$app->db->createCommand("select abc.class_id,abc.class_name,abc.No_Of_Student as `No_of_student_newly_admitted`,
                 ((abc.No_Of_Student)/
                  (SELECT count(*) FROM  student_info si2 
                  inner join ref_class rc on rc.class_id=si2.class_id
                  where si2.fk_branch_id=9 and rc.title= '".$allclasx->title."' and si2.is_active =1))* 100
                  as `Percentage_of_newly_admitted_student`
                  from 
                  (select rc.class_id, rc.title as class_name, count(*) as `No_Of_Student` from student_info si
                  inner join ref_class rc on rc.class_id=si.class_id
                  where si.stu_id in (select fk_stu_id from stu_reg_log_association) and title = '".$allclasx->title."'  and si.fk_branch_id=".yii::$app->common->getBranch()." and si.is_active = 1
                  GROUP by rc.class_id, rc.title) abc")->queryAll();
                  /// echo '<pre>';print_r($newadmisnAvg);
                   //continue;
               $stuarray=[];
               foreach ($studentPercetn as $studentPercetx) {?>
    <tr>
    <td>
    <?php echo $stuarray[]=$studentPercetx['class_name'];?>
    </td>
    <td>
    <?php echo $stuarray[]=$studentPercetx['No_of_student_newly_admitted'];?>
    </td>
    <td>
    <?php echo round($stuarray[]=$studentPercetx['Percentage_of_newly_admitted_student'],2).'%';?>
    </td>
    </tr>
    <?php } } ?>
    <?php //foreach ($promtedclasswixeAvg as $promtedclasswixeAvg) { ?>
    <!-- <tr>
                    <td><?php //echo $promtedclasswixeAvg['class_name']; ?></td>
                    <td><?php //echo $promtedclasswixeAvg['No_Of_Student']; ?></td>
                    <td><?php //echo $promtedclasswixeAvg['Average_Promoted_Students_per_Class']; ?></td>
                  </tr> -->
    <?php //} ?>
    </tbody>
    </table>
    </p>
    </div>
    </div>
    </div>
    <!-- end of tab 1 admission --> 
    <!-- train section -->
    <div class="bhoechie-tab-content">
    <!-- tab 2 content -->
    <ul class="nav nav-tabs">
    <li class="active">
    <a data-toggle="tab" href="#homes">Over All Transport Student Wise</a>
    </li>
    <li>
    <a data-toggle="tab" href="#menu11">Total No of Student who avail Transport</a>
    </li>
    <li>
    <a data-toggle="tab" href="#menus">OverAll Transport</a>
    </li>
    <!-- <li>
    <a data-toggle="tab" href="#menuss">Transport user class, group, section wise</a>
    </li -->
    </ul>
    <div class="tab-content">
    <div id="homes" class="tab-pane fade in active">
    <input type="submit" name="Generate Report" id="overallTransport" class="btn btn-default pull-right" value="Generate Report" data-url=<?php echo Url::to(['reports/over-all-transport-pdf']) ?> />
    <p>
    <?php $transport=yii::$app->db->createCommand("select si.stu_id,concat (u.first_name,' ',u.middle_name,' ',u.last_name) as `student_name`,z.title as `zone_name`,r.title as `route_name`, s.title as `stop_name`,s.fare as `fare` from student_info si
        inner join user u on u.id=si.user_id
        inner join stop s on s.id=si.fk_stop_id
        inner join route r on r.id=s.fk_route_id
        inner join zone z on z.id=r.fk_zone_id where si.fk_branch_id='".yii::$app->common->getBranch()."'
        ")->queryAll(); ?>
    </p>
    <table class="table table-striped">
    <thead>
    <tr>
    <th>Student Name</th>
    <th>Zone</th>
    <th>Route </th>
    <th>Stop</th>
    <th>Fare</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($transport as $transports) {
       //echo '<pre>';print_r($withdrawlStud);
        ?>
    <tr>
    <td>
    <?= $transports['student_name'] ?>
    </td>
    <td>
    <?= $transports['zone_name'] ?>
    </td>
    <td>
    <?= $transports['route_name'] ?>
    </td>
    <td>
    <?= $transports['stop_name'] ?>
    </td>
    <td>
    <?= $transports['fare'] ?>
    </td>
    </tr>
    <?php } ?>
    </tbody>
    </table> 
    </div>
    <div id="menu11" class="tab-pane fade">
    <p>
    <?php  $availTransport=StudentInfo::find()->where(['not', ['fk_stop_id' => null]])->count();
        //echo '<pre>';print_r($availTransport);?>
    </p>
    <table class="table table-striped">
    <thead>
    <tr>
    <th>No of Students who Avail Transport</th>
    </tr>
    </thead>
    <tbody>
    <tr>
    <td>
    <?php echo $availTransport; ?>
    </td>
    </tr>
    </tbody>
    </table>
    
    </div>
    
    <!-- start of overall transport -->
    <div id="menus" class="tab-pane fade">
    <p>
    <div class="showalltransport">
    </div>
    </p>
    </div>
    
    <!-- end of overall transport -->
    
    <div id="menuss" class="tab-pane fade">
    <p>
    </p>
    </div>
    </div>
    </div>
    
    <!-- ///////////////////////////// --> 
    
    <!-- hotel search -->
    

  <!-- student leaving school section -->
                <div class="bhoechie-tab-content">
                <div class="row">
    <div class="col-md-3 yearCalendar">
    <select style="margin-left: 24px; margin-top: 22px;" name="fromYear" id="yearLeave" class="form-control" data-url=<?php echo Url::to(['reports/leave-school'])?>>
    <option>Select Year</option>
    <?php
       '<select name="fromYear">';
       $starting_year  =date('Y', strtotime('-10 year'));
       $ending_year = date('Y', strtotime('+0 year'));
          for($starting_year; $starting_year <= $ending_year; $starting_year++) {
            echo '<option value="'.$starting_year.'">'.$starting_year.'</option>';
          }             
         //echo '</select>'; 
       ?>
    </select>
    </div>
    <div class="col-md-3" style="display: none" id="showPdfLeave">
       <input type="submit" style="margin-left: 24px; margin-top: 25px;" name="Generate Report" value="Generate Report" class="btn green-btn" id="yearlevpdf" data-url="<?php echo Url::to(['reports/leave-school-pdf'])?>" />
    </div>
    <div class="col-md-3" style="display: none" id="leaveYearpdf">
       <input type="submit" style="margin-left: 24px; margin-top: 25px;" name="Generate Report" value="Generate Report" class="btn green-btn" id="levyearpdf" data-url="<?php echo Url::to(['reports/leave-scholl-class-pdf'])?>" />
    </div>

    <div class="col-md-3" style="display: none" id="leaveYearstudpdf">
       <input type="submit" style="margin-left: 24px; margin-top: 25px;" name="Generate Report" value="Generate Report" class="btn green-btn" id="leaveYearstudntpdf" data-url="<?php echo Url::to(['reports/leave-schol-class-student-pdf'])?>" />
    </div>

    <input type="hidden" name="" value="" id="clsxId">
    </div>
    <br />
    <div class="row">
    <div class="col-md-12" id="showleavestu"></div>
    </div> 
              <!--      <table class="table table-striped">
                          <thead>
                            <tr>
                              <th>Name</th>
                              <th>Class</th>
                              <th>Group</th>
                              <th>Section</th>
                              <th>Next School</th>
                              <th>Date</th>
                            </tr>
                          </thead>
                          <tbody> -->
              <?php 
    
                $leaveInfo=StudentLeaveInfo::find()->all();
               //foreach ($leaveInfo as $leave) {?>

                <!-- <tr>
                  <td><?php //echo $leave->stu->user->first_name?></td>
                  <td><?php //echo $leave->class->title?></td>
                  <td><?php //if($leave->group_id == ''){echo "N/A"; }else{echo $leave->group->title;}?></td>
                  <td><?php //echo $leave->section->title?></td>
                  <td><?php //echo $leave->next_school?></td>
                  <td><?php //echo date('d-m-Y',strtotime($leave->created_date))?></td>
                
                
                </tr> -->
                 
              
              <?php //}?>
            <!-- </tbody>
            </table>  -->
                </div>

   <!-- end of student leaving school section -->
   

    </div>
</div>
</div>
<input type="hidden" id="zone" data-url=<?php echo Url::to(['reports/get-zone-generic']) ?>>
<?php 
$script= <<< JS
$(document).ready(function() {

var url=$('#zone').data('url');
//alert(url);
$.ajax
        ({
            type: "POST",
            dataType:"JSON",
            //data: dataString,
            url: url,
            cache: false,
            success: function(html)
            {
              //console.log(html.zonegenric);
                $(".showalltransport").html(html.zonegenric);
            } 
        });
});

JS;
$this->registerJs($script);

?>
