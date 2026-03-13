<?php use yii\helpers\Url; 
	if(isset($_GET['class_id'])){
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
		<h5 style="color:red">Quiz report of <?php echo Yii::$app->common->getName($studentDetails->user_id) ?> <?php echo ($studentDetails->gender_type == 1)?'S/O':'D/O';?> <?php echo Yii::$app->common->getParentName($studentDetails->stu_id) ?>, Session: <?php echo date('Y',strtotime($sessionStart)) ?> - <?php echo date('Y',strtotime($sessionEnd)) ?>
	<?php if(!isset($_GET['class_id'])){?>
	<a style="margin-top: -10px" href="<?= Url::to(['reports/student-wise-quiz-report','class_id'=>$class_id,'group_id'=>$group_id,'section_id'=>$section_id,'stu_id'=>$stu_id]) ?>" class="btn btn-primary btn-sm pull-right">Generate Report</a>
	<?php } ?>
</h5>
	</div>
</div>
<div class="table-responsive">
<table class="table table-striped" width="100%" style="padding-top: -15px">
	<thead>
<tr style="background: #45983b;color:white">
	<td width="10px">Sr.</td>
	<td>Subject</td>
	<td width="120px">Obtained Marks</td>
	<td width="120px">Passing marks</td>
	<td width="120px">Total Marks</td>
	<td>Teacher</td>
	<td>Remarks</td>
	<td>Quiz Date</td>
</tr>
</thead>
<tbody>
	<?php 
	$i=0;
	foreach ($exam_quiz_type_details as $key => $exam_quiz_type_detailsvalue) {
		$employee = \app\models\EmployeeInfo::find()->where(['fk_branch_id'=>Yii::$app->common->getBranch(),'emp_id'=>$exam_quiz_type_detailsvalue['teacher_id']])->one();
		$subject_details=\app\models\Subjects::find()->where(['id'=>$exam_quiz_type_detailsvalue['subject_id']])->one();
		$i++;
		?>
	<tr>
		<td><?=$i; ?></td>
		<td><?php echo $subject_details['title'] ?></td>
		<td><?php if($exam_quiz_type_detailsvalue['obtained_marks'] < $exam_quiz_type_detailsvalue['passing_marks']){echo '<span style="color:red;border:1px solid red;padding:8px">'.$exam_quiz_type_detailsvalue['obtained_marks'].'</span>';}else{echo $exam_quiz_type_detailsvalue['obtained_marks'];} ?></td>
		<td><?php echo $exam_quiz_type_detailsvalue['passing_marks'] ?></td>
		<td><?php echo $exam_quiz_type_detailsvalue['total_marks'] ?></td>
		<td><?php echo Yii::$app->common->getName($employee['user_id']) ?></td>
		<td><?php echo $exam_quiz_type_detailsvalue['remarks'] ?></td>
		<td><?php echo $exam_quiz_type_detailsvalue['quiz_date'] ?></td>
	</tr>
	<?php } ?>
</tbody>
</table>
</div>