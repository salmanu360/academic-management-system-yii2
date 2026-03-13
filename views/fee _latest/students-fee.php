 <?php 
use kartik\date\DatePicker;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\bootstrap\Modal;
use yii\widgets\ActiveForm;


$transport_amount = 0;
$hostel_amount    = 0;
$fine_amount      = 0;
$setting_fine_amount = 0;
 $total_discount_on_transport=0;
 $total_discount_on_hostel=0;
 $total_transport_arrears =0;
 $total_hostel_arrears=0; 
 $overall_discount  = 0;
 $getDateFees='';
  $fromConvert=date('Y-m',strtotime($Fromdate));
  $toConvert=date('Y-m',strtotime($toDate));
$student_info = \Yii::$app->common->getStudent($student_id);
$discount_type= ArrayHelper::map(\app\models\FeeDiscountTypes::find()->where(['fk_branch_id'=>Yii::$app->common->getBranch(),'is_active'=>1])->all(),'id','title');
$exteraHeadArrayMap = \app\models\FeeHead::find()->select(['id','title'])->where(['branch_id'=>Yii::$app->common->getBranch(),'extra_head'=>1])->asArray()->all();
    $form = ActiveForm::begin(['id' => 'fee_submission_form','action'=>Url::to(['fee/fee-submit'])]);
$settings = Yii::$app->common->getBranchSettings();
$setting_fine_amount = $settings->absent_fine;
$student_details = Yii::$app->common->getStudent($student_id);
if($settings->transport_on_off == 1){  
$transport        = \app\models\TransportAllocation::find()->where(['stu_id'=>$student_details->user_id,'status'=>1,'on_off'=>1])->one();
}else{
  $transport        = \app\models\TransportAllocation::find()->where(['stu_id'=>$student_details->user_id,'status'=>1,'on_off'=>0])->one();
}

$hostel           = \app\models\HostelDetail::find()->where(['fk_student_id'=>$student_details->stu_id,
'is_booked'=>1])->one();
/*check if student avail transport*/
if(count($transport)>0){
  $stop = \app\models\Stop::find()->where(['id'=>$transport->fk_stop_id])->One();
  if(count($stop)>0){
    $transport_amount = $stop->fare;
  }
}
/*check if studen avail hostel*/
if(count($hostel)>0){
  $hostel_details = \app\models\Hostel::find()->where(['id'=>$hostel->fk_hostel_id])->One();
  //if(count($stop)>0){
    $hostel_amount = $hostel_details->amount;
  //}
}
/*absend fine*/
$absent_count =\app\models\StudentAttendance::find()->where(['fk_stu_id'=>intval($student_id),'fk_branch_id'=>yii::$app->common->getBranch(),'leave_type'=>'absent'])->andWhere(['>=','date',$Fromdate])->andWhere(['<=','date',$toDate])->count();
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
   
 <div class="box box-warning">
    <div class="box-header with-border">
  <input type="button" class="btn btn-warning" id="previousFeeDetails" data-url="<?php echo Url::to(['fee/previous-details-student']) ?>" data-stuid="<?php echo $student_id ?>" value="Show Previous Details">
        <!-- <h3 class="box-title" style="text-align: center;"> Fee Details</h3> -->
    </div>
    <div id="showPreviousDetails">
      <div id="loading" style="display: none;">  Loading..!</div>
    </div>
     <div class="box-body">
     <?php 
     $previous_history_amt=0;
     $head_arrears_previous=0;
      $PreviousFeeTakenMonth=\app\models\FeeSubmission::find()->select(['from_date,to_date,sum(head_recv_amount) as total_amount,transport_amount,hostel_amount,absent_fine,transport_arrears,hostel_arrears'])->where(['stu_id'=>intval($student_id),'branch_id'=>yii::$app->common->getBranch(),'fee_status'=>1])->groupBy('from_date,to_date,transport_amount,hostel_amount,absent_fine,transport_arrears,hostel_arrears')->asArray()->one();
    
      $FineOrFeeTakenMonthWise =\app\models\FeeSubmission::find()->select('from_date,to_date,hostel_amount,absent_fine,transport_arrears,hostel_arrears')->where(['stu_id'=>intval($student_id),'branch_id'=>yii::$app->common->getBranch()])->andWhere(['=','from_date',$fromConvert])->one(); 
      /*if absent fine has been taken from previous month subtract that from exisiting absent amount.*/ 
      if($FineOrFeeTakenMonthWise['absent_fine']>0){
        $fine_amount = $fine_amount - $FineOrFeeTakenMonthWise['absent_fine'];
      }

      
      $fee_arrears_rcv=\app\models\FeeArrearsRcv::find()->where(['stu_id'=>intval($student_id)])->sum('amount');
     $head_arrears_previous = \app\models\FeeArears::find()->where(['stu_id'=>intval($student_id),'status'=>1,'branch_id'=>yii::$app->common->getBranch()])->sum('arears');
     if($head_arrears_previous== null){$head_arrears_previous=0;}
      $previous_history_amt  = $PreviousFeeTakenMonth['total_amount'];
      if(count($PreviousFeeTakenMonth)>0 ){
        if($PreviousFeeTakenMonth['transport_amount']>0){
            $previous_history_amt = $previous_history_amt+$PreviousFeeTakenMonth['transport_amount'];
        }
        if($PreviousFeeTakenMonth['hostel_amount']>0){
           $previous_history_amt = $previous_history_amt+$PreviousFeeTakenMonth['hostel_amount'];
        }
        if($PreviousFeeTakenMonth['absent_fine']>0){
           $previous_history_amt = $previous_history_amt+$PreviousFeeTakenMonth['absent_fine'];
        }

      }
 
      if(strtotime($PreviousFeeTakenMonth['to_date']) == strtotime($PreviousFeeTakenMonth['from_date'] )){
        $previous_fee_date = date('M-Y',strtotime($PreviousFeeTakenMonth['to_date']));
      }else{
        $previous_fee_date = date('M-Y',strtotime($PreviousFeeTakenMonth['from_date'])) .' - '. date('M-Y',strtotime($PreviousFeeTakenMonth['to_date']));
      }
      if(!empty($PreviousFeeTakenMonth['from_date'])){
          /*get  transport arrears query */
           $transport_arrears_query =\app\models\FeeSubmission::find()->where(['stu_id'=>intval($student_id),'branch_id'=>yii::$app->common->getBranch(),'fee_status'=>1])->andWhere('find_in_set("'.$PreviousFeeTakenMonth['from_date'].'", year_month_interval ) AND find_in_set( "'.$PreviousFeeTakenMonth['to_date'].'", year_month_interval )')->sum('transport_arrears'); 
          /*get  transport arrears query */
           /*get  and hostel arrears query */
           $hostel_arrears_query =\app\models\FeeSubmission::find()->where(['stu_id'=>intval($student_id),'branch_id'=>yii::$app->common->getBranch(),'fee_status'=>1])->andWhere('find_in_set("'.$PreviousFeeTakenMonth['from_date'].'", year_month_interval ) AND find_in_set( "'.$PreviousFeeTakenMonth['to_date'].'", year_month_interval )')->sum('hostel_arrears'); 
          /*get  and hostel arrears query */
          $total_transport_arrears = $transport_arrears_query;
          $total_hostel_arrears=$hostel_arrears_query;

        ?>

        <div class="row">
        <div class="col-md-12">
        <?php if(count($PreviousFeeTakenMonth) > 0){   ?>
            <h5><span style="font-weight: bold;">Previous Fee taken Months:&nbsp;</span>
            <span style="color: red;">&nbsp;<?= $previous_fee_date ?></span></h5>
            <h5><span style="font-weight: bold;">Total Amount Received:&nbsp;</span>
            <span style="color: red;">&nbsp;Rs.<?=$previous_history_amt+$fee_arrears_rcv ?></span></h5>
            <h5><span style="font-weight: bold;">Total Arrears (including Hoste/Transport):&nbsp;</span>
            <span style="color: red;">&nbsp;Rs.<?= $head_arrears_previous+$total_transport_arrears+$total_hostel_arrears?></span></h5>
            <?php
        } ?>
        </div>
      </div>
        <?php
      }
      ?>
      
     <div class="row">
      <div class="col-md-6">
      <input type="hidden" data-url="<?= Url::to(['fee/generate-student-fee']) ?>" id="getDateFee">
      <input type="hidden" value="<?= $class_id ?>" name="class-id" id="class_id">
      <input type="hidden" value="<?= $group_id ?>" name="group-id" id="group_id">
      <input type="hidden" value="<?= $section_id ?>" name="section-id" id="section_id">
      <input type="hidden" value="<?= $student_id ?>" id="stu_id" name="stu_id">
      
        <?php
         echo '<label>From:</label>';
          echo DatePicker::widget([
          'name' => 'Fromdate', 
          'value' => $Fromdate,
          'options' => ['placeholder' => 'From Date','id'=>'from_date_admission'],
          'pluginOptions' => [
              'format' => 'yyyy-m-dd',
              'todayHighlight' => true,
              'autoclose'=>true,
              //'startDate' => '+"'.$getFromDate.'"d',
          ]
        ]);
        ?>
      </div>
      <div class="col-md-6">
        <?php
         echo '<label>To:</label>';
        echo DatePicker::widget([
        'name' => 'toDate', 
        'value' => $toDate,
        'options' => ['placeholder'=>'To Date','class'=>'todate_admission'],
        'pluginOptions' => [
            'format' => 'yyyy-m-dd',
            'todayHighlight' => true,
            'autoclose'=>true,
            //'startDate' => '+0d',
        ]
      ]);
         ?>

      </div>
     </div>
      <br />
     <div class="row">
        <div class="col-md-6"><b>Total Months</b>&nbsp;&nbsp;:&nbsp;<?php if(count($diff) >0){echo $diff;}else{echo '1';}; ?></div>
        <div class="col-md-6"></div>
      </div> 
     <div class="table-responsive">
        <table class="table table-hover">
          <thead>
            <tr class="info">
              <th>Fee Head</th>
              <th>Amount</th>
              <th>Arears</th>
              </tr>
          </thead>
          <tbody>
            <?php
            $sums=0;
            $i=0;
            $discountPercent =0;
            $net_amount=0;
            $head_arrears=0;
            $one_time_receive=0;
            $total_discount=0;
            $promotion_fee_receive=0;
             $custom_ext_head_arr=[];
             $total_sibling_discount = 0;
            foreach ($getFeeDetails as $key=> $getFeeDetails) { 
            $total_amount =0; 
            /*get fee arrears*/
            $fee_arrears = \app\models\FeeArears::find()->where(['stu_id'=>intval($student_id),'branch_id'=>yii::$app->common->getBranch(),'fee_head_id'=>$getFeeDetails->fk_fee_head_id,'status'=>1])->one();
            $getDateFeesCount=\app\models\FeeSubmission::find()->select(['year_month_interval'])->where(['stu_id'=>intval($student_id),'branch_id'=>yii::$app->common->getBranch(),'fee_head_id'=>$getFeeDetails->fk_fee_head_id])->groupBy(['year_month_interval'])->asArray()->all();


            /*concatination of previous fee months record*/
            $interval_concat='';
            foreach ($getDateFeesCount as $gdfc => $month_year_int) {
              $gdfc = $gdfc+1;
              if(count($getDateFeesCount) == $gdfc){
        $interval_concat .= $month_year_int['year_month_interval'];
              }else{
              $interval_concat .= $month_year_int['year_month_interval'].',';
              }

            }
            $explode_interval  =  explode(',', $interval_concat);
            if(!in_array($fromConvert, $explode_interval) && !in_array($toConvert, $explode_interval)){
              $getDateFees=\app\models\FeeSubmission::find()->where(['stu_id'=>intval($student_id),'branch_id'=>yii::$app->common->getBranch(),'fee_head_id'=>$getFeeDetails->fk_fee_head_id])->andWhere(['>=','from_date',$fromConvert])->andWhere(['<=','to_date',$toConvert])->one();
            }else{
              /*get received amount transport and hostel amount.*/
              /*$transport_received =\app\models\FeeSubmission::find()->where(['stu_id'=>intval($student_id),'branch_id'=>yii::$app->common->getBranch()])->andWhere(['>=','from_date',$fromConvert])->andWhere(['<=','to_date',$toConvert])->sum('transport_amount');*/
              $transport_received =\app\models\FeeSubmission::find()->where(['stu_id'=>intval($student_id),'branch_id'=>yii::$app->common->getBranch()])->andWhere('find_in_set("'.$fromConvert.'", year_month_interval ) AND find_in_set( "'.$toConvert.'", year_month_interval )')->sum('transport_amount');
              /*$hostel_received =\app\models\FeeSubmission::find()->where(['stu_id'=>intval($student_id),'branch_id'=>yii::$app->common->getBranch()])->andWhere(['>=','from_date',$fromConvert])->andWhere(['<=','to_date',$toConvert])->sum('hostel_amount');*/
              $hostel_received =\app\models\FeeSubmission::find()->where(['stu_id'=>intval($student_id),'branch_id'=>yii::$app->common->getBranch()])->andWhere('find_in_set("'.$fromConvert.'", year_month_interval ) AND find_in_set( "'.$toConvert.'", year_month_interval )')->sum('hostel_amount');
              
             if(count($transport) == 0 ){
              $transport_amount=0;
             } 
             if(count($hostel) == 0){
                        $hostel_amount=0;
             }
             /*if(count($transport) > 0  &&  in_array($fromConvert, $explode_interval) && in_array($toConvert, $explode_interval)){
                if($transport_received >0){
                  $transport_amount=0;
                }
             }*/ 
              if(count($transport) > 0 && $transport_received>0 ){
                $transport_amount=0;
              }
             
             if(count($hostel) > 0 && $hostel_received >0){
              $hostel_amount=0;
             }
        
               $getDateFees=0;
               /*$transport_amount=0;
               $hostel_amount=0;*/
              
              
            }

            if(count($getDateFees) > 0){ 
               $alert=1;
            }else{ 
               $alert=2;
              $getDiscountPercent=\app\models\FeePlan::find()->where(['fee_head_id'=>$getFeeDetails->fk_fee_head_id,'stu_id'=>$student_id,'status'=>1])->one();
              $getHead=\app\models\FeeHead::find()->where(['branch_id'=>yii::$app->common->getBranch(),'id'=>$getFeeDetails->fk_fee_head_id])->one();
  

                 /*if head is one time*/
              if($getHead->one_time_payment == 1){
                $onetimeFees=\app\models\FeeSubmission::find()->where(['stu_id'=>intval($student_id),'branch_id'=>yii::$app->common->getBranch(),'fee_head_id'=>$getFeeDetails->fk_fee_head_id])->one();
                $one_time_receive = (count($onetimeFees)>0)?1:0;
              }

              /*if promotion head is active*/ 

               $year=date('Y');  
               // $sessionEndDate=$settings->current_session_end;
               $sessionStartDate=date('Y-m-d',strtotime($settings->current_session_start));
               $sessionEndDate=date('Y-m-d',strtotime($settings->current_session_end));
$checkOldStudent=\app\models\StuRegLogAssociation::find()->where(['fk_stu_id'=>$student_id])->count();
$promotedData=\app\models\StuRegLogAssociation::find()->where(['fk_stu_id'=>intval($student_id)])->andWhere(['>=','date(promoted_date)',$sessionStartDate])->andWhere(['<=','date(promoted_date)',$sessionEndDate])->count();
              if($getHead->promotion_head == 1){
               
                $promotionFeeReceive =\app\models\FeeSubmission::find()->where(['stu_id'=>intval($student_id),'branch_id'=>yii::$app->common->getBranch(),'fee_head_id'=>$getFeeDetails->fk_fee_head_id,'YEAR(recv_date)'=>$year])->sum('head_recv_amount');
                $promotion_fee_receive = ($promotionFeeReceive>0)?1:0;
                
                if($promotedData==0){  
                  continue;
                }                
              }
              /*if promotion head is active ends*/
               /*temproary avoid admission fee*/
                if($getHead->one_time_payment == 1 && $getHead->promotion_head ==0 && $promotedData > 0 || $checkOldStudent > 0 && count($fee_arrears)==0 ){
                  continue;
                }
              
              /*temproary avoid admission fee ends*/

                $discountPercent = $discountPercent + $total_discount;
                
                /*onetime payment calculation stars*/
                if(($getHead->one_time_payment ==1 && $one_time_receive==1 && count($fee_arrears)==0)){
                  continue;
                }else{
                  /*if onetime payment is a little left */
                  if(($getHead->one_time_payment ==1 && $one_time_receive==1 && count($fee_arrears)==1)){
                    $sums = 0;
                  }else{
                    /* head discount multiply by no. of months*/
                    if(count($getDiscountPercent)>0){
                      $headFee=$getFeeDetails->amount;
                      if(count($diff) >0 && $getHead->one_time_payment == 0){
                        $total_discount =  $getDiscountPercent->discount*$diff; 
                      }else{
                        //echo $diff;
                        $total_discount =  $getDiscountPercent->discount *1; 
                      }
                        $sums=$sums+$getDiscountPercent->discount;
                    }else{
                      $total_discount = 0;
                    }
                              /* head multiply by total no of months*/

                            if(count($diff) >0 && $getHead->one_time_payment == 0){
                      $sums=$getFeeDetails->amount*$diff;
                    }else{
                        //echo $diff;
                      $sums=$getFeeDetails->amount*1;
                    }
                    $total_amount  = $sums - $total_discount;
                    $overall_discount = $overall_discount + $total_discount;
                  }

                  /*promotion head calculation starts*/
                if(($getHead->promotion_head ==1 && $promotion_fee_receive==1 && count($fee_arrears)==0)){
                  continue;
                }else{
                  if(($getHead->promotion_head ==1 && $promotion_fee_receive==1 && count($fee_arrears)==1)){  
                    $total_amount = 0;
                  }
                }
                /*promotion head calculation ends*/
                  
                  /*total amount with arrears if available*/
                  $head_arrears =  (count($fee_arrears)>0)?$fee_arrears->arears:0;
                  $total_amount = $total_amount + $head_arrears;
                  $net_amount   = $net_amount +$total_amount;
 
                  /*sibling discount calculation on regular head*/
                  if($getHead->one_time_payment ==1 && count($fee_arrears)==1){
                      $discount_sibling=0;
                      $total_sibling_discount=0;
                  }else{
                      if(!empty($parent_cnic && $student_info->avail_sibling_discount==1)){
                          /*if sibling is more than provided in settings*/
                          /*old if condition basis of cnic count*/
                          // if(($cnic_count) >= $settings->sibling_no_childs  && $getFeeDetails->fk_fee_head_id == $getHead->id && $getHead->sibling_discount ==1 )
                          /*old if condition basis of cnic count ends */
                          if($getFeeDetails->fk_fee_head_id == $getHead->id && $getHead->sibling_discount ==1 ){
                              if(!empty($settings->sibling_discount)){
                                  $discount_sibling = $getFeeDetails->amount*$settings->sibling_discount/100;
                                  /*month multiplyer for siblings*/
                                  if(count($discount_sibling)>0){
                                      $headFee=$getFeeDetails->amount;
                                      if(count($diff) >0 && $getHead->one_time_payment == 0){
                                          $total_sibling_discount =  $discount_sibling*$diff;
                                      }else{

                                          //echo $diff;
                                          $total_sibling_discount =  $discount_sibling *1;
                                      }
                                  }

                                  ?>
                                  <input type="hidden" name="sibling_discount[<?= $getFeeDetails->fk_fee_head_id ?>]" value = "<?=$total_sibling_discount?>">
                                  <?php
                                 //  $head_sibling_head_amount=$getFeeDetails->amount -$total_sibling_discount;
                              // $total_amount = $head_sibling_head_amount;
                               $total_amount = $total_amount - $total_sibling_discount;
                              $overall_discount  = $overall_discount+$total_sibling_discount;
                              $net_amount = $net_amount - round($total_sibling_discount,0);
                              }
                          }
                         
                      }
                  }
                      
        /*sibling discount calculation ends*/
                  if($total_amount >0){
                    ?>
                    <tr>
                      <td>
                      <input type="hidden" name="fee_head_id[]" class="feeHeadId" value="<?= $getFeeDetails->fk_fee_head_id ?>">
                      <?= strtoupper($getHead->title);?></td>
                      <td>

                        <span class="pull-left currency-head"> Rs. </span>
                        <div class="form-group field-transaction-head-amount pull-right width_88">
                            <input type="number" id="transaction-head-amount_<?= $i ?>" class="form-control"
                                   name="transaction_head_amount[<?= $getFeeDetails->fk_fee_head_id ?>]"
                                   value="<?= round($total_amount, 0) ?>" aria-invalid="false"
                                   placeholder="<?= round($total_amount, 0) ?>" min="0"  onkeypress="return isNumberKey(event)">
                            <div class="help-block"></div>
                        </div>
                        <?php
                        /*if there's any head discount.*/
                        if (count($total_discount) > 0) {
                            ?>
                            <input type="hidden" value="<?= $total_discount; ?>"
                                   name="headDiscount[<?= $getFeeDetails->fk_fee_head_id ?>]"/>
                            <?php
                        }
                        ?>
                        <!-- end of old amount --> 
                      </td>
                      <td>
                        <span class="pull-left currency-head"> Rs. </span>
                        <div class="form-group field-transaction-head-amount pull-right width_88">
                            <input type="text" id="transaction-head-arrears_<?= $i ?>" class="form-control" name="transaction_head_arrears_amount[<?=  $getFeeDetails->fk_fee_head_id  ?>]"
                                   value=""
                                   aria-invalid="false" placeholder="" readonly >
                            <div class="help-block"></div>
                        </div>
                      </td>
                    </tr>
                    <?php
                  }
                }
                /*one time payment calculation ends with all heads.*/
                $i++;
            } };
                if($net_amount == 0){
                    $alert=1;
                }
                   ?>
                <!-- hostel transport head details -->
                <!--diffecence calculate hostel transport -->
                <?php

                if(count($diff)>0){
                    if($transport_amount>0){

                        $total_discount_on_transport = $transport->discount_amount*$diff;
                        $transport_amount = $transport_amount*$diff;
                        $transport_amount = $transport_amount-$total_discount_on_transport;
                    }
                    if($hostel_amount>0){
                        $total_discount_on_hostel = $hostel->discount_amount*$diff;
                        $hostel_amount = $hostel_amount*$diff;
                        $hostel_amount = $hostel_amount-$total_discount_on_hostel;
                    }
                }else{
                    /*if transport and hostel amount is available*/
                    if($transport_amount>0){
                        $transport_amount = $transport_amount - $transport->discount_amount;
                    }
                    if($hostel_amount>0){
                        $hostel_amount=$hostel_amount-$hostel->discount_amount;
                    }
                }
                if($total_transport_arrears>0){
                    $transport_amount =$transport_amount+$total_transport_arrears;
                }
                if($total_hostel_arrears >0){
                    $hostel_amount =$hostel_amount+$total_hostel_arrears;
                }

                $net_amount = $net_amount + $transport_amount + $hostel_amount;
                if($net_amount==0){
                    $alert=1;
                }
                /*if fine applicable*/
                if($fine_amount>0){
                  $net_amount = $net_amount + $fine_amount;
                }
                ?>
                <!--diffecence calculate hostel transport ends-->
                <tr style="<?=  ($transport_amount >0 )?'visibility:visible;':'display:none;' ?> width: 100%;">
                  <th colspan="1">Transport Fare</th>
                  <td>
                      <span class="pull-left currency-head"> Rs. </span>
                      <div class="form-group field-transaction-head-amount pull-right width_88">
                          <input type="number" id="transaction-head-amount_88888" data-totaltrnsprt="<?=($transport_amount>0)?round($transport_amount,0):0 ?>" class="form-control" name="StudentDisount[input_total_transport_fare]" value="<?=$transport_amount?>" min="0"  onkeypress="return isNumberKey(event)" placeholder="<?= round($transport_amount, 0) ?>">
                          <div class="help-block"></div>
                      </div>
                  </td>
                    <td>
                        <span class="pull-left currency-head"> Rs. </span>
                        <div class="form-group field-transaction-head-amount pull-right width_88">
                            <input type="text" id="transaction-head-arrears_88888" class="form-control" name="transaction_transport_arrears_amount"  aria-invalid="false"  readonly >
                            <div class="help-block"></div>
                        </div>
                    </td>
                </tr>

                <tr style="<?= ( $hostel_amount >0 )?'visibility:visible;':'display:none;' ?> width: 100%;">
                  <th colspan="1">Hostel Fare</th>
                  <td>
                    <span id="total-transport-fare" data-totaltrnsprt="<?=($hostel_amount>0)?round($hostel_amount,0):0 ?>">
                      <span class="pull-left currency-head"> Rs. </span>
                      <div class="form-group field-transaction-head-amount pull-right width_88">
                           <input type="number" id="transaction-head-amount_99999" class="form-control" name="StudentDisount[input_total_hostel_fare]" value="<?=$hostel_amount?>" data-totalhostl="<?=(!empty($hostel_amount) || $hostel_amount != null)?round($hostel_amount,0):0 ?>"aria-invalid="false" placeholder="<?= round($hostel_amount, 0) ?>"  min="0"  onkeypress="return isNumberKey(event)">
                          <div class="help-block"></div>
                      </div>
                    </td>
                    <td>
                        <span class="pull-left currency-head"> Rs. </span>
                        <div class="form-group field-transaction-head-amount pull-right width_88">
                            <input type="text" id="transaction-head-arrears_99999" class="form-control" name="transaction_hostel_arrears_amount"  aria-invalid="false" placeholder="" readonly>
                            <div class="help-block"></div>
                        </div>
                    </td>
                </tr>
                <!-- hostel transport head details end -->
                <!-- absend fine-->
                <tr style="<?=  ($fine_amount >0 )?'visibility:visible;':'display:none;' ?> width: 100%;">
                  <th colspan="2">Absent fine</th>
                  <td>
                      <span class="pull-left currency-head"> Rs. <?=$fine_amount?></span>
                      <div class="form-group field-transaction-head-amount pull-right width_88">
                          <input type="hidden" id="input_absent_fine_fare" onkeyup="transportAdjust(event,this);" data-totalafine="<?=($fine_amount>0)?round($fine_amount,0):0 ?>" class="form-control" name="StudentFine[absendFine]" value="<?=$fine_amount?>"  style="width: 100px;">

                          <div class="help-block"></div>
                      </div>
                  </td>
                  <td></td>
                </tr>
                <!-- absend fine end-->
                <?php
                if($net_amount> 0){
                  ?>
                  <tr>
                    <th colspan="2"><span class="res_total">Total Arrears</span></th>
                    <td colspan="1">
                        <div class="form-group field-transaction-arrears-amount has-success">
                            <input type="text" id="total-arrears-amount"  data-total="<?=round(0,0)?>" class="form-control" name="FeeTransactionDetails[total_arrears_amount]" value="" readonly="readonly" aria-invalid="false">
                            <div class="help-block"></div>
                        </div>
                        </td>
                  </tr>
                  <?php
                }
                ?>
                <tr id="amount-payable">
                    <th class="green" colspan="2"><span class="payable">Amount Payable</span></th>
                    <td class="dblue"><span id="net-amount" data-net="<?=round($net_amount,0)?>">    <?php
                            echo 'Rs. '. round($net_amount,0); ?>
                                </span>
                        <input type="hidden" id="input_total_discount" class="form-control" name="StudentDisount[input_total_discount]" value="<?=$discountPercent?>" >
                        <input type="hidden" id="input_total_amount_payable" class="form-control" name="StudentDisount[input_total_amount_payable]" value="<?=round($net_amount,0)?>">
                        <input type="hidden" id="transaction-amount" class="form-control" name="FeeTransactionDetails[transaction_amount]" value="" readonly="readonly" aria-invalid="false">
                        <input type="hidden" id="diff_date_month" class="form-control" name="diff" value="<?=$diff?>" readonly="readonly" aria-invalid="false">
                    </td>
                </tr>
                </tbody>
            </table>
         </div>
             <br>
              <div class="col-md-12">
                <?php
                  if($alert == 1){
                    /*if($hostel_amount <=0 && $transport_amount <=0){
                      echo '<div class="alert alert-danger">This Student has already sumbitted Fee in these months</div>';
                    }*/ 
                    if($net_amount== 0 && $overall_discount ==0){
                      echo '<div class="alert alert-danger">This Student has already sumbitted Fee in these months or Student has awarded any discount.</div>';
                    }
                  }
               ?>
              </div>
              <?php 
               
              /*if($net_amount == 0 && $overall_discount == 0){*/
                ?>
                <div class="row" id='operation_button'>
                        <div class="col-md-3">

                        <button class="btn btn-success" id="fee-submission-submit-fee-button">Submit Fee</button>
                        <!-- <input type="button" data-url="<?php //echo Url::to(['fee/fee-submit']) ?>" class="btn btn-success" value="Submit Fee" id="submitFee"> -->
                        </div> 
                        <div class="col-md-3"> 
                      <?php
                    //} ?>
                     <input type="button" name="Generate Report" id="singleFeeSlip" class="btn btn-info" data-url="<?= Url::to(['fee/generate-single-fee-pdf']) ?>" value="Generate Fee Slip">
                  </div>
                  <div class="col-md-4">
                      <?= Html::a('Add Extra Head','javascript:void(0);', ['title'=>'Add Head','class' => 'btn btn-danger',/*'disabled'=>($net_amount-$total_amount <= 0)?true:false,*/'id'=>'add-extra-fee-head'])
                      ?>
                    </div>
                   
                </div>
               <?php/* }*/ ?>
                  <?php ActiveForm::end(); ?>   
      </div>
        </div>
              
              <?php
Modal::begin([
    'header'=>'<h4>ADD Head</h4>',
    'id'=>'modal-extera-head',
    'options'=>[
        'data-keyboard'=>false,
        'data-backdrop'=>"static",
        'style'=>"color:green",
 
    ],
    'size'=>'modal-md',
    'footer' =>'<button type="button" class="btn btn-danger pull-left" data-dismiss="modal">Close</button>'.Html::a('Submit Head','javascript:void(0);', ['class' => 'btn btn-success pull-left','id'=>'submit-extra-head','data-url'=>Url::to(['submit-extra-head'])]).Html::a('Add Head','javascript:void(0);', ['class' => 'btn btn-primary pull-right','id'=>'save-extra-fee-head']),

]);
?>
<input type="hidden" name="" id="stu_id_extra_head" value="<?php echo $student_id ?>">
<div class="row">
    <div class="col-md-6 ex_head_division">
        <?php
        /*echo Html::dropDownList('s_id', null,$exteraHeadArrayMap,['class'=>'form-control','prompt'=>'Select Head...','id'=>'ex_head'])*/

        ?>
        <select id="ex_head" class="form-control" name="s_id">
            <option value="">Select Head...</option>
            <?php 
            foreach ($exteraHeadArrayMap as $exhead){
                $check='';
                if(count($custom_ext_head_arr)>0){
                    if(in_array($exhead['id'],$custom_ext_head_arr,true)){
                        $check ='disabled="disabled"';
                    }else{
                        $check='';
                    }
                }
                echo '<option value="'.$exhead['id'].'" '.$check.'>'.$exhead['title'].'</option>';
            }
            ?>
        </select>
        <div class="help-block"></div>
        <div id="extra_head_mesg" style="color: red;font-weight: bold;"></div>
    </div>
    <div class="col-md-6 ex_head_amount">
        <?=Html::input('number','extra_head_amount',null,['class'=>'form-control','placeholder'=>'Head Amount','id'=>'ex_head_amount'])?>
        <div class="help-block"></div>
    </div>
</div>
<?php

Modal::end(); 
?>
<script>
 // var alertFee     = <?=$alert?>;
  var hostelFee    = <?=$hostel_amount?>;
  var transportFee = <?=$transport_amount?>;
  var alertFee     = <?=$net_amount?>;
  var overAllDiscount     = <?=$overall_discount?>;
  var transportAmount = <?=$transport_amount?> 
  var fineAmount = <?=$fine_amount?> 
      if(alertFee == 0 &&  (transportFee == 0 || hostelFee == 0 || fineAmount == 0) &&overAllDiscount==0){ 
        $('.table-responsive').hide();
        //$('#operation_button').hide();
      }else{ 
        $('.table-responsive').show();
       // $('#operation_button').show();
      } 

</script>
