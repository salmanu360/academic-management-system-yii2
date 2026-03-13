<?php 
use yii\helpers\Url;
$this->title="Id Card";
?>
<div class="row">
	<div class="col-md-6">
		<iframe src="<?php echo Url::to(['idcard-pdf','id'=>$stu_id])?>" style="width: 660px; height:  640px;" frameborder="0">
	</div>
	<div class="col-md-6">
		
	</div>
</div>

