<?php 
if (isset($_GET['year'])){?>
<style type="text/css">
  *{ margin-left:0; padding:0;}
  th, tr, td  {
    border:1px solid black;
    padding:10px;
    font-size:1.5em;
  }

  tr:nth-child(even){background-color: #f2f2f2}
</style>
<div style="width: 100%; text-align: center; background-color: #337ab7; color: #000; font-size:14px;">
  <h2 style="font-size:16px; font-weight:700; color:#000000; text-transform:capitalize;margin: 0;padding: 15px 0 8px 0;">
    <?=Yii::$app->common->getBranchDetail()->address?>
  </h2>
</div>
<h3 style='text-align:center'>Total Fee Receive Detail of the Year  <?=$year?>
</h3>
<?php }else{?>
<a href="<?=\yii\helpers\Url::to(['reports/yearly-report-pdf/','year'=>$year]) ?>" name="Generate Report" class="btn btn-primary pull-right">Generate Report</a>
<?php }
 ?>

<table class="table table-bordered" align="center">
                        <thead>
                           <tr style="background: #3c8dbc">
                            <th>SR</th>
                            <th>Student</th>
                            <th>Parent</th>
                            <th>Class</th>
                            <th>Fee Head</th>
                            <th>Amount</th>
                            <th>Transport</th>
                            <th>Hostel</th>
                            <th>Date</th>
                            </tr>
                         </thead>
                           <tbody>
                            <?php
                            $i=0;
                            $headAmnt=0; 
                            $trnsprtAmnt=0; 
                            $hstlAmnt=0; 
                            $totalAmount=0;

                            foreach ($yearSql as $key => $currentYear) {
                                $i++;
                             $headAmnt=$headAmnt+$currentYear['head_recv_amount'];
                             $trnsprtAmnt=$trnsprtAmnt+$currentYear['transport_amount'];
                             $hstlAmnt=$hstlAmnt+$currentYear['hostel_amount'];
                             $totalAmount=$totalAmount+$currentYear['head_recv_amount']+$currentYear['transport_amount']+$currentYear['hostel_amount'];
                             $getHead=\app\models\FeeHead::find()->where(['branch_id'=>yii::$app->common->getBranch(),'id'=>$currentYear['fee_head_id']])->one();
                             $studentInfo=\app\models\StudentInfo::find()->where(['stu_id'=>$currentYear['stu_id']])->one();
                              ?>
                            <tr>
                              <td><?=$i; ?></td>
                              <td><?= yii::$app->common->getName($studentInfo->user_id)?></td>
                              <td><?= Yii::$app->common->getParentName($studentInfo->stu_id)?></td>
                              <td><?=$studentInfo->class->title ?></td>
                               <td><?=strtoupper($getHead->title) ?> </td>
                               <td>Rs <?=$currentYear['head_recv_amount'] ?> </td>
                               <td>Rs <?=$currentYear['transport_amount'] ?> </td>
                               <td>Rs <?=$currentYear['hostel_amount'] ?> </td>
                               <td><?=date('M Y',strtotime($currentYear['recv_date'])) ?> </td>
                            </tr>
                            <?php

                            } ?>
                            <tr>
                              <td></td>
                              <th>Total</th>
                              <td></td>
                              <td></td>
                              <td></td>
                              <th>Rs. <?=$headAmnt ?></th>
                              <th>Rs. <?=$trnsprtAmnt ?></th>
                              <th>Rs. <?=$hstlAmnt ?></th>
                              <td></td>
                            </tr>
                            <tr>
                              <td></td>
                              <th>Grand Total</th>
                              <td></td>
                              <td></td>
                              <td></td>
                              <th>Rs. <?=$totalAmount ?></th>
                              <th></th>
                              <th></th>
                              <th></th>
                            </tr>
                          </tbody>
                          </table>