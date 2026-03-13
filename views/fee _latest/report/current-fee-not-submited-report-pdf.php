<?php use yii\helpers\Url; 
use yii\widgets\ActiveForm;?>
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
<h3 style='text-align:center;color: black;padding-top: -10px'>Fee Not Submitted in <?php echo date('M-Y') ?> <?php echo Yii::$app->common->getCGSName($class_id,$group_id,$section_id) ?></h3>

	<div class="table-responsive">
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
//echo '<pre>';print_r($studentFeeDetails);
$totalHead=0;
$tranportTotal=0;
$i=0;
foreach ($studentFeeDetails as $key => $values) {
 $i++;
 $settings = Yii::$app->common->getBranchSettings();
 $getFeeDetails = \app\models\FeeGroup::find()
        ->where([
         'fk_branch_id'  =>Yii::$app->common->getBranch(),
         'fk_class_id'   => $class_id,
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
			$getHead=\app\models\FeeHead::find()->where(['branch_id'=>yii::$app->common->getBranch(),'id'=>$getFeeDetailsvalue['fk_fee_head_id']])->one();
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
			/*if promotion head is active*/ 
			   $sessionEndDate=$settings->current_session_end;
              $promotedData=\app\models\StuRegLogAssociation::find()->where(['fk_stu_id'=>$values['stu_id']])->andWhere(['>=','date(promoted_date)',$sessionEndDate])->count();
               /*$year=date('Y');  
              $promotedData=\app\models\StuRegLogAssociation::find()->where(['fk_stu_id'=>$values['stu_id']])->andWhere(['YEAR(promoted_date)'=>$year])->count();*/
              if($getHead->promotion_head == 1){
                $promotionFeeReceive =\app\models\FeeSubmission::find()->where(['stu_id'=>$values['stu_id'],'branch_id'=>yii::$app->common->getBranch(),'fee_head_id'=>$getFeeDetailsvalue['fk_fee_head_id'],'YEAR(recv_date)'=>$year])->sum('head_recv_amount');
                $promotion_fee_receive = ($promotionFeeReceive>0)?1:0;
                if($promotedData==0){  
                  continue;
                }                
              }
              /*if promotion head is active ends*/
               /*temproary avoid admission fee*/
                if($getHead['one_time_payment'] ==1 && $getHead['title'] =='Admission Fee' && $promotedData >0 ){
                  continue;
                }
              /*temproary avoid admission fee ends*/
              
             if($values['avail_sibling_discount']==1 && $getFeeDetailsvalue['fk_fee_head_id'] == $getHead['id'] && $getHead['sibling_discount'] ==1 ){
					 $amount=$getFeeDetailsvalue['amount']*$settings->sibling_discount/100;
					}else{
						$amount=$getFeeDetailsvalue['amount'];
					}
			if(($getHead['one_time_payment'] ==1 && $one_time_receive==1 && count($head_arrears_previous)==0)){
              	continue;
              }else if(($getHead->one_time_payment ==1 && $one_time_receive==1 && count($head_arrears_previous)==1)){
              	 	 $amount=0;
              	 }
			?>
				<?php echo $getHead['title'] ?> : 
				<?php echo $totalAmount=$amount - $getDiscountPercent['discount'] + $head_arrears_previous['arears'];
				$totalHead=$totalHead+$totalAmount;
				/*if($values['avail_sibling_discount']==1 && $getFeeDetailsvalue['fk_fee_head_id'] == $getHead['id'] && $getHead['sibling_discount'] ==1 ){
					 $sibling_amount=$getFeeDetailsvalue['amount']*$settings->sibling_discount/100;
					echo $sibling_amount - $getDiscountPercent['discount'] + $head_arrears_previous['arears'];
				}else{
				echo $getFeeDetailsvalue['amount'] - $getDiscountPercent['discount'];
				}*/
				 ?>
				 <input type="hidden" name="fee[<?php echo $values['stu_id'] ?>][<?php echo $getFeeDetailsvalue['fk_fee_head_id'] ?>]" value="<?php echo $amount - $getDiscountPercent['discount'] + $head_arrears_previous['arears']; ?>">
				 <input type="hidden" name="studentId" value="<?php echo $values['stu_id']?>">
			<?php } ?>
			<td><?php echo $transportAmount=$transport_amount - $transport['discount_amount'] + $feeSubmisstionTblArrears['transport_arrears'];
			$tranportTotal=$tranportTotal+$transportAmount;
			?></td>
			<input type="hidden" name="transport[<?php echo $values['stu_id'] ?>]" value="<?php echo $transport_amount - $transport['discount_amount'] + $feeSubmisstionTblArrears['transport_arrears']; ?>">
				</td>
			
		</tr>

<?php }?>
		<tr>
			<th colspan="3">Grand Total</th>
			<th colspan="4">Rs <?php echo $totalHead+$tranportTotal ; ?></th>
		</tr>
	</tbody>
	</table>
	
</div>