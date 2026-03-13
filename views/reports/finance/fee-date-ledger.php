<?php 

?>
<?php 
if (isset($_GET['startcnvrt'])){?>
<style type="text/css">
  *{ margin-left:0; padding:0;}
  th, tr, td  {
    border:0.6px solid black;
    padding:7px;
    font-size:0.9em;
  }

  tr:nth-child(even){background-color: #f2f2f2}
</style>
<div style="width: 100%; text-align: center; background-color: #337ab7; color: #000; font-size:14px;">
  <h2 style="font-size:16px; font-weight:700; color:#000000; text-transform:capitalize;margin: 0;padding: 15px 0 8px 0;">
    <?=Yii::$app->common->getBranchDetail()->address?>
  </h2>
</div>
<h3 style='text-align:center'>Fee Ledger from <?= date('d M Y',strtotime($startcnvrt)) .' - '. date('d M Y',strtotime($endcnvrt));?> of <?php echo Yii::$app->common->getCGSName($class_id,$group_id,$section_id) ?>
</h3>
<?php }else{?>
<a href="<?=\yii\helpers\Url::to(['reports/date-fees/','startcnvrt'=>$startcnvrt,'endcnvrt'=>$endcnvrt,'class_id'=>$class_id,'group_id'=>$group_id,'section_id'=>$section_id]) ?>" name="Generate Report" class="btn btn-primary pull-right"><i class="fa fa-download"></i> Generate Report</a>
<?php } ?>
<table class="table table-bordered custorder2" style="padding-top: -10px">
    <thead>
     <tr style="background:#D3D3D3">
        <th>SR</th>
        <th>Student</th>
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
<tbody class="bodyContent">
    <?php //echo '<pre>';print_r($dateFee);
    $i=0;
    $headAmnt=0; 
    $trnsprtAmnt=0; 
    $transportArrears=0;
    $hstlAmnt=0; 
    $hostelArrears=0;
    $headAmntArrears=0;
    $rollNoAlotment=0;
    //echo '<pre>';print_r($dateFee);die;
    ini_set('max_execution_time', 300);
    foreach ($dateFee as $dateFeevalue) {
        $i++;
        $studentTable=\app\models\StudentInfo::find()->where(['stu_id'=>$dateFeevalue['stu_id']])->orderBy(['roll_no'=>SORT_ASC])->one();
        /*head details*/
        $getHead=\app\models\FeeHead::find()->where(['branch_id'=>yii::$app->common->getBranch(),'id'=>$dateFeevalue['fee_head_id']])->one();
        /*fee arrears*/
         $where="from_date >= '".date('Y-m-01',strtotime($startcnvrt))."' and from_date <= '".date('Y-m-t',strtotime($endcnvrt))."' and fee_head_id=".$dateFeevalue['fee_head_id']." and status = 1 and  stu_id=".intval($dateFeevalue['stu_id']);
        $feeArrears=\app\models\FeeArears::find()->where($where)->one();
        /*fee arear receive*/
        $whereRcve="from_date >= '".date('Y-m-01',strtotime($startcnvrt))."' and from_date <= '".date('Y-m-t',strtotime($endcnvrt))."' and fee_head_id=".$dateFeevalue['fee_head_id']."  and  stu_id=".intval($dateFeevalue['stu_id']);
           $fee_arrears_rcv=\app\models\FeeArrearsRcv::find()->where($whereRcve)->sum('amount');

        $userTable=\app\models\User::find()->select(['username'])->where(['id'=>$studentTable->user_id])->one();

        $headAmnt=$headAmnt+$dateFeevalue['head_recv_amount']+$fee_arrears_rcv;;
        $trnsprtAmnt=$trnsprtAmnt+$dateFeevalue['transport_amount'];
        $transportArrears=$transportArrears+$dateFeevalue['transport_arrears'];
        $hstlAmnt=$hstlAmnt+$dateFeevalue['hostel_amount'];
        $hostelArrears=$hostelArrears+$dateFeevalue['hostel_arrears'];
        $headAmntArrears=$headAmntArrears+$feeArrears['arears'];
        ?>
         
    <tr>
        <?php if (empty($rollNoAlotment)) {?>
            <td><?php echo $i; ?></td>
            <td><?= yii::$app->common->getName($studentTable->user_id)?></td>
            <td><?= (!empty($studentTable->roll_no))?$studentTable->roll_no:'N/A'; ?></td>
            <?php }else if($rollNoAlotment != $userTable->username){ ?>
            <td><?php echo $i; ?></td>
            <td><?= yii::$app->common->getName($studentTable->user_id)?></td>
            <td><?= (!empty($studentTable->roll_no))?$studentTable->roll_no:'N/A'; ?></td>
            <?php }else{ ?>
            <td><?php echo $i; ?></td>
             <td colspan="2"></td>
             <?php } ?>
            <td><?= strtoupper($getHead->title); ?></td>
            <td>Rs. <?= $dateFeevalue['head_recv_amount']+$fee_arrears_rcv; ?></td>
            <td>Rs <?= (count($feeArrears['arears'])>0)? $feeArrears['arears'] : '0'; ?></td>
            <td>Rs.<?= $dateFeevalue['transport_amount']; ?></td>
            <td>Rs. <?= $dateFeevalue['transport_arrears']; ?></td>
            <td>Rs. <?= $dateFeevalue['hostel_amount']; ?></td>
            <td>Rs. <?= $dateFeevalue['hostel_arrears']; ?></td>
            <td><?= date('d M Y',strtotime($dateFeevalue['recv_date'])); ?></td>
    </tr>
    <?php 
    
    $rollNoAlotment=$userTable->username;

}
     ?>
     <tr>
       <th colspan="4">Total</th>
       
       <th>Rs. <?php echo $headAmnt; ?></th>
       <th>Rs. <?php echo $headAmntArrears; ?></th>
       <th>Rs. <?php echo $trnsprtAmnt; ?></th>
       <th>Rs. <?php echo $transportArrears; ?></th>
       <th>Rs. <?php echo $hstlAmnt; ?></th>
       <th>Rs. <?php echo $hostelArrears; ?></th>
       <td></td>
   </tr>
   <tr>
       <th colspan="4">Grand Total</th>
       <th colspan="4">Total Fee: Rs. <?php echo $headAmnt+$trnsprtAmnt+$hstlAmnt; ?></th>
       <th colspan="3">Total Arrears: Rs. <?php echo $headAmntArrears+$transportArrears+$hostelArrears; ?></th>
   </tr>

</tbody>
</table>
