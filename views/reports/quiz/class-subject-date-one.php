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
		<h5 style="color:red">Quiz report of Class(<?php echo Yii::$app->common->getCGName($class_id,$group_id) ?>) , Quiz Teacher: <?= Yii::$app->common->getName($employee->user_id) ?>, Subject: <?= strtoupper($subject_details->title); ?>
	<?php if(!isset($_GET['class_id'])){?>
	<a style="margin-top: -10px" href="<?= Url::to(['reports/class-subject-date','date'=>$date,'class_id'=>$class_id,'group_id'=>$group_id,'subject_id'=>$subject_id]) ?>" class="btn btn-primary btn-sm pull-right">Generate Report</a>
	<?php } ?>
</h5>
	</div>
</div>
<div class="table-responsive">
<table class="table table-striped" width="100%">
	<thead>
<tr style="background: #45983b;color:white">
	<td>Sr.</td>
	<td>Reg. No.</td>
	<td>Roll #</td>
	<td>Name</td>
	<td>Parrent</td>
	<td>Total Marks</td>
	<td>Passing Marks</td>
	<td>Obtained Marks</td>
	<td>Remarks</td>
</tr>
</thead>
<tbody>
<?php 
$i=1;
//echo '<pre>';print_r($query);die;
foreach ($query as $key => $queryValue) {
$student_details=\app\models\StudentInfo::find()->where(['stu_id'=>$queryValue->stu_id])->one();
	?>
<tr>
	<td><?=$i ?></td>
	<td><?= Yii::$app->common->getUserName($student_details->user_id) ?></td>
	<td><?=($student_details->roll_no)?$student_details->roll_no:'N/A'  ?></td>
	<td><?= Yii::$app->common->getName($student_details->user_id) ?></td>
	<td><?= Yii::$app->common->getParentName($queryValue->stu_id) ?></td>
	<td><?= $exam_quiz_type_details->total_marks ?></td>
	<td><?= $exam_quiz_type_details->passing_marks ?></td>
	<td><?php if($queryValue->obtained_marks < $exam_quiz_type_details->passing_marks){echo '<span style="color:red;border:1px solid red">'.$queryValue->obtained_marks.'</span>';}else{echo $queryValue->obtained_marks;}?></td>	
	<td><?= $queryValue->remarks ?></td>
</tr>
</thead>
</tbody>
<?php $i++;} ?>
</table>
</div>