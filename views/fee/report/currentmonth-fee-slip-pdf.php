<?php 
use kartik\date\DatePicker;
use yii\helpers\Url;
use yii\helpers\Html;
?>
<!DOCTYPE html>
<html>
<head>
    <title><?php echo date('M-Y')?> Fee</title>
    <style>
    .customers {
        font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
        border-collapse: collapse;
        width: 100%;
        color: black;
        font-size: 10px;
    }

    .customers td, .customers th {
        border: 0.2px solid #ddd;
        padding: 2px;
        color: black;
    }

    .customers th {
        padding-top: 2px;
        padding-bottom: 2px;
        text-align: left;
        /* background-color: #4CAF50;*/
        color: black;
    }
</style>

</head>
<body>
    <?php
   
    $copies = Yii::$app->common->getBranchSettings()->challan_copies;
        
        $transport_amount = 0;
        $hostel_amount = 0;
        $fine_amount = 0;
        $setting_fine_amount = 0;
        $total_discount_on_transport = 0;
        $total_discount_on_hostel = 0;
        $total_transport_arrears = 0;
        $total_hostel_arrears = 0;
        $getDateFees = '';
        $sums = 0;
        $i = 0;
        $discountPercent = 0;
        $net_amount = 0;
        $head_arrears = 0;
        $total_arrears = 0;
        $one_time_receive = 0;
        $total_discount = 0;
        $custom_ext_head_arr = [];
        $total_sibling_discount = 0;
        $transport_head_amount = 0;
        $hostel_head_amount = 0;
        $student_info = \Yii::$app->common->getStudent($student_id);
        $settings = Yii::$app->common->getBranchSettings();
        $setting_fine_amount = $settings->absent_fine;
        $student_details = Yii::$app->common->getStudent($student_id);

        if($settings->transport_on_off == 1){  
            $transport        = \app\models\TransportAllocation::find()->where(['stu_id'=>$student_details->user_id,'status'=>1,'on_off'=>1])->one();
        }else{
          $transport        = \app\models\TransportAllocation::find()->where(['stu_id'=>$student_details->user_id,'status'=>1,'on_off'=>0])->one();
      }
      $hostel = \app\models\HostelDetail::find()->where(['fk_student_id' => $student_details->stu_id, 'is_booked' => 1])->one();
      /*check if student avail transport*/
      if (count($transport) > 0) {
        $stop = \app\models\Stop::find()->where(['id' => $transport->fk_stop_id])->One();
        if (count($stop) > 0) {
            $transport_amount = $stop->fare;
        }
    }
    /*check if studen avail hostel*/
    if (count($hostel) > 0) {
        $hostel_details = \app\models\Hostel::find()->where(['id' => $hostel->fk_hostel_id])->One();
        //if(count($stop)>0){
        $hostel_amount = $hostel_details->amount;
        //}
    }
    /*absend fine*/
    $absent_count = \app\models\StudentAttendance::find()->where(['fk_stu_id' => intval($student_id), 'fk_branch_id' => yii::$app->common->getBranch(),'leave_type'=>'absent'])->andWhere(['>=', 'date', $Fromdate])->andWhere(['<=', 'date', $toDate])->count();
    $classWiseFine = \app\models\FineClassWise::find()->select(['amount'])->where(['class_id'=>$student_details->class_id,'status'=>'active'])->one();

    if($absent_count >0){ 
      // if 2 alowed then absent_count should be  > 2
      if(count($classWiseFine)>0){
        $fine_amount  = $classWiseFine->amount * $absent_count;
    }
    else{ 
        if($setting_fine_amount>0){
          $fine_amount  = $setting_fine_amount * $absent_count;
      }
  } 

} 
?>
<!--left panel start-->
<p style='text-align:center;'><br> <br><hr>
          
    </p>
<div style="border-bottom:1px dashed #3f3c8b;">
    <div style="width: 100%; text-align: center; padding-bottom:10px;">
        <h2 style="font-size:17px; width: 100%; text-transform:capitalize;margin: 0;padding:5px 0;"></h2>
    </div>
    <div style="width: 50%; float:left;">
        <table class="customers">
            <tr>
                <th>Name</th>
                <th><strong> <?= Yii::$app->common->getName($student_info->user_id); ?></strong></th>
            </tr>
            <tr>
                <th>Parent</th>
                <th><?= Yii::$app->common->getParentName($student_info->stu_id); ?></th>
            </tr>
            <tr>
                <th>Reg No.</th>
                <th><strong><?= $student_info->user->username ?></strong></th>
            </tr>
            <tr>
                <th>Roll No.</th>
                <th><strong><?=(count($student_info->roll_no)>0)?$student_info->roll_no:'N/A'?></strong></th>
            </tr>
            <tr>
                <th><?= Yii::t('app', 'Class') ?></th>
                <th><strong><?= strtoupper(Yii::$app->common->getStudentCGSection($student_id)) ?></strong></th>
            </tr>
        </table>
    </div>
    <!--left panel end-->

    <div style="width: 46%; float:right; background:none; font-size:10px;">
        <?php
        $previous_history_amt = 0;
        $head_arrears_previous = 0;
        $PreviousFeeTakenMonth = \app\models\FeeSubmission::find()->select('from_date,to_date,sum(head_recv_amount) as total_amount,transport_amount,hostel_amount,absent_fine,transport_arrears,hostel_arrears')->where(['stu_id' => intval($student_id), 'branch_id' => yii::$app->common->getBranch(), 'fee_status' => 1])->orderBy(['id' => SORT_ASC])->asArray()->one();
        $head_arrears_previous = \app\models\FeeArears::find()->where(['stu_id' => intval($student_id), 'status' => 1, 'branch_id' => yii::$app->common->getBranch()])->sum('arears');
        if ($head_arrears_previous == null) {
            $head_arrears_previous = 0;
        }
        $previous_history_amt = $PreviousFeeTakenMonth['total_amount'];
        if (count($PreviousFeeTakenMonth) > 0) {
            if ($PreviousFeeTakenMonth['transport_amount'] > 0) {
                $previous_history_amt = $previous_history_amt + $PreviousFeeTakenMonth['transport_amount'];
            }
            if ($PreviousFeeTakenMonth['hostel_amount'] > 0) {
                $previous_history_amt = $previous_history_amt + $PreviousFeeTakenMonth['hostel_amount'];
            }
            if ($PreviousFeeTakenMonth['absent_fine'] > 0) {
                $previous_history_amt = $previous_history_amt + $PreviousFeeTakenMonth['absent_fine'];
            }
        }

        if (strtotime($PreviousFeeTakenMonth['to_date']) == strtotime($PreviousFeeTakenMonth['from_date'])) {
            $previous_fee_date = date('M-Y', strtotime($PreviousFeeTakenMonth['to_date']));
        } else {
            $previous_fee_date = date('M-Y', strtotime($PreviousFeeTakenMonth['from_date'])) . ' - ' . date('M-Y', strtotime($PreviousFeeTakenMonth['to_date']));
        }
        if (!empty($PreviousFeeTakenMonth['from_date'])) {
           /*get  transport arrears query */
           $transport_arrears_query =\app\models\FeeSubmission::find()->where(['stu_id'=>intval($student_id),'branch_id'=>yii::$app->common->getBranch(),'fee_status'=>1])->andWhere('find_in_set("'.$PreviousFeeTakenMonth['from_date'].'", year_month_interval ) AND find_in_set( "'.$PreviousFeeTakenMonth['to_date'].'", year_month_interval )')->sum('transport_arrears'); 
           /*get  transport arrears query */
           /*get  and hostel arrears query */
           $hostel_arrears_query =\app\models\FeeSubmission::find()->where(['stu_id'=>intval($student_id),'branch_id'=>yii::$app->common->getBranch(),'fee_status'=>1])->andWhere('find_in_set("'.$PreviousFeeTakenMonth['from_date'].'", year_month_interval ) AND find_in_set( "'.$PreviousFeeTakenMonth['to_date'].'", year_month_interval )')->sum('hostel_arrears'); 
           /*get  and hostel arrears query */
           $total_transport_arrears = $transport_arrears_query;
           $total_hostel_arrears=$hostel_arrears_query;
       }
       ?>

       <table class="customers">
        <thead>
            <tr>
                <th>Fee Head</th>
                <th>Amount</th>
                <th>Arrears</th>
                <th>Head Details</th>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($getFeesDetails as $key => $getFeeDetails) {
                            //echo $student_id;
                $total_amount = 0;
                $fromConvert = date('Y-m', strtotime($Fromdate));
                $toConvert = date('Y-m', strtotime($toDate));
                /*get fee arrears*/
                $fee_arrears = \app\models\FeeArears::find()->where(['stu_id' => intval($student_id), 'branch_id' =>yii::$app->common->getBranch(), 'fee_head_id' => $getFeeDetails->fk_fee_head_id, 'status' => 1])->one();

                $total_fee_amount=\app\models\FeeGroup::find()->select(['amount'])->where(['fk_branch_id'=>yii::$app->common->getBranch(),'fk_fee_head_id'=>$getFeeDetails->fk_fee_head_id,'fk_class_id'=>$class_id])->one();

                $getDateFees = \app\models\FeeSubmission::find()->where(['stu_id' => intval($student_id), 'branch_id' => yii::$app->common->getBranch(), 'fee_head_id' => $getFeeDetails->fk_fee_head_id])->andWhere(['>=', 'from_date', $fromConvert])->andWhere(['<=', 'to_date', $toConvert])->one();

                if (count($getDateFees) > 0) { 
                    $alert = 1;
                } else { 
                    $alert = 2;
                    $getDiscountPercent = \app\models\FeePlan::find()->where(['fee_head_id' => $getFeeDetails->fk_fee_head_id, 'stu_id' => $student_id, 'status' => 1])->one();
                    $getHead = \app\models\FeeHead::find()->where(['branch_id' => yii::$app->common->getBranch(), 'id' => $getFeeDetails->fk_fee_head_id])->one();
                    /*if head is one time*/
                    if ($getHead->one_time_payment == 1) {
                        $onetimeFees = \app\models\FeeSubmission::find()->where(['stu_id' => intval($student_id), 'branch_id' => yii::$app->common->getBranch(), 'fee_head_id' => $getFeeDetails->fk_fee_head_id])->one();
                        $one_time_receive = (count($onetimeFees) > 0) ? 1 : 0;
                    }
                    /*new code salman*/
                    $sessionStartDate=date('Y-m-d',strtotime($settings->current_session_start));
                    $sessionEndDate=date('Y-m-d',strtotime($settings->current_session_end));
                    $oldStudent=\app\models\StuRegLogAssociation::find()->where(['fk_stu_id'=>$student_id])->count();
                    $promotedData=\app\models\StuRegLogAssociation::find()->where(['fk_stu_id'=>intval($student_id)])->andWhere(['>=','date(promoted_date)',$sessionStartDate])->andWhere(['<=','date(promoted_date)',$sessionEndDate])->count();
                    if($getHead->promotion_head == 1){

                        $promotionFeeReceive =\app\models\FeeSubmission::find()
                        ->where(['stu_id'=>intval($student_id),'branch_id'=>yii::$app->common->getBranch(),'fee_head_id'=>$getFeeDetails->fk_fee_head_id])
                        ->andWhere(['between', 'YEAR(recv_date)', $sessionStartDate, $sessionEndDate])
                        ->sum('head_recv_amount');
                        $promotion_fee_receive = ($promotionFeeReceive>0)?1:0;

                        if($promotedData==0){  
                          continue;
                      }                
                  }
                  /*if promotion head is active ends*/
                  if($getHead->one_time_payment == 1 && $getHead->promotion_head ==0 && $promotedData > 0 && count($fee_arrears)==0 ){
                      continue;
                  }
                  if($getHead->one_time_payment == 1 && $getHead->promotion_head ==0 && $promotedData == 0 && count($fee_arrears)==0 && $oldStudent > 0){
                      continue;
                  }
                  /*new code salman ends*/
                  $discountPercent = $discountPercent + $total_discount;
                  /*temproary avoid admission fee ends*/
                  if(($getHead->one_time_payment ==1 && $getHead->promotion_head ==0 && $one_time_receive==1 && count($fee_arrears)==0)){
                    continue;
                }
                else{
                    /*if onetime payment is a little left */
                    if(($getHead->one_time_payment ==1 && $getHead->promotion_head ==0 &&  $one_time_receive==1 && count($fee_arrears)==1)){
                    $sums = 0;
                  }else if(($getHead->one_time_payment ==1 && $getHead->promotion_head ==1 &&  $one_time_receive==1 && count($fee_arrears)==1)){
                    $sums = 0;
                  } else {
                        /* head discount multiply by no. of months*/
                        if (count($getDiscountPercent) > 0) {
                            $headFee = $getFeeDetails->amount;
                            if (count($diff) > 0 && $getHead->one_time_payment == 0) {
                                $total_discount = $getDiscountPercent->discount * $diff;
                            } else {
                                                //echo $diff;
                                $total_discount = $getDiscountPercent->discount * 1;
                            }
                            $sums = $sums + $getDiscountPercent->discount;
                        }else{
                          $total_discount = 0;
                      }
                      /* head multiply by total no of months*/

                      if (count($diff) > 0 && $getHead->one_time_payment == 0) {
                        $sums = $getFeeDetails->amount * $diff;
                    } else {
                                            //echo $diff;
                        $sums = $getFeeDetails->amount * 1;
                    }
                    $total_amount = $sums - $total_discount;
                }
                /*total amount with arrears if available*/
                $head_arrears = (count($fee_arrears) > 0) ? $fee_arrears->arears : 0;
                /*promotion head calculation starts*/
                if(($getHead->promotion_head ==1 && $promotion_fee_receive==1 && count($fee_arrears)==0)){
                  continue;
                }else{
                  if(($getHead->promotion_head ==1 && $promotion_fee_receive==1 && count($fee_arrears)==1)){  
                    $total_amount = 0;
                  }
                }
                /*promotion head calculation ends*/
                /*new code salman*/
                if($getHead->one_time_payment == 1 && $getHead->promotion_head ==0 && $promotedData > 0 && count($fee_arrears)==0){
                    $total_amount=0.001;
                }
                if($total_amount == 0){
                    $total_amount=0.001;
                }
                /*new code salman ends*/
                $total_arrears = $total_arrears+$head_arrears;
                $total_amount = $total_amount + $head_arrears;
                $net_amount = $net_amount + $total_amount;



                /*sibling discount calculation on regular head*/
                if ($getHead->one_time_payment == 1 && count($fee_arrears) == 1) {
                    $discount_sibling = 0;
                    $total_sibling_discount = 0;
                } else {

                    if ($student_info->avail_sibling_discount == 1) {
                        /*if sibling is more than provided in settings*/

                        if ($getFeeDetails->fk_fee_head_id == $getHead->id && $getHead->sibling_discount == 1) {
                            if (!empty($settings->sibling_discount)) {
                                $discount_sibling = $getFeeDetails->amount * $settings->sibling_discount / 100;

                                /*month multiplyer for siblings*/
                                if (count($discount_sibling) > 0) {
                                    $headFee = $getFeeDetails->amount;
                                    if (count($diff) > 0 && $getHead->one_time_payment == 0) {
                                        $total_sibling_discount = $discount_sibling * $diff;
                                    } else {
                                                            //echo $diff;
                                        $total_sibling_discount = $discount_sibling * 1;
                                    }
                                    $total_amount = $total_amount - $total_sibling_discount;
                                    $net_amount = $net_amount - round($total_sibling_discount, 0);
                                }


                            }
                        }
                    }
                }
                /*temproary avoid admission fee*/
                /*temproary avoid admission fee*/
                if($getHead->one_time_payment ==1 && $getHead->title =='Admission Fee' && $promotedData >0 ){
                  continue;
              }
            /*new code salman ends*
            /        /*new code salman ends*/

            /*sibling discount calculation ends*/
                                    //echo $getHead->title;echo $total_amount;exit;
            ?>
            <tr>
                <td> <?= strtoupper(ucfirst($getHead->title)); ?></td>
                <td>
                    <!--<span class="pull-left currency-head"> Rs. </span>-->
                    Rs. <?= round($total_amount, 0) ?>

                </td>
                <td>Rs. <?=$head_arrears?></td>
                <td>
                  Rs.  <?php
                                            //if(empty($diff)){echo $total_fee_amount['amount'];}
                  if($getHead->one_time_payment == 1){
                    echo $total_fee_amount['amount'];
                }else{
                    if($diff==1){
                        echo $total_fee_amount['amount'];
                    }
                    if($diff>1){
                        if($getHead->extra_head == 1){}else{
                            echo $total_fee_amount['amount']." x ".$diff." = ".$total_fee_amount['amount']*$diff;
                        }
                    }
                }
                ?>
            </td>
        </tr>
        <?php
    }
    $i++;

}
}

if ($net_amount == 0){
    $alert = 1;
}
else{

    ?>
    <!-- hostel transport head details -->
    <!--diffecence calculate hostel transport -->
    <?php
    if (count($diff) > 0) {
        if ($transport_amount > 0) {
            $total_discount_on_transport = $transport->discount_amount * $diff;
            $transport_head_amount = $transport_amount;
            $transport_amount = $transport_amount * $diff;
            $transport_amount = $transport_amount - $total_discount_on_transport;
        }
        if ($hostel_amount > 0) {
            $total_discount_on_hostel = $hostel->discount_amount * $diff;
            $hostel_head_amount = $hostel_amount;
            $hostel_amount = $hostel_amount * $diff;
            $hostel_amount = $hostel_amount - $total_discount_on_hostel;
        }
    } else {
        /*if transport and hostel amount is available*/
        if ($transport_amount > 0) {
            $transport_amount = $transport_amount - $transport->discount_amount;
        }
        if ($hostel_amount > 0) {
            $hostel_amount = $hostel_amount - $hostel->discount_amount;
        }
    }
    if ($total_transport_arrears > 0) {
        $transport_amount = $transport_amount + $total_transport_arrears;
    }
    if ($total_hostel_arrears > 0) {
        $hostel_amount = $hostel_amount + $total_hostel_arrears;
    }

    $net_amount = $net_amount + $transport_amount + $hostel_amount;
    if ($net_amount == 0) {
        $alert = 1;
    }
    /*if fine applicable*/
    if ($fine_amount > 0) {
        $net_amount = $net_amount + $fine_amount;
    }
    ?>
    <!--diffecence calculate hostel transport ends-->
    <?php
    if($transport_amount>0) {
        ?>
        <tr>
            <td colspan="1">Transport Fare</td>
            <td>Rs. <?= $transport_amount ?></td>
            <td>Rs. <?=$total_transport_arrears?></td>
            <td>Rs. <?php echo $transport_head_amount." x ".$diff." = ".$transport_head_amount*$diff;?> </td>
        </tr>
        <?php
    }
    ?>
    <?php
    if($hostel_amount > 0){
        ?>
        <tr>
            <td colspan="1">Hostel Fare</td>
            <td>Rs. <?= round($hostel_amount, 0) ?>  </td>
            <td>Rs. <?=$total_hostel_arrears?> </td>
            <td>Rs. <?php   echo $hostel_head_amount." x ".$diff." = ".$hostel_head_amount*$diff;?> </td>
        </tr>
        <!-- hostel transport head details end -->

        <?php
    }
    if($fine_amount > 0){
        ?>
        <!-- absend fine-->
        <tr >
            <td>Absent fine</td>
            <td>Rs.  <?= $fine_amount ?> </td>
            <td></td>
            <td>
                <?php
                if($absent_count >0){ 
                                          // if 2 alowed then absent_count should be  > 2
                  if(count($classWiseFine)>0){
                    echo  $classWiseFine->amount." X ".$absent_count;
                }
                else{ 
                    if($setting_fine_amount>0){
                      echo  $setting_fine_amount." X ".$absent_count;
                  }
              } 

          }
          ?>        
      </td>
  </tr>
  <!-- absend fine end-->
  <?php
}
if($extra_head_ids>0){
    foreach ($extra_head_ids as $exkey=>$head_id) {
        $getHead = \app\models\FeeHead::find()->where(['branch_id' => yii::$app->common->getBranch(), 'id' => $head_id])->one();
        $net_amount = $net_amount +$ex_head_amount[$exkey];
        ?>
        <tr id="amount-payable">
            <td><?=ucfirst($getHead->title)?></td>
            <td><?= $ex_head_amount[$exkey] ?></td>
            <td></td>
            <td></td>
        </tr>

        <?php
    }
}
?>

<tr id="amount-payable">
    <th colspan="2">Amount Payable</th>

    <td>

        Rs. <?= round($net_amount, 0) ?> 
        <input type="hidden" value="<?php echo round($net_amount, 0) ?>" class="nettotal">
         </td>
        <td></td>
    </tr>
    <tr>
        <th colspan="2">Total Arrears</th>
        <td>Rs. <?=$total_arrears+$total_hostel_arrears+$total_transport_arrears?> </td>
        <td> </td>
    </tr>
</tbody>
</table>

<?php
}

?>

</div>
</div>

</body>
</html>
<?php $script = <<< JS
$(document).ready(function() {
var total=$('.nettotal').val();
var sum = 0;
    $('.nettotal').each(function() {
        sum += Number($(this).val());
    });
});  
JS;
$this->registerJs($script);?>