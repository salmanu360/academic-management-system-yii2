<!DOCTYPE html>
<html>
<head>
<style>
.customers {
    font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
    border-collapse: collapse;
    width: 100%;
    color: black;
}

.customers td, .customers th {
    border: 0.4px solid #ddd;
    padding: 5px;
    color: black;
    font-size: 13px;
}

.customers tr:nth-child(even){background-color: #f2f2f2;}

.customers tr:hover {background-color: #ddd;}

.customers th {
    padding-top: 5px;
    padding-bottom: 8px;
    text-align: left;
   /* background-color: #4CAF50;*/
    color: black;
}
</style>

</head>
<body>
 <?php 
         use yii\widgets\ActiveForm;
         $copies = Yii::$app->common->getBranchSettings()->challan_copies;
        $this->registerCss(" 

        @media print{    
            .footer{
                display:none;
            }
            header {
                display:none;
            }
        }
        ");
        for ($itrration=1;$itrration<=$copies;$itrration++) {
        if ($itrration == 1) {
            $copy = 'Student Copy';
        } else if ($itrration == 2) {
            $copy = 'School Copy';
        } else {
            $copy = 'Bank Copy';
        }
            $transport_head_amount = 0;
            $transport_discount_amount = 0;
            $hostel_head_amount = 0;
            $hostel_dscount_amount = 0;

        $settings = Yii::$app->common->getBranchSettings();
        $studentsinfo=\app\models\StudentInfo::find()->where(['stu_id'=>$student_id])->one();
            /*transport/hostel discount details*/
            $transport        = \app\models\TransportAllocation::find()->where(['stu_id'=>$studentsinfo->user_id,'status'=>1])->one();
            $hostel           = \app\models\HostelDetail::find()->where(['fk_student_id'=>$student_id, 'is_booked'=>1])->one();
            /*check if student avail transport*/
            if(count($transport)>0){
                $stop = \app\models\Stop::find()->where(['id'=>$transport->fk_stop_id])->One();
                if(count($stop)>0){
                    $transport_head_amount = $stop->fare;
                }
            }
            /*check if studen avail hostel*/
            if(count($hostel)>0){
                $hostel_details = \app\models\Hostel::find()->where(['id'=>$hostel->fk_hostel_id])->One();
                //if(count($stop)>0){
                $hostel_head_amount = $hostel_details->amount;
                //}
            }
            /*transport/hostel discount details end*/
         ?>
            <div style="border-bottom:1px dashed #3f3c8b;">
                <div style="width: 100%; text-align: center; background-color: #3f3c8b; color: #fff; font-size:14px;">
                    <h2 style="font-size:16px; font-weight:600; color:#FFFFFF; text-transform:capitalize;margin: 0;padding: 15px 0 8px 0;"><?=strtoupper(str_replace('-',' ',Yii::$app->common->getBranchDetail()->address))?></h2>
                </div>
                <h4 style='text-align:center'>Fee Slip for the Month of <?php echo (date('M-Y',strtotime($Fromdate)) == date('M-Y',strtotime($toDate)))? date('M-Y',strtotime($Fromdate)) : date('M-Y',strtotime($Fromdate)) .' - '. date('M-Y',strtotime($toDate)) ?> (<b>Generated on :</b> <?php echo date('d-M-Y') ?>)
                </h4>
                <div style="margin:0;float:left; font-size:13px; text-align: center; font-weight: bold;">
            <?php
            if(!empty(Yii::$app->common->getBranchSettings()->fee_bank_name) && !empty(Yii::$app->common->getBranchSettings()->fee_bank_account)) {
                ?>
                <?= ucfirst(Yii::$app->common->getBranchSettings()->fee_bank_name) ?> 
                A/C No: <?=  Yii::$app->common->getBranchSettings()->fee_bank_account?>
                <?php
            }
            ?>
            </div>
                <div style="width: 100%; text-align: center; padding-bottom:10px;">
                    <h2 style="font-size:17px; width: 100%; text-transform:capitalize;margin: 0;padding:5px 0;"><?=$copy?></h2>
                </div>
                <div style="width: 50%; float:left;">
                <table class="customers">
                  <tr>
                    <th>Name</th>
                    <th><strong> <?= Yii::$app->common->getName($studentsinfo->user_id); ?></strong></th>
                </tr>
                <tr>
                    <th>Parent</th>
                    <th><?= Yii::$app->common->getParentName($studentsinfo->stu_id); ?></th>
                </tr>
                <tr>
                    <th>Reg No.</th>
                    <th><strong><?=$studentsinfo->user->username?></strong></th>
                </tr>
                <tr>
                    <th>Roll No.</th>
                    <th><strong><?=(count($studentsinfo->roll_no)>0)?$studentsinfo->roll_no:'N/A'?></strong></th>
                </tr>
                <tr>
                    <th><?=Yii::t('app','Class')?></th>
                    <th><strong><?= strtoupper(Yii::$app->common->getStudentCGSection($student_id) ) ?></strong></th>
                </tr>
                  </tr>
                </table>
                <br />
            <div style="float: left;">Accountant:_____________________</div><br>

                </div>
                <div style="width: 46%; float:right; background:none; font-size:13px;">
                <table class="customers">
            <thead>
            <tr>
                <th>Fee Head</th>
                <th>Amount</th>
                <th>Arrears</th>
                <th>Head Detail</th>
            </tr>
            </thead>
            <tbody>
            <?php 
            $amount_payable = 0;
            $total_arrears_amount=0;
            foreach ($transaction_head_amount as $key => $tamount) {
                if($siblings_details > 0){
                    if(isset($siblings_details['sibling_discount'][$key])){
                        $tamount= $tamount - $siblings_details['sibling_discount'][$key];
                    }  
                }
                
             $getHeadName=\app\models\FeeHead::find()->where(['branch_id'=>yii::$app->common->getBranch(),'id'=>$key])->one();                                      $total_fee_amount=\app\models\FeeGroup::find()->select(['amount'])->where(['fk_branch_id'=>yii::$app->common->getBranch(),'fk_fee_head_id'=>$key,'fk_class_id'=>$class_id])->one();
             ?>
             <tr>
                 <td><?php echo strtoupper($getHeadName->title); ?></td>
                 <td>Rs.<?php echo $tamount; ?></td>
                 <td>Rs.<?=(isset($transaction_head_arrears_amount[$key]) && $transaction_head_arrears_amount[$key]>0)?$transaction_head_arrears_amount[$key]:0?></td>
                 <td>
                    Rs.<?php
                     //if(empty($diff)){echo $total_fee_amount['amount'];}
                     if($getHeadName->one_time_payment == 1){
                         
                        echo $total_fee_amount['amount'];
                     }else{  
                         if($diff==1 || empty($diff)){ 
                             echo $total_fee_amount['amount'];
                         }
                         if($diff>1){
                            if($getHeadName->extra_head == 1){}else{
                             echo $total_fee_amount['amount']." x ".$diff." = ".$total_fee_amount['amount']*$diff;
                            }
                         }
                     }
                     ?>
                 </td>
             </tr>
            
                <?php 
                $amount_payable = $amount_payable +$tamount;
             }
             /*transport Arrears*/  
            if($transport_amount >0 || $transport_arrears>0){
                if($transport->discount_amount >0) {
                    $transport_discount_amount = $transport_head_amount - $transport->discount_amount;
                }
                ?>
                <tr>
                    <td>Transport fare</td>
                    <td>Rs.<?php echo $transport_amount; ?></td>
                    <td>
                        <?php
                        if($transport_arrears>0){
                            echo "Rs.".$transport_arrears;
                        }else{
                            echo "Rs.0";
                        }
                        ?>
                    </td>
                    <td>
                        <?php
                        if($diff==1){
                            echo ($transport_discount_amount>0)?$transport_discount_amount:'';
                        }
                        if($diff>1){
                            echo ($transport_discount_amount>0)?$transport_discount_amount." x ".$diff." = ".$transport_discount_amount*$diff:'';
                        }
                        ?>
                    </td>
                    </td>
                 </tr> 
                <?php
                $amount_payable = $amount_payable + $transport_amount;
            }
            /*hostel Arrears*/
            if($hostel_amount >0 || $hostel_arrears >0){
                if($hostel->discount_amount>0){
                    $hostel_dscount_amount = $hostel_head_amount-$hostel->discount_amount;
                }
                ?>
                <tr>
                    <td>Hostel fare</td>
                    <td>Rs. <?php echo $hostel_amount; ?></td>
                    <td>
                        <?php
                        if($hostel_arrears > 0){
                            echo "Rs.".$hostel_arrears;
                        }else{
                            echo "Rs.0";
                        }
                        ?>
                    </td>
                    <td>
                        <?php
                        if($diff==1){
                            echo ($hostel_dscount_amount>0)?$hostel_dscount_amount:'';
                        }
                        if($diff>1){
                            echo ($hostel_dscount_amount>0)?$hostel_dscount_amount." x ".$diff." = ".$hostel_dscount_amount*$diff:'';
                        }
                        ?>
                    </td>
                    </td>
                 </tr> 
                <?php
                $amount_payable = $amount_payable + $hostel_amount;
            }
            if($absent_fine >0){
                ?>
                <tr>
                    <td>Absent fine</td>
                    <td>Rs.<?php echo $absent_fine; ?></td>
                    <td></td>
                 </tr> 
                <?php
                $amount_payable= $amount_payable+$absent_fine;
            }
            /*if there's any onetime arrears*/
            if(count($ontime_arears)>0){
                foreach ($ontime_arears as $key => $oneTimeArrears) {
                    if($total_arrears_amount != null){
                        $total_arrears_amount = $total_arrears_amount + intval($oneTimeArrears['arears']);
                    }else{
                        $total_arrears_amount = intval($oneTimeArrears['arears']);
                    }
                    ?>
                    <tr>
                    <td> Arrears </td>
                    <td>Rs.<?php echo $oneTimeArrears['arears']; ?></td>
                    <td>
                        <?php
                        echo '&nbsp;';
                        ?>
                    </td>
                    <td>
                        <?php
                        echo '&nbsp;';
                        ?>
                    </td>
                    </td>
                 </tr> 
                    <?php
                }
            }
             ?>

             <tr>
               <th>Amount Payable:</th>
                <th>Rs.<?=$amount_payable?></th>
                <td></td>
                <td></td>
             </tr>
            <tr>
                <th>
                    <?= (count($total_arrears_amount)>0)? 'Arrears:' :''; ?></th>
                <th>Rs.<?= $total_arrears_amount; ?></th>
                <td></td>
                <td></td>
            </tr>
            </tbody>
            </table>
            
            </div>
             </div>
            <?php }?>
</body>
</html>