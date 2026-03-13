<?php
use yii\helpers\Url;
if(isset($_GET['class_id'])){?>
<style type="text/css">
  *{ margin:0; padding:0;}
  th, tr, td  {
    border:1px solid black;
    padding:8px;
    font-size:1em;
  }

  tr:nth-child(even){background-color: #f2f2f2}
</style>
<div style="width: 100%; text-align: center; background-color: #337ab7; color: #000; font-size:14px;">
  <h2 style="font-size:16px; font-weight:700; color:#000000; text-transform:capitalize;margin: 0;padding: 15px 0 8px 0;">
    <?=Yii::$app->common->getBranchDetail()->address?>
  </h2>
</div>
<?php }
?>
<div class="row">
	<div class='col-md-12'>
		<h5 style="color:red"><span style="text-align: center">Quiz report of Class(<?php echo Yii::$app->common->getCGName($class_id,$group_id) ?>) on <?php echo date('d M Y',strtotime($date)) ?></span>
	<?php if(!isset($_GET['class_id'])){?>
	<a style="margin-top: -10px" href="<?= Url::to(['reports/class-wise-quiz','date'=>$date,'class_id'=>$class_id,'group_id'=>$group_id]) ?>" class="btn btn-primary btn-sm pull-right">Generate Report</a>
	<?php } ?>
</h5>
	</div>
</div>
<div class="table-responsive">
<table class="table table-striped" width="100%">
	<thead>
<tr style="background: #45983b;color:white">
	<td>Sr.</td>
	<td>Teacher</td>
	<td>Subject</td>
	<td>Total Marks</td>
	<td>Passing Marks</td>
</tr>
</thead>
<tbody>
<?php 
$i=1;
//echo '<pre>';print_r($query);die;
foreach ($exam_quiz_type_details as $key => $queryValue) {
 $employee = \app\models\EmployeeInfo::find()->where(['fk_branch_id'=>Yii::$app->common->getBranch(),'emp_id'=>$queryValue->teacher_id])->one();
 $subject_details=\app\models\Subjects::find()->where(['id'=>$queryValue->subject_id])->one();
	?>
<tr>
	<td><?=$i ?></td>
	<td><?= strtoupper(Yii::$app->common->getName($employee->user_id)) ?></td>
	<td><?= $subject_details->title ?></td>
	<td><?= $queryValue->total_marks ?></td>
	<td><?= $queryValue->passing_marks ?></td>
</tr>
</thead>
</tbody>
<?php $i++;} ?>
</table>
</div>