<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use app\models\EmplEducationalHistoryInfo;
use app\models\EmployeeSalarySelection;
use yii\helpers\Url;
use yii\grid\GridView;
use app\models\EmployeeAllowances;
use app\models\EmployeeDeductions;
use app\models\EmployeePayroll;
use app\models\EmployeeParentsInfo;

/* @var $this yii\web\View */
/* @var $model app\models\EmployeeInfo */

//$this->title =  $model->user->first_name.' '.$model->user->middle_name.' '.$model->user->last_name;

$this->title='Profile of '.Yii::$app->common->getName($model->user_id);
//$this->params['breadcrumbs'][] = ['label' => 'Employee Info', 'url' => ['index']];
//$this->params['breadcrumbs'][] = $this->title; 

?>

<style>
  .title{
 margin-left:20px
}

.fa-user{
 font-size:80px   
}

.searchable-container{
    margin-top:40px;
}

.glyphicon-lg{
    font-size:4em
}
.info-block{
    border-right:5px solid #E6E6E6;margin-bottom:25px
}
.info-block .square-box {
    width:120px;
    min-height:120px;
    margin-right:22px;
    text-align:center!important;
    background-color:#676767;
    padding:20px 0
}
.info-block:hover .info-block.block-info {
    border-color:#20819e
}

.info-block.block-info .square-box {
    background-color:#5bc0de;
    color:#FFF
}






/*   */

body{margin-top:20px;
background:#eee;
}

.btn-compose-email {
    padding: 10px 0px;
    margin-bottom: 20px;
}

.btn-danger {
    background-color: #E9573F;
    border-color: #E9573F;
    color: white;
}

.panel-teal .panel-heading {
    background-color: #37BC9B;
    border: 1px solid #36b898;
    color: white;
}

.panel .panel-heading {
    padding: 5px;
    border-top-right-radius: 3px;
    border-top-left-radius: 3px;
    border-bottom: 1px solid #DDD;
    -moz-border-radius: 0px;
    -webkit-border-radius: 0px;
    border-radius: 0px;
}

.panel .panel-heading .panel-title {
    padding: 10px;
    font-size: 17px;
}

form .form-group {
    position: relative;
    margin-left: 0px !important;
    margin-right: 0px !important;
}

.inner-all {
    padding: 10px;
}

/* ========================================================================
 * MAIL
 * ======================================================================== */
.nav-email > li:first-child + li:active {
  margin-top: 0px;
}
.nav-email > li + li {
  margin-top: 1px;
}
.nav-email li {
  background-color: white;
}
.nav-email li.active {
  background-color: transparent;
}
.nav-email li.active .label {
  background-color: white;
  color: black;
}
.nav-email li a {
  color: black;
  -moz-border-radius: 0px;
  -webkit-border-radius: 0px;
  border-radius: 0px;
}
.nav-email li a:hover {
  background-color: #EEEEEE;
}
.nav-email li a i {
  margin-right: 5px;
}
.nav-email li a .label {
  margin-top: -1px;
}

.table-email tr:first-child td {
  border-top: none;
}
.table-email tr td {
  vertical-align: top !important;
}
.table-email tr td:first-child, .table-email tr td:nth-child(2) {
  text-align: center;
  width: 35px;
}
.table-email tr.unread, .table-email tr.selected {
  background-color: #EEEEEE;
}
.table-email .media {
  margin: 0px;
  padding: 0px;
  position: relative;
}
.table-email .media h4 {
  margin: 0px;
  font-size: 14px;
  line-height: normal;
}
.table-email .media-object {
  width: 35px;
  -moz-border-radius: 2px;
  -webkit-border-radius: 2px;
  border-radius: 2px;
}
.table-email .media-meta, .table-email .media-attach {
  font-size: 11px;
  color: #999;
  position: absolute;
  right: 10px;
}
.table-email .media-meta {
  top: 0px;
}
.table-email .media-attach {
  bottom: 0px;
}
.table-email .media-attach i {
  margin-right: 10px;
}
.table-email .media-attach i:last-child {
  margin-right: 0px;
}
.table-email .email-summary {
  margin: 0px 110px 0px 0px;
}
.table-email .email-summary strong {
  color: #333;
}
.table-email .email-summary span {
  line-height: 1;
}
.table-email .email-summary span.label {
  padding: 1px 5px 2px;
}
.table-email .ckbox {
  line-height: 0px;
  margin-left: 8px;
}
.table-email .star {
  margin-left: 6px;
}
.table-email .star.star-checked i {
  color: goldenrod;
}

.nav-email-subtitle {
  font-size: 15px;
  text-transform: uppercase;
  color: #333;
  margin-bottom: 15px;
  margin-top: 30px;
}

.compose-mail {
  position: relative;
  padding: 15px;
}
.compose-mail textarea {
  width: 100%;
  padding: 10px;
  border: 1px solid #DDD;
}

.view-mail {
  padding: 10px;
  font-weight: 300;
}

.attachment-mail {
  padding: 10px;
  width: 100%;
  display: inline-block;
  margin: 20px 0px;
  border-top: 1px solid #EFF2F7;
}
.attachment-mail p {
  margin-bottom: 0px;
}
.attachment-mail a {
  color: #32323A;
}
.attachment-mail ul {
  padding: 0px;
}
.attachment-mail ul li {
  float: left;
  width: 200px;
  margin-right: 15px;
  margin-top: 15px;
  list-style: none;
}
.attachment-mail ul li a.atch-thumb img {
  width: 200px;
  margin-bottom: 10px;
}
.attachment-mail ul li a.name span {
  float: right;
  color: #767676;
}

@media (max-width: 640px) {
  .compose-mail-wrapper .compose-mail {
    padding: 0px;
  }
}
@media (max-width: 360px) {
  .mail-wrapper .panel-sub-heading {
    text-align: center;
  }
  .mail-wrapper .panel-sub-heading .pull-left, .mail-wrapper .panel-sub-heading .pull-right {
    float: none !important;
    display: block;
  }
  .mail-wrapper .panel-sub-heading .pull-right {
    margin-top: 10px;
  }
  .mail-wrapper .panel-sub-heading img {
    display: block;
    margin-left: auto;
    margin-right: auto;
    margin-bottom: 10px;
  }
  .mail-wrapper .panel-footer {
    text-align: center;
  }
  .mail-wrapper .panel-footer .pull-right {
    float: none !important;
    margin-left: auto;
    margin-right: auto;
  }
  .mail-wrapper .attachment-mail ul {
    padding: 0px;
  }
  .mail-wrapper .attachment-mail ul li {
    width: 100%;
  }
  .mail-wrapper .attachment-mail ul li a.atch-thumb img {
    width: 100% !important;
  }
  .mail-wrapper .attachment-mail ul li .links {
    margin-bottom: 20px;
  }

  .compose-mail-wrapper .search-mail input {
    width: 130px;
  }
  .compose-mail-wrapper .panel-sub-heading {
    padding: 10px 7px;
  }
}










/*font Awesome http://fontawesome.io*/
@import url(//maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css);
/*Comment List styles*/
.comment-list .row {
  margin-bottom: 0px;
}
.comment-list .panel .panel-heading {
  padding: 4px 15px;
  position: absolute;
  border:none;
  /*Panel-heading border radius*/
  border-top-right-radius:0px;
  top: 1px;
}
.comment-list .panel .panel-heading.right {
  border-right-width: 0px;
  /*Panel-heading border radius*/
  border-top-left-radius:0px;
  right: 16px;
}
.comment-list .panel .panel-heading .panel-body {
  padding-top: 6px;
}
.comment-list figcaption {
  /*For wrapping text in thumbnail*/
  word-wrap: break-word;
}
/* Portrait tablets and medium desktops */
@media (min-width: 768px) {
  .comment-list .arrow:after, .comment-list .arrow:before {
    content: "";
    position: absolute;
    width: 0;
    height: 0;
    border-style: solid;
    border-color: transparent;
  }
  .comment-list .panel.arrow.left:after, .comment-list .panel.arrow.left:before {
    border-left: 0;
  }
  /*****Left Arrow*****/
  /*Outline effect style*/
  .comment-list .panel.arrow.left:before {
    left: 0px;
    top: 30px;
    /*Use boarder color of panel*/
    border-right-color: inherit;
    border-width: 16px;
  }
  /*Background color effect*/
  .comment-list .panel.arrow.left:after {
    left: 1px;
    top: 31px;
    /*Change for different outline color*/
    border-right-color: #FFFFFF;
    border-width: 15px;
  }
  /*****Right Arrow*****/
  /*Outline effect style*/
  .comment-list .panel.arrow.right:before {
    right: -16px;
    top: 30px;
    /*Use boarder color of panel*/
    border-left-color: inherit;
    border-width: 16px;
  }
  /*Background color effect*/
  .comment-list .panel.arrow.right:after {
    right: -14px;
    top: 31px;
    /*Change for different outline color*/
    border-left-color: #FFFFFF;
    border-width: 15px;
  }
}
.comment-list .comment-post {
  margin-top: 6px;
}







/**** resumee ****/
                    
    /* uses font awesome for social icons */
@import url(http://maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css);

.page-header{
  text-align: center;    
}

/*social buttons*/
.btn-social{
  color: white;
  opacity:0.9;
}
.btn-social:hover {
  color: white;
    opacity:1;
}
.btn-facebook {
background-color: #3b5998;
opacity:0.9;
}
.btn-twitter {
background-color: #00aced;
opacity:0.9;
}
.btn-linkedin {
background-color:#0e76a8;
opacity:0.9;
}
.btn-github{
  background-color:#000000;
  opacity:0.9;
}
.btn-google {
  background-color: #c32f10;
  opacity: 0.9;
}
.btn-stackoverflow{
  background-color: #D38B28;
  opacity: 0.9;
}

/* resume stuff */

.bs-callout {
    -moz-border-bottom-colors: none;
    -moz-border-left-colors: none;
    -moz-border-right-colors: none;
    -moz-border-top-colors: none;
    border-color: #eee;
    border-image: none;
    border-radius: 3px;
    border-style: solid;
    border-width: 1px 1px 1px 5px;
    margin-bottom: 5px;
    padding: 20px;
}
.bs-callout:last-child {
    margin-bottom: 0px;
}
.bs-callout h4 {
    margin-bottom: 10px;
    margin-top: 0;
}

.bs-callout-danger {
    border-left-color: #d9534f;
}

.bs-callout-danger h4{
    color: #d9534f;
}

.resume .list-group-item:first-child, .resume .list-group-item:last-child{
  border-radius:0;
}

/*makes an anchor inactive(not clickable)*/
.inactive-link {
   pointer-events: none;
   cursor: default;
}

.resume-heading .social-btns{
  margin-top:15px;
}
.resume-heading .social-btns i.fa{
  margin-left:-5px;
}



@media (max-width: 992px) {
  .resume-heading .social-btn-holder{
    padding:5px;
  }
}


/* skill meter in resume. copy pasted from http://bootsnipp.com/snippets/featured/progress-bar-meter */

.progress-bar {
    text-align: left;
    white-space: nowrap;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
  cursor: pointer;
}

.progress-bar > .progress-type {
  padding-left: 10px;
}

.progress-meter {
  min-height: 15px;
  border-bottom: 2px solid rgb(160, 160, 160);
  margin-bottom: 15px;
}

.progress-meter > .meter {
  position: relative;
  float: left;
  min-height: 15px;
  border-width: 0px;
  border-style: solid;
  border-color: rgb(160, 160, 160);
}

.progress-meter > .meter-left {
  border-left-width: 2px;
}

.progress-meter > .meter-right {
  float: right;
  border-right-width: 2px;
}

.progress-meter > .meter-right:last-child {
  border-left-width: 2px;
}

.progress-meter > .meter > .meter-text {
  position: absolute;
  display: inline-block;
  bottom: -20px;
  width: 100%;
  font-weight: 700;
  font-size: 0.85em;
  color: rgb(160, 160, 160);
  text-align: left;
}

.progress-meter > .meter.meter-right > .meter-text {
  text-align: right;
}
</style>


<div class="container">
<div class="row">
    
    <div class="col-sm-10">
        
        <!-- resumt -->
        <div class="panel panel-default">
               <div class="panel-heading resume-heading">
                  <div class="row">
                     <div class="col-lg-12">
                        <div class="col-xs-12 col-sm-4">
                           <figure>
                           <?php 
       if(!empty($model->user->Image)){
       echo $img= '<img style="height: 208px;border-radius: 50%;
    width: 200px;" src="'.Yii::$app->request->BaseUrl.'/uploads/'.$model->user->Image.'">';
                   }
       ?>
                              <!-- <img style="height: 208px;
    width: 200px;" class="img-circle img-responsive" alt="" src="/bilal/uploads/^5F9F9D80153052B896A0B4218949787F118348AF9EF77832D9^pimgpsh_fullsize_distr.jpg"> -->

                           </figure>
                           <!-- <div class="row">
                              <div class="col-xs-12 social-btns">
                                 <div class="col-xs-3 col-md-1 col-lg-1 social-btn-holder">
                                    <a href="#" class="btn btn-social btn-block btn-google">
                                    <i class="fa fa-google"></i> </a>
                                 </div>
                                 <div class="col-xs-3 col-md-1 col-lg-1 social-btn-holder">
                                    <a href="#" class="btn btn-social btn-block btn-facebook">
                                    <i class="fa fa-facebook"></i> </a>
                                 </div>
                                 <div class="col-xs-3 col-md-1 col-lg-1 social-btn-holder">
                                    <a href="#" class="btn btn-social btn-block btn-twitter">
                                    <i class="fa fa-twitter"></i> </a>
                                 </div>
                                 <div class="col-xs-3 col-md-1 col-lg-1 social-btn-holder">
                                    <a href="#" class="btn btn-social btn-block btn-linkedin">
                                    <i class="fa fa-linkedin"></i> </a>
                                 </div>
                                 <div class="col-xs-3 col-md-1 col-lg-1 social-btn-holder">
                                    <a href="#" class="btn btn-social btn-block btn-github">
                                    <i class="fa fa-github"></i> </a>
                                 </div>
                                 <div class="col-xs-3 col-md-1 col-lg-1 social-btn-holder">
                                    <a href="#" class="btn btn-social btn-block btn-stackoverflow">
                                    <i class="fa fa-stack-overflow"></i> </a>
                                 </div>
                              </div>
                             
                              <br />
                               
                           </div> -->
                        </div>
                        <div class="col-xs-12 col-sm-8">
                          <ul class="list-group">
                          <li class="list-group-item"><?php if($model->gender_type == 1){echo 'Mr.';}else{echo 'Miss.';}?><?php echo Yii::$app->common->getName($model->user_id); ?></li>
                          <li class="list-group-item"><?php echo ucfirst($model->departmentType->Title); ?></li>

                          <li class="list-group-item"><?php echo $model->designation->Title?></li>
                          <li class="list-group-item"><i class="fa fa-phone"></i> <?php echo $model->contact_no?> </li>
                          <li class="list-group-item"><i class="fa fa-envelope"></i> <?php if(!empty($model->user->email)){echo $model->user->email;}else{echo "N/A";}?></li>
                           </ul>
                        </div>
                     </div>
                  </div>
               </div>
               <div class="bs-callout bs-callout-danger">
                  <h4>Personnel Information</h4>
                  <table class="table table-striped table-responsive ">
                     <thead>
                  <tr>
                     <th>CNIC</th><td><?php if(!empty($model->cnic)){echo $model->cnic;}else{echo "N/A";} ?></td>
                     <th>Date Of Hiring</th><td><?php if(!empty($model->hire_date)){echo $model->hire_date;}else{echo "N/A";} ?></td>
                     <th>Date Of Birth</th><td><?php if(!empty($model->dob)){echo $model->dob;}else{echo "N/A";} ?></td>
                     
                  </tr>
                  <tr>
                     <th>Graduation Year</th><td>2017</td>
                     <th>Marital Status</th><td><?php if($model->marital_status == 1){echo 'Single';}else if($model->marital_status == 2){echo 'Married';}else{echo 'Divorced';} ?></td>
                     <th>Number of children</th><td>
                     <?php 
                      $prntcontact=EmployeeParentsInfo::find()->where(['emp_id'=>$model->emp_id])->one();
                      if(!empty($prntcontact->no_of_children)){echo $prntcontact->no_of_children;}else{
                        echo 'N/A';
                      }
                      ?></td>

                  </tr>
                  <tr>
                    <th>Nationality</th><td><?php if(!empty($model->Nationality)){echo $model->Nationality;}else{echo "N/A";} ?></td>
                  </tr>
                  <tr>
                    <th>Permanent Address</th>
                    <td colspan="8"><?php if(!empty($model->location2)){echo strtoupper($model->location2) .' , '. $model->city->city_name .' ,  '. $model->district->District_Name .' ,  '. $model->province->province_name .' ,  '. $model->country->country_name;}else{echo "N/A";} ?>
                      
                    </td>
                  </tr>
                  <tr>
                    <th>Postal Address</th>
                    <td colspan="8"><?php if(!empty($model->location1)){
                      echo  strtoupper($model->location1) .' , '.'  '. $model->fkRefCityId2['city_name'] .', '. $model->fkRefDistrictId2['District_Name'] .' ,  '. $model->fkRefProvinceId2['province_name'] .' ,  '. $model->fkRefCountryId2['country_name'];
                      }else{echo "N/A";} ?></td>
                  </tr>
                     </thead>
                     
                  </table>
               </div>
               <div class="bs-callout bs-callout-danger">
                  <h4>Parents Info</h4>
                  <p>
                  <table class="table table-striped table-responsive ">
                  <tr>
                    <th>Name</th>
                    <td>
                      <?php 
                      $parentInfo=EmployeeParentsInfo::find()->where(['emp_id'=>$model->emp_id])->one();
                    if($parentInfo->gender== 1){echo 'Mr.';}else{echo 'Miss.';}
                     echo $parentInfo->first_name .' '.$parentInfo->middle_name .' '.$parentInfo->last_name
                       ?>
                    </td>
                    <th>CNIC</th><td>
                      <?php  if(!empty($parentInfo->cnic)){echo $parentInfo->cnic;}else{echo "N/A";} ?>
                    </td>
                    <th>Email</th><td>
                      <?php  if(!empty($parentInfo->email)){echo $parentInfo->email;}else{echo "N/A";} ?>
                    </td>
                  </tr>
                  <tr>
                    <th>Contact NO</th><td>
                      <?php  if(!empty($parentInfo->contact_no)){echo $parentInfo->contact_no;}else{echo "N/A";} ?>
                    </td>
                  </tr>
                  </table>
                  </p>
               </div>
               <div class="bs-callout bs-callout-danger">
                  <h4>Education Info</h4>
                  <table class="table table-striped table-responsive">
                  <?php 
                  $employeeEducation=EmplEducationalHistoryInfo::find()->where(['emp_id'=>$model->emp_id])->one();
                  $emply_alwnc = EmployeeAllowances::find()->where(['fk_emp_id'=>$_GET['id'],'status'=>1])->All();
                 $payrollDeduction = EmployeeDeductions::find()->select(['fk_deduction_id'])->where(['fk_emp_id'=>$_GET['id'],'status'=>1])->All();
                  ?>
                   <th>Degree Name</th>
                   <td> <?php echo $employeeEducation->degree_name;?>  </td>
                    <th>Institute Name</th>
                   <td> <?php echo $employeeEducation->Institute_name;?>  </td>
                   <th>Start Date</th>
                   <td> <?php echo $employeeEducation->start_date;?>  </td>
                   <th>End Date</th>
                   <td> <?php echo $employeeEducation->end_date;?>  </td>
                  </table>
               </div>
               <div class="bs-callout bs-callout-danger">
                  <h4>Salary Details</h4>
                  <?php 
                    $employee_payroll = EmployeePayroll::find()->where(['fk_emp_id'=>$_GET['id']])->one();

                   ?>
                  <table class="table table-striped table-responsive">
                  <tr>
                  <th>Group</th><td><?php echo $employee_payroll->fkGroup->title;?></td>
                  <th>Stage</th><td><?php echo $employee_payroll->fkPayStages->title;?></td>
                  <th>Basic Salary</th><td><?php echo $employee_payroll->fkPayStages->amount;?></td>
                  </tr>
                  </table>
                  <div class="row">
                  <div class="col-md-6">
                  <table class="table table-striped table-responsive">
                  
                  <tr>
                    <th>Total Allownces</th>
                  </tr>
                  <?php if(count($emply_alwnc) > 0){
                     $amount=0;
                     $sum=0;
                  foreach ($emply_alwnc as $pay) {

                   ?>
                  <tr>
                    <td>
        <strong><?=$pay->fkAllownces->title?></strong>: <?= $totlalwnc=$pay->fkAllownces->amount?>
                      
                    </td>
                  </tr>
                  <?php } ?>
                   <tr>
        <td><strong>Total Allownces: <?=$employee_payroll->total_allownce ?></strong></td>
        </tr>
        <?php } else{ ?>
        
        <tr>
        <td colsapn="2">N/A</td>
        </tr>
        <?php }?>

        <?php 
        if(count($employee_payroll) > 0){
        if($employee_payroll->total_allownce == '' && $employee_payroll->total_deductions == '' ){?>
           <tr>
            <th>Net Amount:<?php echo $employee_payroll->basic_salary?></th>
        </tr> 
        <?php } }?>

                  </table>
                  </div>
                  <div class="col-md-6">
                    <table class="table table-striped table-responsive">
                
                   <?php
        //print_r($payroll);
        if(count($payrollDeduction) > 0){
        
        //$amount=0;
        // $deducts=0;
        foreach ($payrollDeduction as $deduction) {
        // print_r($deduction);die;
        // $deducts+=$deduction->fkDeduction->amount;
        ?>
        
        
        <tr>
        <td><strong><?=$deduction->fkDeduction->title?></strong>: <?=$deduction->fkDeduction->amount?></td>
        
        </tr>
        
        
        <?php }?>
        
        <tr>
        <td><strong>Total Deductions:</strong> <?php if(!empty($employee_payroll->total_deductions)){
        echo $employee_payroll->total_deductions;
        }?></td>
        </tr>
        
        <tr>
          <td><strong>Net Amount: </strong><?php if(!empty($employee_payroll->total_amount)){
            echo $employee_payroll->total_amount;} ?></td>
        </tr>
        <?php } else{ ?>
        <tr>
        <strong>Total Deductions</strong>
        <br>
        <td colsapn="2">N/A</td>
        </tr>
        <?php }?>
                  </table>
                  </div>
                  </div>
                  
               </div>
               <div class="bs-callout bs-callout-danger">
                  <h4>OverAll Attendance Status</h4>
                  <ul class="list-group">
                     <div class="list-group-item" href="#">
                        
                        
                         <div id="chartContainer" style="height:280px; padding:0px !important;"></div> 
                        
                        
                        
                        
                        
                     </div>
                  </ul>
               </div>
               
            </div>

         </div>
        <!-- resume -->


    </div>

</div>
<?php $this->registerJsFile(Yii::getAlias('@web').'/js/jquery.canvasjs.min.js',['depends' => [yii\web\JqueryAsset::className()]]);?>
<?php
  $leave=count($leave);
  $absent=count($absent);
  $shortleave=count($shortleave);
  $present=count($present);
  $script = <<< JS
window.onload = function() { 
    $("#chartContainer").CanvasJSChart({ 
        /*title: { 
            text: "records of ",
            fontSize: 24
        }, */
        axisY: { 
            title: "Products in %" 
        }, 
        legend :{ 
            verticalAlign: "center", 
            horizontalAlign: "right" 
        }, 
        data: [ 
        { 
            type: "pie", 
            showInLegend: true, 
            toolTipContent: "{label} <br/> {y} %", 
            indexLabel: "{y} %", 
            dataPoints: [  
                { label: "Short Leave",    y: $shortleave, legendText: "Short Leave"  },
                { label: "leave",  y: $leave, legendText: "Leave"}, 
                { label: "Absent",y: $absent,  legendText: "Absent" }, 
                { label: "present",y: $present,  legendText: "Present"}, 
            
            ] 
        } 
        ] 
    }); 
}

JS;
$this->registerJs($script);
?>