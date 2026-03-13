<?php 
if (isset($_GET['id'])){?>
<style type="text/css">
  *{ margin:0; padding:0;}
  th, tr, td  {
    border:0.3px solid black;
    padding:5px;
    font-size:0.9em;
  }

  tr:nth-child(even){background-color: #f2f2f2}
  #first{ min-width: 15px; width: 15px; }
</style>
<div style="width: 100%; text-align: center; background-color: #337ab7; color: #000; font-size:14px;">
  <h2 style="font-size:16px; font-weight:700; color:#000000; text-transform:capitalize;margin: 0;padding: 15px 0 8px 0;">
    <?=Yii::$app->common->getBranchDetail()->address?>
</h2>
</div>

<?php }else{?>

<a href="<?=\yii\helpers\Url::to(['reports/discount-avail/','id'=>$class_id]) ?>" name="Generate Report" class="btn btn-primary pull-right"><i class="fa fa-download"></i> Generate Report</a>
<?php } ?>
<h5 style='text-align:center;color: red'>Discount Avail by  
<?php 
$classDetails=\app\models\RefClass::find()->where(['class_id'=>$class_id])->one();
echo strtoupper($classDetails->title);?> Students
</h5>
    <table class="table table-bordered" style="padding-top: -10px">
        <thead>
           <tr style="background: #3c8dbc">
            <th>SR</th>
            <th>Roll #</th>
            <th>Reg No.</th>
            <th>Student</th>
            <!-- <th>Parent</th> -->
            <th>Fee Head</th>
            <th>Head Discount</th>
            <th>Discount Name</th>
            <th>Sibling</th>
            <th>Transport Discount</th>
            <th>Hostel Discount</th>
        </tr>
    </thead>
    <tbody>
        <?php 
        $i=0;
        $totalHeadAmount=0;
        $totaltransprtAmount=0;
        $totalhostelAmount=0;
        $totalSiblings=0;
        $rollNoAlotment=0;
        //echo '<pre>';print_r($studentTable);die;
        foreach ($studentTable as $studentTableValue) {
        $availSiblings=$studentTableValue['avail_sibling_discount'];
        $settings = Yii::$app->common->getBranchSettings();
        $userTable=\app\models\User::find()->select(['username'])->where(['id'=>$studentTableValue['user_id']])->one();
        $trasnportDiscount=\app\models\TransportAllocation::find()->where(['stu_id'=>$studentTableValue['user_id']])->one();
            $hostelDiscount=\app\models\HostelDetail::find()->where(['fk_student_id'=>$studentTableValue['user_id']])->one();
            
        $totaltransprtAmount=$totaltransprtAmount+$trasnportDiscount['discount_amount'];
            $totalhostelAmount=$totalhostelAmount+$hostelDiscount['discount_amount'];
             $discountName=\app\models\FeeDiscountTypes::find()->where(['id'=>$studentTableValue['fk_fee_discounts_type_id']])->one();
            $getHead=\app\models\FeeHead::find()->where(['branch_id'=>yii::$app->common->getBranch(),'id'=>$studentTableValue['fee_head_id']])->one(); 
            $getHeadSiblings=\app\models\FeeHead::find()->where(['branch_id'=>yii::$app->common->getBranch()])->one();
            $getFeeDetails = \app\models\FeeGroup::find()
        ->where([
         'fk_branch_id'  =>Yii::$app->common->getBranch(),
         'fk_class_id'   => $studentTableValue['class_id'],
         'fk_fee_head_id'   => $getHeadSiblings->id,
         'fk_group_id'   => ($studentTableValue['group_id'])?$studentTableValue['group_id']:null,
         ])->one();
            $totalHeadAmount=$totalHeadAmount+$studentTableValue['discount'];
            if($studentTableValue['discount'] == 0 && $trasnportDiscount['discount_amount'] == 0 && $hostelDiscount['discount_amount']== 0 && $availSiblings == 0 && $studentTableValue['status'] == 0){
                continue;
            }else{
                $i++;
                ?>
                <tr>
                    <?php //if(empty($rollNoAlotment)){ ?>
                    <td><?=$i ?></td>
                    <td><?= (count($studentTableValue['roll_no'])>0)?$studentTableValue['roll_no']:'N/A'; ?></td>
                    <td><?= yii::$app->common->getUserName($studentTableValue['user_id']) ?></td>
                    <td><?= yii::$app->common->getName($studentTableValue['user_id']) ?></td>
                    <!-- <td><?//= yii::$app->common->getParentName($studentTableValue['stu_id']) ?></td> -->
                    <?php  ///}else if($rollNoAlotment != $userTable->username){ ?>
                    <!--  <td><?///=$i ?></td>
                                        <td><?//= (count($studentTableValue['roll_no'])>0)?$studentTableValue['roll_no']:'N/A'; ?></td>
                                        <td><?//= yii::$app->common->getName($studentTableValue['user_id']) ?></td>
                                        <td><?//= yii::$app->common->getParentName($studentTableValue['stu_id']) ?></td>
                                        <?php// }else{ ?>
                                        <td><?//=$i ?></td>
                                        <td colspan='3'></td> 
                                        <?php //} ?> -->
                    
                    <td><?=$getHead['title'] ?></td>
                    <td>Rs. <?=(count($studentTableValue['discount'])>0)?$studentTableValue['discount']:'N/A'; ?></td>
                    <td><?php echo $discountName['title']?></td>
                    <td><?php if($studentTableValue['avail_sibling_discount'] == 1 && $getFeeDetails->fk_fee_head_id == $getHeadSiblings->id && $getHeadSiblings->sibling_discount ==1){
                        echo $amount=$getFeeDetails->amount*$settings->sibling_discount/100;
                        $totalSiblings=$totalSiblings+$amount=$getFeeDetails->amount*$settings->sibling_discount/100;
                    }else{
                        echo 'N/A';
                    } ?></td>
                    <?php //if($rollNoAlotment != $userTable->username){ ?>
                     <td>Rs. <?php 
                      if(count($trasnportDiscount)>0){
                        echo (count($trasnportDiscount['discount_amount'])>0)? $trasnportDiscount['discount_amount']:'N/A'; }else{echo 0;}?></td>
                   <!--  <?php //}else{ ?>
                       <td></td>
                       <?php //} ?> -->
                        <td>Rs. <?php 
                        if(count($hostelDiscount)>0){
                            echo (count($hostelDiscount['discount_amount'])>0)?$hostelDiscount['discount_amount']:'N/A';}else{echo 'N/A';} ?></td>
                        </tr>
                        <?php 
                        $rollNoAlotment=$userTable['username'];
                    }
                    }

                 ?>
                <?php if($totalHeadAmount == 0 && $totalSiblings == 0 && $totaltransprtAmount==0 && $totalhostelAmount == 0){
                    echo 'No details Found..!';
                }else{ ?>
                <tr>
                   <th colspan="5">Grand Total</th>
                   <th>Rs. <?=$totalHeadAmount; ?></th>
                   <td></td>
                   <th>Rs. <?= $totalSiblings;?></th>
                   <th>Rs. <?=$totaltransprtAmount; ?></th>
                   <th>Rs. <?=$totalhostelAmount; ?></th>
               </tr>
               <tr>
                   <th colspan="5">Total</th>
                   <th colspan="5">Rs. <?= $totalHeadAmount + $totalSiblings + $totaltransprtAmount + $totalhostelAmount  ?></th>
               </tr>
               <?php } ?>
           </tbody>
       </table>