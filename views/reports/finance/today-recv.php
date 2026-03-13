<?php use yii\helpers\Url;?>
<div class="panel panel-body">
<?php
if (isset($_GET['class_id'])){
$this->title='Today Fee';?>
<style type="text/css">
  table{width: 100%}
  *{ margin-left:0; padding:0;}
  th, tr, td  {
    border:1px solid black;
    padding:10px;
    font-size:.5em;
  }
  tr:nth-child(even){background-color: #f2f2f2}
</style>
<div style="width: 100%; text-align: center; background-color: #337ab7; color: #000; font-size:14px;">
  <h2 style="font-size:16px; font-weight:700; color:#000000; text-transform:capitalize;margin: 0;padding: 15px 0 8px 0;">
    <?=Yii::$app->common->getBranchDetail()->address?>
  </h2>
</div>

<?php }else{?>  
  <a style="margin-left: 5px" href="<?php echo Url::to(['/site']) ?>" class="btn btn-danger pull-right"> back</a>
  <a href="<?=Url::to(['reports/today-rcv/','class_id'=>1]) ?>" name="Generate Report" class="btn btn-primary pull-right"><i class="fa fa-download"></i> Generate Report</a>

  <br>
<?php } ?>
  <span>Fee ledger of <?= date('d M Y') ?></span>
  <table class="table table-bordered table-responsive">
    <thead>
      <tr class="alert alert-success">
        <th>SR.</th>
        <th>Student</th>
        <th>Father</th>
        <th>Class</th>
        <th>Roll #</th>
        <th>Fee Title</th>
        <th>Fee</th>
        <th>Transport</th>
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
        $total_amount+=$todayFee_rcv->head_recv_amount;
        $totaltransportAmnt+=$todayFee_rcv->transport_amount;
        $getHead=\app\models\FeeHead::find()->where(['branch_id'=>yii::$app->common->getBranch(),'id'=>$todayFee_rcv->fee_head_id])->one();
        /*
        $total_amount=$total_amount+$todayFee_rcv['head_recv_amount'];
        $totaltransportAmnt=$totaltransportAmnt+$todayFee_rcv['transport_amount'];
        $totalhostelAmnt=$totalhostelAmnt+$todayFee_rcv['hostel_amount'];*/
        ?>
        <tr>
          <td><?php echo $i; ?></td>
          <td><?= yii::$app->common->getStudentName($todayFee_rcv->stu_id); ?> </td>
          <td><?= yii::$app->common->getParentName($todayFee_rcv->stu_id); ?></td>
          <td><?= yii::$app->common->getStudentCGSection($todayFee_rcv->stu_id); ?></td>
          <td><?php $studentRollNo=yii::$app->common->getStudent($todayFee_rcv->stu_id); 
          echo ($studentRollNo->roll_no)?$studentRollNo->roll_no:'N/A';
          ?></td>
          <td><?php echo strtoupper($getHead->title) ?></td>
          <td>Rs. <?php echo  $todayFee_rcv->head_recv_amount;?><?php //echo $todayFee_rcv['head_recv_amount']?></td>
          <td>Rs.<?php echo ($todayFee_rcv->transport_amount)?$todayFee_rcv->transport_amount:'0'?></td>
          
        </tr>
      <?php } ?>
      <tr>
        <td></td>
        <th>Grand Total</th>
        <th>Rs. <?php echo $total_amount+$totaltransportAmnt?></th>
        <td colspan="2"></td>
        <th>Total</th>
        <th>Rs. <?php echo $total_amount ?></th>
        <th>Rs. <?php echo $totaltransportAmnt ?></th>
      </tr>
    </tbody>
  </table>
</div> 