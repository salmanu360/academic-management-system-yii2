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
$this->registerCssFile(Yii::getAlias('@web').'/css/profile.css',['depends' => [yii\web\JqueryAsset::className()]]);

$this->title='Profile of '.Yii::$app->common->getName($model->user_id);
//$this->params['breadcrumbs'][] = ['label' => 'Employee Info', 'url' => ['index']];
//$this->params['breadcrumbs'][] = $this->title; 

?>


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
                           $file_name = $model->user->Image;
                            $file_path = Yii::getAlias('@webroot').'/uploads/';

                            if(!empty($file_name) && file_exists($file_path.$file_name)) {
                                $web_path = Yii::getAlias('@web').'/uploads/';
                                $imageName = $model->user->Image;

                            }else{
                                $web_path = Yii::getAlias('@web').'/img/';
                                if($model->gender_type == 1){
                                    $imageName = 'male.jpg';
                                }else{
                                    $imageName = 'female.png';

                                }
                            }
       /*if(!empty($model->user->Image)){
       echo $img= '<img style="height: 208px;
    width: 200px;" class="img-circle img-responsive" src="'.Yii::$app->request->BaseUrl.'/uploads/'.$model->user->Image.'">';
        }*/
       ?>
       <img class="img-circle img-responsive" style="height: 208px;
    width: 200px;" src="<?= $web_path.$imageName?>" alt="<?=Yii::$app->common->getName($model->user->id);?>">
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
                     <th>Registeration No.</th><td><?php echo $model->user->username ?></td>
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
                    <th>Role</th><td><?php 
                    if($model->user->fk_role_id == 1){
                      echo 'Administrator';
                    }else if($model->user->fk_role_id == 4){
                      echo 'Teacher';
                    }else if($model->user->fk_role_id == 5){
                      echo 'Accountant';
                    }else if($model->user->fk_role_id == 6){
                      echo 'Librarian';
                    }

                    ?></td>
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
                   <td> <?php if(!empty($employeeEducation->degree_name)){echo $employeeEducation->degree_name;}else{echo "N/A";}?>  </td>
                    <th>Institute Name</th>
                   <td> <?php if(!empty($employeeEducation->Institute_name)){echo $employeeEducation->Institute_name;}else{echo "N/A";}?>  </td>
                   <th>Start Date</th>
                   <td> <?php if(!empty($employeeEducation->start_date)){echo $employeeEducation->start_date;}else{echo "N/A";}?>  </td>
                   <th>End Date</th>
                   <td> <?php if(!empty($employeeEducation->end_date)){echo $employeeEducation->end_date;}else{echo "N/A";}?>  </td>
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
               <div class="well" style="background: white;border: none;">
               

<!-- <button type="button" name="Generate Report" id="generate-employee-profile-pdf" class="btn btn-primary pull-right" value="Generate Pdf" data-id="<?php //echo $model->emp_id;?>" data-url="<?php //echo Url::to(['/employee/view-profile-pdf'])?>">
            <i class="fa fa-download"></i> Generate PDF
          </button> -->
       <!-- <button style="margin-left:10px;" type="button" name="Generate Report" id="print" class="btn btn-info pull-right" value="Generate Pdf"> <i class="fa fa-download"></i> Generate PDF</button> -->
       <a href="<?= Url::to(['/employee']) ?>" class="btn btn-warning pull-right" style="margin-left: 10px">Back</a> 
       <?php if(Yii::$app->user->identity->fk_role_id == 1){ ?>
    <?php echo Html::a('Update', ['update', 'id' => $model->emp_id], ['class' => 'btn btn-primary pull-right update']); }?> 


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


$("#print").click(function () {
    //Hide all other elements other than printarea.
    $(this).hide();
    $('.update').hide();
    window.print();
}); newWin.document.close();

JS;
$this->registerJs($script);
?>