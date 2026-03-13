<?php
use yii\helpers\Url;
if(isset($_GET['testId'])){
?>
<style type="text/css">
  *{ margin:0; padding:0;}
  th, tr, td  {
    border:0.3px solid black;
    padding:5px;
    font-size:0.7em;
  }

  tr:nth-child(even){background-color: #f2f2f2}
  #first{ min-width: 15px; width: 15px; }
</style>
<div style="width: 100%; text-align: center; background-color: #337ab7; color: #000; font-size:14px;">
  <h2 style="font-size:16px; font-weight:700; color:#000000; text-transform:capitalize;margin: 0;padding: 15px 0 8px 0;">
    <?=Yii::$app->common->getBranchDetail()->address?>
  </h2>
</div>
<?php } ?>
<div class="row">
	<div class='col-md-12'>
	<h5 style="color:red">Quiz report of Class(<?php echo Yii::$app->common->getCGName($exam_quiz_type_details->class_id,$exam_quiz_type_details->group_id) ?>), Subject: <?= strtoupper($subject_details->title) .' , Quiz Date: '. date('d M Y',strtotime($exam_quiz_type_details->quiz_date))  ?>
	<?php if(!isset($_GET['testId'])){?>
	<a style="margin-top: -10px" href="<?= Url::to(['reports/subject-wise-date-quiz','testId'=>$testId]) ?>" class="btn btn-primary btn-sm pull-right">Generate Report</a>
	<?php } ?>
</h5>
	</div>
</div>
<div class="table-responsive">
<table class="table table-striped" width="100%" style="padding-top: -10px">
	<thead>
<tr style="background: #45983b;color:white">
	<td width="10px">Sr.</td>
	<td>Reg. No.</td>
	<td width="40px">Roll #</td>
	<td width="160px">Name</td>
	<td width="160px">Parent</td>
	<td width="70px">Obtained Marks</td>
	<td width="70px">Passing marks</td>
	<td width="70px">Total Marks</td>
	<td>Remarks</td>
</tr>
</thead>
<tbody>
	<?php 
	$i=0;
	foreach ($quiz_details as $key => $exam_quiz_type_detailsvalue) {
		$student_details=\app\models\StudentInfo::find()->where(['stu_id'=>$exam_quiz_type_detailsvalue['stu_id']])->one();
		$i++;
		?>
	<tr>
		<td><?=$i; ?></td>
		<td><?= Yii::$app->common->getUserName($student_details['user_id']) ?></td>
		<td><?php echo ($student_details->roll_no)?$student_details->roll_no:'N/A' ?></td>
		<td><?php echo Yii::$app->common->getName($student_details['user_id']) ?></td>
		<td><?php echo Yii::$app->common->getParentName($student_details['stu_id']) ?></td>
		<td><?php if($exam_quiz_type_detailsvalue->obtained_marks < $exam_quiz_type_details->passing_marks){echo '<span style="color:red;border:1px solid red;padding:8px">'.$exam_quiz_type_detailsvalue->obtained_marks.'</span>';}else{echo $exam_quiz_type_detailsvalue->obtained_marks;} ?></td>
		<td><?php echo $exam_quiz_type_details->passing_marks ?></td>
		<td><?php echo $exam_quiz_type_details->total_marks ?></td>
		<td><?php echo $exam_quiz_type_detailsvalue->remarks ?></td>
	</tr>
	<?php } ?>
</tbody>
</table>
</div>
