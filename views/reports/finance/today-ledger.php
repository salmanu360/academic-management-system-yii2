<?php
use yii\helpers\Url;
use app\models\FeeSubmission;
$getClass=\app\models\RefClass::find()->where(['fk_branch_id'=>Yii::$app->common->getBranch(),'status'=>'active','class_id'=>$class_id])->one();
if (isset($_GET['class_id'])){?>
<style type="text/css">
  *{ margin-left:0; padding:0;}
  th, tr, td  {
    border:1px solid black;
    padding:10px;
    font-size:1.5em;
  }
  /*tr.noBorder td {
  border: 0;
  border:1px;
}tr.noBorder th {
  border: 0;

}*/

  tr:nth-child(even){background-color: #f2f2f2}
</style>
<div style="width: 100%; text-align: center; background-color: #337ab7; color: #000; font-size:14px;">
  <h2 style="font-size:16px; font-weight:700; color:#000000; text-transform:capitalize;margin: 0;padding: 15px 0 8px 0;">
    <?=Yii::$app->common->getBranchDetail()->address?>
  </h2>
</div>
<h3 style="text-align: center;">Fee ledger of <?php echo $getClass->title .'('.date('d M Y'); ?>)</h3>

<?php }else{
  ?>
<a href="<?=Url::to(['reports/today-class-ledger/','class_id'=>$class_id]) ?>" name="Generate Report" class="btn btn-primary pull-right"><i class="fa fa-download"></i> Generate Report</a>
<?php } ?>
    <table class="table table-bordered" align="center">
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
       <tbody>
        <?php 
         $i=1;
        $totalHeadAmountGrand=0;
         $totalArrearsGrand=0;
         $totalTransportGrand=0;
         $totalTransportArrearsGrand=0;
         $totalHostelGrand=0;
         $totalHostelArrearsGrand=0;
        foreach ($getClassStudents as $key => $studentsClass) { 
          $where="from_date like '".date('Y-m')."%' and stu_id=".intval($studentsClass->stu_id);
          $todayLedgerQuery=FeeSubmission::find()->where($where)->all(); 
          $studentInfo=\app\models\StudentInfo::find()->where(['stu_id'=>$studentsClass->stu_id])->one();
          $userTable=\app\models\User::find()->select(['username'])->where(['id'=>$studentInfo->user_id])->one();
           if(count($todayLedgerQuery)>0){
            $rollNoAlotment=0;
            $totalHeadAmount=0;
            $totalArrears=0;
            $totalTransport=0;
            $totalTransportArrears=0;
            $totalHostel=0;
            $totalHostelArrears=0;
          foreach ($todayLedgerQuery as $key => $todayLedgerQueryValue) {
            $getHead=\app\models\FeeHead::find()->where(['branch_id'=>yii::$app->common->getBranch(),'id'=>$todayLedgerQueryValue['fee_head_id']])->one();
            $feeArrears=\app\models\FeeArears::find()->where(['stu_id'=>$todayLedgerQueryValue->stu_id,'fee_head_id'=>$getHead->id,'status'=>1])->one();

            $fee_arrears_rcv=\app\models\FeeArrearsRcv::find()->where(['stu_id'=>$todayLedgerQueryValue->stu_id,'date(created_date)'=>date('Y-m-d')])->sum('amount');

            $totalHeadAmount=$totalHeadAmount+$todayLedgerQueryValue['head_recv_amount']+ $fee_arrears_rcv;
            $totalHeadAmountGrand=$totalHeadAmountGrand+$todayLedgerQueryValue['head_recv_amount'] + $fee_arrears_rcv;
            $totalArrears=$totalArrears+$feeArrears['arears'];
            $totalArrearsGrand=$totalArrearsGrand+$feeArrears['arears'];
            $totalTransport=$totalTransport+$todayLedgerQueryValue['transport_amount'];
            $totalTransportGrand=$totalTransportGrand+$todayLedgerQueryValue['transport_amount'];
            $totalTransportArrears=$totalTransportArrears+$todayLedgerQueryValue['transport_arrears'];
            $totalTransportArrearsGrand=$totalTransportArrearsGrand+$todayLedgerQueryValue['transport_arrears'];
            $totalHostel=$totalHostel+$todayLedgerQueryValue['hostel_amount'];
            $totalHostelGrand=$totalHostelGrand+$todayLedgerQueryValue['hostel_amount'];
            $totalHostelArrears=$totalHostelArrears+$todayLedgerQueryValue['hostel_arrears'];
            $totalHostelArrearsGrand=$totalHostelArrearsGrand+$todayLedgerQueryValue['hostel_arrears'];
            //echo '<pre>';print_r($feeArrears);?>
        <tr>
           <?php if (empty($rollNoAlotment)) {?>
            <td><?= $i; ?></td>
            <td><?= yii::$app->common->getName($studentInfo->user_id); ?></td>
            <td><?= (!empty($studentInfo->roll_no))? $studentInfo->roll_no :'N/A'; ?></td>
            <?php  $i++; }else if($rollNoAlotment != $userTable->username){
                $rollNoAlotment = $userTable->username;?>
            <td><?= $i; ?></td>
            <td><?= yii::$app->common->getName($studentInfo->user_id); ?></td>
            <td><?= (!empty($studentInfo->roll_no))? $studentInfo->roll_no :'N/A'; ?></td>
            <?php  $i++; }else{?>
            <td colspan="3"></td>
            <?php } ?>
            <td><?= strtoupper($getHead->title); ?></td>
            <td>Rs. <?= $todayLedgerQueryValue['head_recv_amount'] + $fee_arrears_rcv; ?></td>
            <td>Rs. <?= (count($feeArrears['arears'])>0)? $feeArrears['arears'] : '0'; ?></td>
            <td>Rs. <?= $todayLedgerQueryValue['transport_amount']; ?></td>
            <td>Rs. <?= $todayLedgerQueryValue['transport_arrears']; ?></td>
            <td>Rs. <?= $todayLedgerQueryValue['hostel_amount']; ?></td>
            <td>Rs. <?= $todayLedgerQueryValue['hostel_arrears']; ?></td>
            <td><?=date('M Y',strtotime($todayLedgerQueryValue['recv_date'])) ?></td>
        </tr>
            <?php $rollNoAlotment=$userTable->username;}?>
                <tr class="success noBorder">
                    <th colspan="3">Total</th>
                    <td></td>
                    <th>Rs. <?php echo $totalHeadAmount; ?></th>
                    <th>Rs. <?php echo $totalArrears; ?></th>
                    <th>Rs. <?php echo $totalTransport; ?></th>
                    <th>Rs. <?php echo $totalTransportArrears; ?></th>
                    <th>Rs. <?php echo $totalHostel; ?></th>
                    <th>Rs. <?php echo $totalHostelArrears; ?></th>
                    <td></td>
                </tr>
                <!-- <tr class="success noBorder">
                    <td></td>
                    <th colspan="2">Total Rcv</th>
                    <th>Rs. <?//= $totalHeadAmount+$totalTransport+$totalHostel; ?></th>
                    <th> Total Arrears</th>
                    <th> Rs. <?php // echo $totalArrears+$totalTransportArrears+$totalHostelArrears?> </th>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr> -->
                <?php }?>
                <?php } ?>
               
                
                <tr class="success noBorder">
                    <th colspan="3">Grand Total</th>
                    <td></td>
                    <th>Rs. <?= $totalHeadAmountGrand; ?></th>
                    <th>Rs. <?= $totalArrearsGrand; ?> </th>
                    <th>Rs. <?= $totalTransportGrand; ?></th>
                    <th>Rs. <?= $totalTransportArrearsGrand; ?></th>
                    <th>Rs. <?= $totalHostelGrand; ?></th>
                    <th>Rs. <?= $totalHostelArrearsGrand; ?></th>
                    <td></td>
                </tr>
       </tbody>
   </table>