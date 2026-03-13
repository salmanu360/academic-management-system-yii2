<?php 
use yii\helpers\Url;
?>
<title>Today Ledger</title>
<div class="panel panel-default panel-body">
<div class="row">
  <div class="col-md-12">
    <strong style="color:red">Today Ledger</strong>
    <a style="margin-left: 5px;" class="btn btn-danger pull-right" href="<?php echo Url::to(['accounts']) ?>">Back</a>
    <a href="<?=Url::to(['reports/today-all-ledger']) ?>" name="Generate Report" class="btn btn-primary pull-right"><i class="fa fa-download"></i> Generate Report</a>
  </div></div>
  <div class="row">
    <div class="col-md-12"> 
      <div class="table-responsive">
      <table class="table table-bordered">
        <thead>
          <tr style="background: #3c8dbc">
            <th>SR.</th>
            <th>Student</th>
            <th>Parent</th>
            <th>Class</th>
            <th>Roll #</th>
            <th>Fee Title</th>
            <th>Fee</th>
            <th>Transport</th>
            <th>Hostel</th>
          </tr>
        </thead>
        <tbody>
          <?php  
          $i=0;
          $total_amount=0;
          $totaltransportAmnt=0;
          $totalhostelAmnt=0;
          foreach ($todayFeeRcv as $key => $todayFee_rcv) {
            $i++;
            $getHead=\app\models\FeeHead::find()->where(['branch_id'=>yii::$app->common->getBranch(),'id'=>$todayFee_rcv['fee_head_id']])->one();
            $total_amount=$total_amount+$todayFee_rcv['head_recv_amount'];
            $totaltransportAmnt=$totaltransportAmnt+$todayFee_rcv['transport_amount'];
            $totalhostelAmnt=$totalhostelAmnt+$todayFee_rcv['hostel_amount'];
            ?>
            <tr>
              <td><?php echo $i; ?></td>
              <td><?= yii::$app->common->getName($todayFee_rcv['user_id']); ?></td>
              <td><?= yii::$app->common->getParentName($todayFee_rcv['stu_id']); ?></td>
              <td><?= yii::$app->common->getCGSName($todayFee_rcv['class_id'],$todayFee_rcv['group_id'],$todayFee_rcv['section_id']); ?></td>
              <td><?= (!empty($todayFee_rcv['roll_no']))? $todayFee_rcv['roll_no'] :'N/A'; ?></td>
              <td><?php echo strtoupper($getHead['title']) ?></td>
              <td>Rs. <?php echo $todayFee_rcv['head_recv_amount']?></td>
              <td>Rs. <?php echo ($todayFee_rcv['transport_amount'])?$todayFee_rcv['transport_amount']:'0'?></td>
              <td>Rs. <?php echo ($todayFee_rcv['hostel_amount'])?$todayFee_rcv['hostel_amount']:'0'?></td>
            </tr>
          <?php } ?>
          <tr>
            <td></td>
            <th colspan="5">Total</th>
            <th>Rs. <?php echo $total_amount ?></th>
            <th>Rs. <?php echo $totaltransportAmnt ?></th>
            <th>Rs. <?php echo $totalhostelAmnt ?></th>
          </tr>
          <tr>
            <td></td>
            <th colspan="5">Grand Total</th>
            <th>Rs. <?php echo $total_amount+$totaltransportAmnt+$totalhostelAmnt?></th>
          </tr>
        </tbody>
      </table>
    </div>
    </div>
  </div>
  </div>