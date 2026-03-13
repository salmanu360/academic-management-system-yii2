<?php // new code only monthly fee ?>
<?php use yii\helpers\Url; 
use yii\widgets\ActiveForm;?>
<div class='box box-default'>
<div class='box-body'>
	<div class="table-responsive">
	<?php $form = ActiveForm::begin(['action'=>Url::to(['fee/move-arrears-submit'])]); ?>
	<table class="table table-striped" width="100%">
		<thead>
			<tr class="info">
			<td>Sr.</td>
			<td>Roll No.</td>
			<td>Reg No.</td>
			<td>Name</td>
			<td>Parent</td>
			<td>Fee</td>
			<td>Transport</td>
		</tr>
		</thead>
		<tbody>
<?php 
 $date=date('Y-m');
//echo '<pre>';print_r($studentFeeDetails);
$i=0;foreach ($studentFeeDetails as $key => $values) {
$fee_arrears=\app\models\FeeArears::find()->where(['stu_id'=>$values['stu_id']])
->andWhere(['like','date',$date])
->one();
$fee_submssion=\app\models\FeeSubmission::find()
->where(['stu_id'=>$values['stu_id']])
 ->andWhere('find_in_set("'.$date.'",year_month_interval)')
->one();
if(count($fee_submssion)>0){
	continue;
}
if(count($fee_arrears)>0){
	continue;
}
 $i++;
 $settings = Yii::$app->common->getBranchSettings();
 $getHead=\app\models\FeeHead::find()->where(['branch_id'=>yii::$app->common->getBranch(),'extra_head'=>0,'one_time_payment'=>0,'promotion_head'=>0,])->one();
 $getFeeDetails = \app\models\FeeGroup::find()
        ->where([
         'fk_branch_id'  =>Yii::$app->common->getBranch(),
         'fk_class_id'   => $class_id,
         'fk_fee_head_id'   => $getHead->id,
         'fk_group_id'   => ($group_id)?$group_id:null,
         ])->all();
	?>
		<tr>
			<td><?php echo $i; ?></td>
			<td><?php echo $values['roll_no']; ?></td>
			<td><?php echo Yii::$app->common->getUserName($values['user_id']) ?></td>
			<td><?php echo Yii::$app->common->getName($values['user_id']) ?></td>
			<td><?php echo Yii::$app->common->getParentName($values['stu_id']) ?></td>
			<td>
			<?php foreach ($getFeeDetails as $key => $getFeeDetailsvalue) { 
			
			$getDiscountPercent=\app\models\FeePlan::find()->where(['fee_head_id'=>$getFeeDetailsvalue['fk_fee_head_id'],'stu_id'=>$values['stu_id'],'status'=>1])->one();
			$student_details = Yii::$app->common->getStudent($values['stu_id']);
			$head_arrears_previous = \app\models\FeeArears::find()->where(['stu_id'=>$values['stu_id'],'fee_head_id'=>$getFeeDetailsvalue['fk_fee_head_id'],'status'=>1,'branch_id'=>yii::$app->common->getBranch()])->one();
			// for transport & hostel arrears
			$feeSubmisstionTblArrears=\app\models\FeeSubmission::find()->where(['stu_id'=>$values['stu_id'],'branch_id'=>yii::$app->common->getBranch(),'fee_status'=>1])->one();
			// for transport & hostel arrears ends
			$transport= \app\models\TransportAllocation::find()->where(['stu_id'=>$student_details['user_id'],'status'=>1])->one();
			//if(count($transport)>0){
			  $stop = \app\models\Stop::find()->where(['id'=>$transport['fk_stop_id']])->One();
			  //if(count($stop)>0){
			    $transport_amount = $stop['fare'];
			 // }
			//}
			
			/*if head is one time*/
              if($getHead['one_time_payment'] == 1){
			$onetimeFees=\app\models\FeeSubmission::find()->where(['stu_id'=>$values['stu_id'],'branch_id'=>yii::$app->common->getBranch(),'fee_head_id'=>$getFeeDetailsvalue['fk_fee_head_id']])->one();
			$one_time_receive = (count($onetimeFees)>0)?1:0;
		}
			
              
             if($values['avail_sibling_discount']==1 && $getFeeDetailsvalue['fk_fee_head_id'] == $getHead['id'] && $getHead['sibling_discount'] ==1 ){
					 $amount_sibling=$getFeeDetailsvalue['amount']*$settings->sibling_discount/100;
                     $amount=$getFeeDetailsvalue['amount'] - $amount_sibling;
					}else{
						$amount=$getFeeDetailsvalue['amount'];
					}
			
			?>
				<?php echo $getHead['title'] ?> : 
				<?php $amountget= $amount - $getDiscountPercent['discount'] + $head_arrears_previous['arears'];
				echo round($amountget,0);
				/*if($values['avail_sibling_discount']==1 && $getFeeDetailsvalue['fk_fee_head_id'] == $getHead['id'] && $getHead['sibling_discount'] ==1 ){
					 $sibling_amount=$getFeeDetailsvalue['amount']*$settings->sibling_discount/100;
					echo $sibling_amount - $getDiscountPercent['discount'] + $head_arrears_previous['arears'];
				}else{
				echo $getFeeDetailsvalue['amount'] - $getDiscountPercent['discount'];
				}*/
				 ?>
				 <input type="hidden" name="fee[<?php echo $values['stu_id'] ?>][<?php echo $getFeeDetailsvalue['fk_fee_head_id'] ?>]" value="<?php echo round($amountget,0); ?>">
				 <input type="hidden" name="studentId" value="<?php echo $values['stu_id']?>">
			<?php } ?>
			</td>

			<td><?php echo $transport_amount - $transport['discount_amount'] + $feeSubmisstionTblArrears['transport_arrears'] ?></td>

			<input type="hidden" name="transport[<?php echo $values['stu_id'] ?>]" value="<?php echo $transport_amount - $transport['discount_amount'] + $feeSubmisstionTblArrears['transport_arrears']; ?>">
				
			
		</tr>
<?php }?>
	</tbody>
	</table>
	<div class="row">
		<div class="col-md-2"><button type="submit" class="btn btn-success" onclick="return confirm('Are your sure you want to move arrears to next comming month.. make sure the changes will not be reversed..!')">Move To arrears</button></div>
	</div>
<?php ActiveForm::end(); ?>  
</div>
</div>
</div>