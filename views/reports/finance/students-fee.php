<?php use yii\helpers\Url;
use app\models\StudentParentsInfo;
$settings = Yii::$app->common->getBranchSettings();
$studnt_info = \Yii::$app->common->getStudent($student_id);
?> 

<a href="<?= Url::to(['reports/student-fee-details-pdf/','stuid'=>$student_id,'classid'=>$class_id])  ?>" name="Generate Report" class="btn btn-primary pull-right">Generate Report</a>
<h5><?= yii::$app->common->getName($stu_id)  ?> <?= ($studentTable->gender_type== '0')? 'D/O' : 'S/O' ?> <?= Yii::$app->common->getParentName($studentTable->stu_id);?></h5>
<table class="table table-bordered">
  <thead>
   <tr style="background: #3c8dbc">
    <th>SR</th>
    <th>Fee Head</th>
    <th>Amount</th>
    <th>Head Discount</th>
    <th>Sibling Discount</th>
  </tr>
</thead>
<tbody>
  <?php 
  $i=0;
  $total=0;
  $discountHead=0;
  $total_sibling_discount = 0;
  $transport=0;
  $transport=\app\models\TransportAllocation::find()->where(['stu_id'=>$studnt_info->user_id,'status'=>1])->one();
                            //echo '<pre>';print_r($getFeeDetails);die();
  foreach ($getFeeDetails as $key=> $getFeeDetails) {
    $i++;
    $total=$total+$getFeeDetails->amount;
    $getHead=\app\models\FeeHead::find()->where(['branch_id'=>yii::$app->common->getBranch(),'id'=>$getFeeDetails->fk_fee_head_id])->one();
    $getDiscountPercent=\app\models\FeePlan::find()->where(['fee_head_id'=>$getFeeDetails->fk_fee_head_id,'stu_id'=>$student_id,'status'=>1])->one();
    $discountHead=$discountHead+$getDiscountPercent['discount'];


    ?>
    <tr>
     <td><?= $i; ?></td>
     <td><?=strtoupper($getHead->title) ?> </td> 
     <td>Rs. <?=$getFeeDetails->amount ?> </td>
     <td>Rs. <?=(!empty($getDiscountPercent['discount']))?$getDiscountPercent['discount']:0?></td>
     <td>
       <?php
       if(!empty($parent_cnic) && $studnt_info->avail_sibling_discount ==1){
         if(($cnic_count) >= $settings->sibling_no_childs  && $getFeeDetails->fk_fee_head_id == $getHead->id && $getHead->sibling_discount ==1 ){
          if(!empty($settings->sibling_discount)){
            echo 'Rs. '.$discount_sibling = $getFeeDetails->amount*$settings->sibling_discount/100;
          }
        }
        $total_sibling_discount = $total_sibling_discount+$discount_sibling;
      }
      ?></td>


    </tr>
    
  <?php } ?>
  <tr>
    <td></td>
    <th> Transport Fare

    </th>
    <td> <?php
    if(count($transport)>0){
      echo 'Rs. ';
      $stop = \app\models\Stop::find()->where(['id'=>$transport->fk_stop_id])->One();
      if(count($stop)>0){
        echo $transport_amount = $stop->fare -$transport->discount_amount;
      }
    } ?></td>
  </tr>
  <tr>
    <td></td>
    <th>Total</th>
    <th>
      <?php
      if(count($transport)>0){
       echo 'Rs. ';
       $stop = \app\models\Stop::find()->where(['id'=>$transport->fk_stop_id])->One();
       if(count($stop)>0){
         $transport_amount = $stop->fare;
         echo $total+$transport_amount-$transport->discount_amount;
       }
     }else{
      echo 'Rs. '. $total;
    }

    ?>

  </th>
  <th>Rs. <?=$discountHead ?></th>
  <th>Rs. <?=$total_sibling_discount ?></th>
</tr>
<tr>
  <td></td>
  <th>Fee Taken</th>

  <th>Rs.
    <?php
    if(count($transport)>0){
      echo $total-$discountHead+$transport_amount-$transport->discount_amount;
    }else if(($cnic_count) >= $settings->sibling_no_childs && $studnt_info->avail_sibling_discount==1){
                                  //sibling

      if(!empty($settings->sibling_discount)){
        $discount_sibling = $getFeeDetails->amount*$settings->sibling_discount/100;
        echo $total-$discountHead-$total_sibling_discount;
      }
    }


    else{
                                  //end of sibling
      echo $total-$discountHead;
    }
    ?>
  </th>

</tr>
</tbody>
</table>