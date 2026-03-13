<style type="text/css">
  *{ margin-left:0; padding:0;}
  th, tr, td  {
    border:1px solid black;
    padding:5px;
    font-size:0.8em;
  }
table{width: 100%;}
</style>
<div style="width: 100%; text-align: center; background-color: #337ab7; color: #000; font-size:14px;">
  <h2 style="font-size:16px; font-weight:700; color:#000000; text-transform:capitalize;margin: 0;padding: 15px 0 8px 0;">
    <?=Yii::$app->common->getBranchDetail()->address?>
  </h2>
</div>
<h3 style='text-align:center'> Fee ledger of <?= date('d M Y') ?>
</h3> 
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
        <th colspan="3">Rs. <?php echo $total_amount+$totaltransportAmnt+$totalhostelAmnt?></th>
      </tr>
    </tbody>
  </table>