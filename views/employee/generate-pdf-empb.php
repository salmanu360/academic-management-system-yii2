<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use app\models\EmplEducationalHistoryInfo;
use app\models\EmployeeParentsInfo;
$this->registerCss(" 
 
@media print{    
 
    .footer{
        display:none;
    }
 }

");
?>
<style type="text/css">
  *{ margin:0; padding:0;}
  th, tr, td  {
    border:0.1px solid black;
    padding:6px;
    font-size:0.8em;
  }

  /*tr:nth-child(even){background-color: #f2f2f2}*/
  table{width: 100%}
</style>
<div style="width: 100%; text-align: center; color: #000; font-size:14px;">
  <h2 style="font-size:16px; font-weight:700; color:#000000; text-transform:capitalize;margin: 0;padding: 15px 0 8px 0;">
   <u> <?=Yii::$app->common->getBranchDetail()->address?></u>
  </h2>
</div>
<div class="employee-generate-pdf-biometirc">
  <?php if($id==1){?>
    <h3 style="text-align: center;margin-bottom: 20px;">Inactive Employees Details</h3>
  <?php }else{?>
    <h3 style="text-align: center;margin-bottom: 20px;">Employees Details</h3>
  <?php } ?>
    <div class="table-responsive">
        <table>
            <thead>
            <tr>
                <th>#</th>
                <th>Registration No.</th>
                <th>Name</th>
                <th>Parent Name</th>
                <th>DOB</th>
                <th>Hire Date</th>
                <th>Contact #</th>
                <th>CNIC</th>
                <th>Designation</th>
                <th>Biometric ID</th>
            </tr>
            </thead>
            <tbody>
            <?php  $i=1;
            foreach ($query as $items){
                 $prntName=EmployeeParentsInfo::find()->where(['emp_id'=>$items['emp_id']])->one();
                 $designation=\app\models\RefDesignation::find()->where(['designation_id'=>$items['designation_id']])->one();
                $name= '';
                if($items['first_name']){
                    $name .=$items['first_name'];
                }
                if($items['middle_name']){
                    $name .= ' '.$items['middle_name'];
                }
                if($items['last_name']){
                    $name .= ' '.$items['last_name'];
                }
                ?>
                <tr>
                    <td><?=$i?></td>
                    <td><?php echo Yii::$app->common->getUserName($items['user_id']);
                    ?></td>
                    <td><?=$name?></td>
                    <td><?php echo $prntName->first_name .' '. $prntName->last_name; ?></td>
                    <td><?=$items['dob']?></td>
                    <td><?=$items['hire_date']?></td>
                    <td><?=$items['contact_no']?></td>
                    <td><?=$items['cnic']?></td>
                    <td><?=$designation['Title']?></td>
                    <td><?=$items['id']?></td>
                </tr>
                <?php
                $i++;
            }
            ?>
            </tbody>
        </table>
</div>