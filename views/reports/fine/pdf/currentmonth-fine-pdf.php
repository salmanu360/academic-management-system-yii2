<?php use yii\helpers\Url;?>  
<style type="text/css">
*{ margin-left:0; padding:0;}
th, tr, td  {
  border:0.7px solid black;
  padding:6px;
  font-size:0.9em;
}
table{width:100%;}
</style>
<div style="width: 100%; text-align: center; background-color: #337ab7; color: #000; font-size:14px;">
  <h2 style="font-size:16px; font-weight:700; color:#000000; text-transform:capitalize;margin: 0;padding: 15px 0 8px 0;">
    <?=Yii::$app->common->getBranchDetail()->address?>
  </h2>
</div>
<h3 style='text-align:center'> Fine ledger of <?php echo date('M Y')?>
</h3> 
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
		$i=0;foreach ($currentMonth as $key => $monthCurrent) {
		$total=$total+$monthCurrent->payment_received;
			$i++;?>
		<tr>
			<td><?php echo $i; ?></td>
			<td><?php echo Yii::$app->common->getUserName($monthCurrent->fk_stu_id) ?></td>
			<td><?php echo Yii::$app->common->getName($monthCurrent->fk_stu_id) ?></td>
			<td><?php echo Yii::$app->common->getParentName($monthCurrent->fkStudent->stu_id) ?></td>
			<td><?php echo Yii::$app->common->getCGSName($monthCurrent->fkStudent->class_id,$monthCurrent->fkStudent->group_id,$monthCurrent->fkStudent->section_id) ?></td>
			<td><?php echo $monthCurrent->fineType->title ?></td>
			<td>Rs. <?php echo $monthCurrent->payment_received ?></td>
		</tr>
		<?php } ?>
		<tr>
			<th colspan="3"></th>
			<th colspan="2">Total</th>
			<th colspan="2">Rs. <?php echo $total ?></th>
		</tr>
	</tbody>
</table>