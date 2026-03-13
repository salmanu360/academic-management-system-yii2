<?php 
use app\models\FeeSubmission;
if (isset($_GET['user_id'])){?>
<style type="text/css">
*{ margin-left:0; padding:0;}
th, tr, td  {
    border:1px solid black;
    padding:10px;
    font-size:1.5em;
}

tr:nth-child(even){background-color: #f2f2f2}
tr.noBorder td{

    border: 0;
}
</style>
<div style="width: 100%; text-align: center; background-color: #337ab7; color: #000; font-size:14px;">
  <h2 style="font-size:16px; font-weight:700; color:#000000; text-transform:capitalize;margin: 0;padding: 15px 0 8px 0;">
    <?=Yii::$app->common->getBranchDetail()->address?>
</h2>
</div>
<?php $studentInfo=\app\models\StudentInfo::find()->where(['stu_id'=>$studentTable->stu_id])->one(); ?>
<h3 style='text-align:center'>Fee Slip  of <?= yii::$app->common->getName($studentTable->user_id)  ?> <?= ($studentTable->gender_type== '0')? 'D/O' : 'S/O' ?> <?= Yii::$app->common->getParentName($studentTable->stu_id).' (Class '.$studentInfo->class->title .')';?>
</h3>
<?php }else{?>
<a href="<?=\yii\helpers\Url::to(['reports/previous-slip/','user_id'=>$user_id]) ?>" name="Generate Report" class="btn btn-primary pull-right"><i class="fa fa-download"></i> Generate Report</a>
<h4 style="font-weight: bold; color: green"><?php echo yii::$app->common->getName($user_id) .' S/D/O ';
echo Yii::$app->common->getParentName($studentTable->stu_id) .' Class ';
echo $studentTable->class->title; ?></h4>
<?php } ?>
<table class="table table-bordered" align="center">
    <thead>
     <tr style="background:#D3D3D3">
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
    $i=1;
    $headAmnt=0; 
    $trnsprtAmnt=0; 
    $transportArrears=0;
    $hstlAmnt=0; 
    $hostelArrears=0;
    $headAmntArrears=0;

    foreach ($feeSubmission as $previousSlipValue) {
        $getHead=\app\models\FeeHead::find()->where(['branch_id'=>yii::$app->common->getBranch(),'id'=>$previousSlipValue->fee_head_id])->one();

        $where="created_date like '".$previousSlipValue->recv_date."%' and stu_id=".intval($previousSlipValue->stu_id);
        $fee_arrears_rcv=\app\models\FeeArrearsRcv::find()->where($where)->sum('amount');

        $headAmnt=$headAmnt+$previousSlipValue->head_recv_amount+$fee_arrears_rcv;
        $trnsprtAmnt=$trnsprtAmnt+$previousSlipValue->transport_amount;
        $transportArrears=$transportArrears+$previousSlipValue->transport_arrears;
        $hstlAmnt=$hstlAmnt+$previousSlipValue->hostel_amount;
        $hostelArrears=$hostelArrears+$previousSlipValue->hostel_arrears;
        $feeArrears=\app\models\FeeArears::find()->where(['stu_id'=>$previousSlipValue->stu_id,'fee_head_id'=>$previousSlipValue->fee_head_id,'status'=>1])->one();
        $headAmntArrears=$headAmntArrears+$feeArrears['arears'];
        ?>
        <tr>
            <td><?php echo $i; ?></td>
            <td><?= (!empty($studentTable->roll_no))?$studentTable->roll_no:'N/A'; ?></td>
            <td><?= strtoupper($getHead->title); ?></td>
            <td>Rs. <?= $previousSlipValue->head_recv_amount+$fee_arrears_rcv; ?></td>
            <td>Rs <?= (count($feeArrears['arears'])>0)? $feeArrears['arears'] : '0'; ?></td>
            <td>Rs.<?= $previousSlipValue->transport_amount; ?></td>
            <td>Rs. <?= $previousSlipValue->transport_arrears; ?></td>
            <td>Rs. <?= $previousSlipValue->hostel_amount; ?></td>
            <td>Rs. <?= $previousSlipValue->hostel_arrears; ?></td>
            <td><?= date('d M Y',strtotime($previousSlipValue->recv_date)); ?></td>
        </tr>
        <?php 
        $i++;
    }
    ?>
    <tr>
       <td></td>
       <th>Total</th>
       <td></td>
       <th>Rs. <?php echo $headAmnt; ?></th>
       <th>Rs. <?php echo $headAmntArrears; ?></th>
       <th>Rs. <?php echo $trnsprtAmnt; ?></th>
       <th>Rs. <?php echo $transportArrears; ?></th>
       <th>Rs. <?php echo $hstlAmnt; ?></th>
       <th>Rs. <?php echo $hostelArrears; ?></th>
       <td></td>
   </tr>
   <tr class="noBorder">
       <td></td>
       <td></td>
       <td></td>
       <td></td>
       <td></td>
       <td></td>
       <td></td>
       <td></td>
       <td></td>
       <td></td>
   </tr>
   <tr class="success">
       <th colspan="3">Total Recieve</th>
       <th colspan="2">Rs. <?php echo $headAmnt+$trnsprtAmnt+$hstlAmnt; ?></th>
       <th></th>
       <th colspan="2">Total Arrears</th>
       <th colspan="2">Rs. <?php echo $headAmntArrears+$transportArrears+$hostelArrears; ?></th>
   </tr>
</tbody>
</table>