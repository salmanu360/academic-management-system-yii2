<style type="text/css">
  *{ margin:0; padding:0;}
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
<div class="row">
    <div class="col-md-8"><h4 style="color:red">Fee details of Class : <?= strtoupper($class_details->title) ?> (Included all fee+arrears)</h4></div>
</div>
<table class="table table-bordered">
        <thead>
           <tr style="background: #3c8dbc">
            <th>SR</th>
            <th>Registeration No.</th>
            <th>Roll #</th>
            <th>Student</th>
            <th>Parent</th>
            <th>Fee</th>
        </tr>
    </thead>
    <tbody>
        <?php 
        $i=1;
        $total=0;
        foreach ($student_details as $student_value) { 
            $fee_group=\app\models\FeeGroup::find()->where(['is_active'=>'yes','fk_class_id'=>$class_details->class_id])->sum('amount');
            $fee_group_head_id=\app\models\FeeGroup::find()->where(['is_active'=>'yes','fk_class_id'=>$class_details->class_id])->one();
            $getHead=\app\models\FeeHead::find()->where(['branch_id'=>yii::$app->common->getBranch(),'id'=>$fee_group_head_id['fk_fee_head_id']])->one();
            $fee_discount=\app\models\FeePlan::find()->where(['branch_id'=>yii::$app->common->getBranch(),'stu_id'=>$student_value->stu_id,'status'=>1])->sum('discount');
            $settings = Yii::$app->common->getBranchSettings();
            /*$fee_rcv_detail=\app\models\FeeSubmission::find()->where(['fee_status'=>1,'stu_id'=>$student_value->stu_id])->one();*/
            $fee_arrears_detail=\app\models\FeeArears::find()->where(['status'=>1,'stu_id'=>$student_value->stu_id])->one();
            $transport= \app\models\TransportAllocation::find()->where(['stu_id'=>$student_value->user_id,'status'=>1])->one();
            $stop= \app\models\Stop::find()->where(['id'=>$transport['fk_stop_id']])->one();

            ?>
        <tr>
            <td><?=$i ?></td>
            <td><?=$student_value->user->username ?></td>
            <td><?= (count($student_value->roll_no)>0)?$student_value->roll_no:'N/A'; ?></td>
            <td><?= yii::$app->common->getName($student_value->user_id) ?></td>
            <td><?= yii::$app->common->getParentName($student_value->stu_id) ?></td>
            <td>
            <?php 
            
            if($student_value->avail_sibling_discount == 1 && $getHead->sibling_discount ==1){
                 $discount_sibling = $fee_group_head_id['amount']*$settings->sibling_discount/100 ;
                 $transport_details=$stop['fare']-$transport['discount_amount'];
                 $discount_details=$fee_discount;
                echo $total_fee_details=$fee_group - $discount_sibling +$fee_arrears_detail['arears'] +$transport_details -$discount_details;
                 $total=$total+$total_fee_details;

            }else{
                $discount_details=$fee_discount;
                $transport_details=$stop['fare']-$transport['discount_amount'];
                echo $total_fee_details=$fee_group + $fee_arrears_detail['arears'] + $transport_details -$discount_details;
                $total=$total+$total_fee_details;
            } 
            ?></td>
        </tr>
        <?php $i++;
        } ?>
        <tr>
            <th colspan="3">Grand Total</th>
            <th colspan="3"><?php echo $total; ?></th>
        </tr>
    </tbody>
</table>