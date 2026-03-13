<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use app\models\EmplEducationalHistoryInfo;
use app\models\EmployeeParentsInfo;
use app\models\RefSession;
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
    <?php  $sessionName=RefSession::find()->where(['session_id'=>$session_id])->one(); ?>
    <h3 style="text-align: center;margin-bottom: 20px;"><?php if($is_active ==0){ echo 'Alumni';}else{echo 'Active';} ?> List of Class <?=Yii::$app->common->getCGSName($class_id,$group_id,$section_id)?> (Session: <?php echo $sessionName['title'] ?>) </h3>
 
    <div class="table-responsive">
        <table>
            <thead>
            <tr>
                <th>#</th>
                <th>Reg. No</th>
                <th>Name</th>
                <th>Father</th>
                <th>Father CNIC</th>
                <th>Father Contact No</th>
                <th>Dob</th>
            </tr>
            </thead>
            <tbody>
            <?php  $i=1;
            foreach ($student_details_alumni as $items){
                 $parentDetail=Yii::$app->common->getParent($items->stu_id);
                ?>
                <tr>
                    <td><?=$i?></td>
                    <td><?php echo Yii::$app->common->getUserName($items['user_id']);?></td>
                    <td><?= Yii::$app->common->getName($items->user_id)?></td>
                    <td><?php echo Yii::$app->common->getParentName($items->stu_id); ?></td>
                    <td><?php if($parentDetail){ echo $parentDetail->cnic;}else{ echo 'N/A';}?></td>
                    <td><?php if($parentDetail){echo $parentDetail->contact_no;}else{ echo 'N/A';}?></td>
                    <td><?=$items['dob']?></td>
                </tr>
                <?php
                $i++;
            }
            ?>
            </tbody>
        </table>
</div>