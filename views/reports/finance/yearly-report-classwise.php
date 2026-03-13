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
<h3 style='text-align:center'>Fee Ledger Of  <?= yii::$app->common->getName($studentTable->user_id)  ?> <?= ($studentTable->gender_type== '0')? 'D/O' : 'S/O' ?> <?= Yii::$app->common->getParentName($studentTable->stu_id).' in '.$year;?>
</h3>
<?php }else{?>
<a href="<?=\yii\helpers\Url::to(['reports/yearly-report-classwise-pdf/','year'=>$year,'student_id'=>$student_id]) ?>" name="Generate Report" class="btn btn-primary pull-right"><i class="fa fa-download"></i> Generate Report</a>
<h4 style="color: green;font-weight: bold;">
  <?php $studentInfo=\app\models\StudentInfo::find()->where(['stu_id'=>$studentTable->stu_id])->one();
echo yii::$app->common->getName($studentInfo->user_id) .' S/D/O ';
echo Yii::$app->common->getParentName($studentInfo->stu_id) .' Class ';
echo $studentInfo->class->title; ?></h4>
<?php }
 ?>

<table class="table table-bordered" align="center">
                        <thead>
                           <tr style="background: #3c8dbc">
                            <th>SR</th>
                            <th>Roll #</th>
                            <th>Fee Head</th>
                            <th>Amount</th>
                            <th>Arrears</th>
                            <th>Transport</th>
                            <th>Transport Arrears</th>
                            <th>Hostel</th>
                            <th>Hostel Arrears</th>

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
                            $transportArrears=0;
                            $hostelArrears=0;
                            $headAmntArrears=0;
                            $rollNoAlotment=0;
                            foreach ($yearSql as $key => $currentYear) { 
                                $i++;
                                $year_month = $year.'-'.date('m',strtotime($currentYear['from_date'])); 
                                $where="from_date like '".$year_month."%' and fee_head_id=".$currentYear->fee_head_id." and stu_id=".intval($currentYear->stu_id);
                              $fee_arrears_rcv=\app\models\FeeArrearsRcv::find()->where($where)->sum('amount');
                             $headAmnt=$headAmnt+$currentYear['head_recv_amount']+$fee_arrears_rcv;
                             $trnsprtAmnt=$trnsprtAmnt+$currentYear['transport_amount'];
                             $transportArrears=$transportArrears+$currentYear['transport_arrears'];
                             $hstlAmnt=$hstlAmnt+$currentYear['hostel_amount'];
                             $hostelArrears=$hostelArrears+$currentYear['hostel_arrears'];
                             $totalAmount=$totalAmount+$currentYear['head_recv_amount']+$currentYear['transport_amount']+$currentYear['hostel_amount']+ $fee_arrears_rcv;
                             $getHead=\app\models\FeeHead::find()->where(['branch_id'=>yii::$app->common->getBranch(),'id'=>$currentYear['fee_head_id']])->one();
                             $studentInfo=\app\models\StudentInfo::find()->where(['stu_id'=>$currentYear['stu_id']])->one();
                             $userTable=\app\models\User::find()->select(['username'])->where(['id'=>$studentInfo->user_id])->one();
                            
                            $whereArrears="from_date like '".$year_month."%' and fee_head_id=".$currentYear->fee_head_id." and status=1 and stu_id=".$currentYear['stu_id'];
                             $feeArrears=\app\models\FeeArears::find()->where($whereArrears)->one();
                             /*$feeArrears=\app\models\FeeArears::find()->where(['stu_id'=>$currentYear['stu_id'],'fee_head_id'=>$currentYear->fee_head_id,'status'=>1])->one();*/

                             $headAmntArrears=$headAmntArrears+$feeArrears['arears'];
                              ?>
                            <tr>
                               <?php if (empty($rollNoAlotment)) {?>
                              <td><?=$i; ?></td>
                              <td><?= (!empty($studentInfo->roll_no))?$studentInfo->roll_no:'N/A' ?></td>
                              <?php }else if($rollNoAlotment != $userTable->username){ ?>
                              <td><?=$i; ?></td>
                               <td><?= (!empty($studentInfo->roll_no))?$studentInfo->roll_no:'N/A' ?></td>
                               <?php }else{ ?>
                               <td colspan="2"></td>
                               <?php } ?>
                               <td><?=strtoupper($getHead->title) ?> </td>
                               <td>Rs <?=$currentYear['head_recv_amount']+$fee_arrears_rcv ?> </td>
                               <td>Rs. <?= (count($feeArrears['arears'])>0)? $feeArrears['arears'] : '0'; ?></td>
                               <td>Rs <?=$currentYear['transport_amount'] ?> </td>
                               <td>Rs <?=$currentYear['transport_arrears'] ?> </td>
                               <td>Rs <?=$currentYear['hostel_arrears'] ?> </td>
                               <td>Rs <?=$currentYear['hostel_amount'] ?> </td>
                               <td><?=date('M Y',strtotime($currentYear['from_date'])) ?> </td>
                            </tr>
                            <?php
                            $rollNoAlotment=$userTable->username;
                            } ?>
                            
                            <tr>
                              <th colspan="2">Total</th>
                              <td></td>
                              <th>Rs. <?=$headAmnt ?></th>
                              <th>Rs. <?=$headAmntArrears ?></th>
                              <th>Rs. <?=$trnsprtAmnt ?></th>
                              <th>Rs. <?=$transportArrears ?></th>
                              <th>Rs. <?=$hostelArrears ?></th>
                              <th>Rs. <?=$hstlAmnt ?></th>
                              <td></td>
                            </tr>
                            <tr>
                              <th colspan="2">Grand Total</th>
                              <td></td>
                              <th>Rs. <?=$totalAmount ?></th>
                              <th></th>
                              <th></th>
                              <th></th>
                              <th></th>
                              <th></th>
                              <th></th>
                            </tr>
                          </tbody>
                          </table>