<div class="modal-dialog modal-dialog-centered modal-lg" role="document">
<div class="modal-content">
        <div class="modal-header alert alert-info">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title" style="color: black">Today Fine Details</h4>
        </div>
        <div class="modal-body">
<div class="table-responsive">
<table class="table table-stripped">
	<thead>
		<tr>
			<th>Sr.</th>
			<th>Student</th>
			<th>Father</th>
			<th>Class</th>
			<th>Fine Type</th>
			<th>Fine</th>
		</tr>
	</thead>
	<tbody>
		<?php $i=0;foreach ($fine as $key => $value) {
			$i++;?>
		<tr>
			<td><?php echo $i; ?></td>
			<td><?php echo Yii::$app->common->getName($value->fk_stu_id) ?></td>
			<td><?php echo Yii::$app->common->getParentName($value->fkStudent->stu_id) ?></td>
			<td><?php echo Yii::$app->common->getCGSName($value->fkStudent->class_id,$value->fkStudent->group_id,$value->fkStudent->section_id) ?></td>
			<td><?php echo $value->fineType->title ?></td>
			<td>Rs. <?php echo $value->payment_received ?></td>
		</tr>
		<?php } ?>
	</tbody>
</table>
</div>
</div>
<div class="modal-footer">
          <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
        </div>
</div>
</div>