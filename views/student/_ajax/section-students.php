<?php 
use yii\helpers\Url;
 ?>
<div class="box box-default">
<div class="box-body">
<div class="table-responsive">
	<form action="<?php echo Url::to(['student/section-send-sms'])?>" method="post">
<table class="table table-striped">
	<thead>
		<tr class="info">
			<td>Sr.</td>
			<td>SMS</td>
			<td>Roll No.</td>
			<td>Reg. No.</td>
			<td>Name</td>
			<td>Parent</td>
		</tr>
	</thead>
	<tbody>
		<?php $i=0;foreach ($studentDetails as $key => $studentDetailsvalue) {
		$i++?>
		<tr>
		<td><?php echo $i; ?></td>
		<td><input type="checkbox" name="checbox[<?php echo $studentDetailsvalue->stu_id ?>]"></td>
		<td><?= ($studentDetailsvalue->roll_no)?$studentDetailsvalue->roll_no:'N/A' ?></td>
		<td><?= Yii::$app->common->getUserName($studentDetailsvalue->user_id) ?></td>
		<td><?= Yii::$app->common->getName($studentDetailsvalue->user_id) ?></td>
		<td><?= Yii::$app->common->getParentName($studentDetailsvalue->stu_id) ?></td>
		</tr>
		<?php } ?>
	</tbody>
</table>
<div class="row">
	<div class="col-md-6">
		<div class="form-group">
  <label for="comment">SMS:</label>
  <textarea class="form-control messageCount" rows="3" name="sms" required="required"></textarea>
   <span id="remaining">160 characters remaining</span> <span id="messages">1 message(s)</span>
</div>
	</div>
</div>
<div class="row"><div class="col-md-2"><button type="submit" class="btn btn-success">Send SMS</button></div></div>
</form>
</div>
</div>
</div>
<script>
    /*message count*/
    var $remaining = $('#remaining'),
    $messages = $remaining.next();
    $('.messageCount').keyup(function(){
    var chars = this.value.length,
    messages = Math.ceil(chars / 160),
    remaining = messages * 160 - (chars % (messages * 160) || messages * 160);
    $remaining.text(remaining + ' characters remaining');
    $messages.text(messages + ' message(s)');
}); 
</script>
