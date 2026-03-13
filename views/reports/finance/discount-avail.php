<?php 
if (isset($_GET['id'])){?>
<style type="text/css">
  *{ margin:0; padding:0;}
  th, tr, td  {
    border:0.3px solid black;
    padding:5px;
    font-size:0.8em;
  }

  tr:nth-child(even){background-color: #f2f2f2}
  #first{ min-width: 15px; width: 15px; }
</style>
    <div style="width: 100%; text-align: center; background-color: #337ab7; color: #000; font-size:14px;">
      <h2 style="font-size:16px; font-weight:700; color:#000000; text-transform:capitalize;margin: 0;padding: 15px 0 8px 0;">
        <?=Yii::$app->common->getBranchDetail()->address?>
    </h2>
</div>
<h3 style='text-align:center'>Discount Avail by <?php
$class_details=\app\models\RefClass::find()->where(['class_id'=>$class_id])->one();
 echo strtoupper($class_details->title); ?></h3>
<?php }else{?>
    <div class="row">
        <div class="col-md-2 pull-right">
            <a href="<?=\yii\helpers\Url::to(['reports/discount-avail/','id'=>$class_id]) ?>" name="Generate Report" class="btn btn-primary pull-right"><i class="fa fa-download"></i> Generate Report</a>
        </div>
    </div>
<?php } ?>
<div class="table-responsive">
    <table class="table table-bordered" style="padding-top: -10px">
        <thead>
           <tr style="background: #3c8dbc">
            <th>SR</th>
            <th>Roll #</th>
            <th>Reg No.</th>
            <th>Student</th>
            <th>Parent</th>
            <th colspan="2">Head Discount</th>
            <th>Sibling</th>
            <th>Transport Discount</th>
            <!-- <th>Hostel Discount</th> -->
        </tr>
    </thead>
    <tbody>
        <?php $i=1;
        $totalHead=0;
        $totalTransport=0;
        $totalSibling=0;
        foreach ($studentTable as $key => $studentTableValue) {
         $settings = Yii::$app->common->getBranchSettings();
         $trasnportDiscount=\app\models\TransportAllocation::find()->where(['stu_id'=>$studentTableValue->user_id])->one();
         $fee_plan=\app\models\FeePlan::find()->where(['stu_id'=>$studentTableValue->stu_id,'status'=>1])->all(); 
         $fee_planOne=\app\models\FeePlan::find()->where(['stu_id'=>$studentTableValue->stu_id,'status'=>1])->one();
         $getHeadSiblings=\app\models\FeeHead::find()->where(['branch_id'=>yii::$app->common->getBranch()])->one();
         $getFeeDetails = \app\models\FeeGroup::find()
         ->where([
             'fk_branch_id'  =>Yii::$app->common->getBranch(),
             'fk_class_id'   => $studentTableValue['class_id'],
             'fk_fee_head_id'   => $getHeadSiblings['id'],
             'fk_group_id'   => ($studentTableValue['group_id'])?$studentTableValue['group_id']:null,
         ])->one();
         if($fee_planOne['discount'] == 0 && $trasnportDiscount['discount_amount'] == 0 && $studentTableValue->avail_sibling_discount == 0){}else{
            $totalTransport=$totalTransport+$trasnportDiscount['discount_amount'];

            ?>
            <tr>
             <td><?php echo $i; ?></td>
             <td><?php echo $studentTableValue->roll_no; ?></td>
             <td><?= yii::$app->common->getUserName($studentTableValue->user_id) ?></td>
             <td><?= yii::$app->common->getName($studentTableValue->user_id) ?></td>
             <td><?= yii::$app->common->getParentName($studentTableValue->stu_id) ?></td>

             <td colspan="2">
                <?php foreach ($fee_plan as $key => $value):
                    $totalHead=$totalHead+$value['discount'];
                    $getHead=\app\models\FeeHead::find()->where(['branch_id'=>yii::$app->common->getBranch(),'id'=>$value['fee_head_id']])->one();
                    $discountName=\app\models\FeeDiscountTypes::find()->where(['id'=>$value['fk_fee_discounts_type_id']])->one();
                    ?>
                    <?php echo strtoupper($getHead['title']) .' :Rs. '. $value['discount'] .' ('.$discountName['title'] .')<br>' ?>
                <?php endforeach ?>
            </td>
            <td><?php if($studentTableValue->avail_sibling_discount == 1 && $getFeeDetails->fk_fee_head_id == $getHeadSiblings->id && $getHeadSiblings->sibling_discount ==1){
                echo $amount=$getFeeDetails->amount*$settings->sibling_discount/100;
                $totalSibling=$totalSibling+$amount;
            }?></td>
            <td><?php echo ($trasnportDiscount['discount_amount'])?$trasnportDiscount['discount_amount']:'N/A'; ?></td>
        </tr>
        <?php $i++;} }?>
        <tr>
         <th colspan="5">Grand Total</th>
         <th colspan="2">Rs. <?php echo $totalHead; ?></th>
         <th>Rs. <?php echo $totalSibling; ?></th>
         <th>Rs. <?php echo $totalTransport; ?></th>
     </tr>
     <tr>
         <th colspan="5">Total</th>
         <th colspan="4">Rs. <?php echo $totalHead+$totalSibling+$totalTransport; ?></th>
     </tr>
 </tbody>
</table>
</div>