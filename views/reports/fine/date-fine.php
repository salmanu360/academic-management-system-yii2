<?php 
use yii\helpers\Url;?>
<div class="table-responsive">
<?php if(count($dateFine)>0){ ?>
<a href="<?= Url::to(['fine-date-pdf','start'=>$startcnvrt,'end'=>$endcnvrt]) ?>" class="btn btn-primary btn-sm pull-right">
              <i class="fa fa-download"> Generate Report</i></a>
	<table class="table table-stripped">
	<thead>
		<tr>
			<th>Sr.</th>
			<th>Reg. No.</th>
			<th>Student</th>
			<th>Father</th>
			<th>Class</th>
			<th>Fine Type</th>
			<th>Fine</th>
		</tr>
	</thead>
	<tbody>
		<?php 
		$total=0;
		$i=0;foreach ($dateFine as $key => $value) {
			$i++;
			$total=$total+$value->payment_received;
			?>
		<tr>
			<td><?php echo $i; ?></td>
			<td><?php echo Yii::$app->common->getUserName($value->fk_stu_id) ?></td>
			<td><?php echo Yii::$app->common->getName($value->fk_stu_id) ?></td>
			<td><?php echo Yii::$app->common->getParentName($value->fkStudent->stu_id) ?></td>
			<td><?php echo Yii::$app->common->getCGSName($value->fkStudent->class_id,$value->fkStudent->group_id,$value->fkStudent->section_id) ?></td>
			<td><?php echo $value->fineType->title ?></td>
			<td>Rs. <?php echo $value->payment_received ?></td>
		</tr>
		<?php } ?>
		<tr>
			<th colspan="3"></th>
			<th colspan="2">Total</th>
			<th colspan="2">Rs. <?php echo $total ?></th>
		</tr>
	</tbody>
</table>
<?php }else{ 
echo '<div class="alert alert-danger">No Record Found..!</div>';
}?>
</div>