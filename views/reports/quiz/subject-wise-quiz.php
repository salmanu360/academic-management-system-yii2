<?php
use yii\helpers\Url;
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
		<h5 style="color:red">Quiz report of Class(<?php echo Yii::$app->common->getCGName($class_id,$group_id) ?>), Subject: <?= strtoupper($subject_details->title); ?> , Session: <?php echo date('Y',strtotime($sessionStart)) ?> - <?php echo date('Y',strtotime($sessionEnd)) ?>
	<?php if(!isset($_GET['class_id'])){?>
	<a style="margin-top: -10px" href="<?= Url::to(['reports/get-subject-wise-quiz','class_id'=>$class_id,'group_id'=>$group_id,'subject_id'=>$subject_id]) ?>" class="btn btn-primary btn-sm pull-right">Generate Report</a>
	<?php } ?>
</h5>
	</div>
</div>
<div class="table-responsive">
<table class="table table-striped" width="100%" style="padding-top: -15px">
	<thead>
<tr style="background: #45983b;color:white">
	<td>Sr.</td>
	<td>Total Marks</td>
	<td>Passing marks</td>
	<td>Teacher</td>
	<td>Quiz Date</td>
</tr>
</thead>
<tbody>
	<?php 
	$i=0;
	foreach ($exam_quiz_type_details as $key => $exam_quiz_type_detailsvalue) {
		$employee = \app\models\EmployeeInfo::find()->where(['fk_branch_id'=>Yii::$app->common->getBranch(),'emp_id'=>$exam_quiz_type_detailsvalue['teacher_id']])->one();
		$i++;
		?>
	<tr>
		<td><?=$i; ?></td>
		<td><?php echo $exam_quiz_type_detailsvalue['total_marks'] ?></td>
		<td><?php echo $exam_quiz_type_detailsvalue['passing_marks'] ?></td>
		<td><?php echo Yii::$app->common->getName($employee['user_id']) ?></td>
		<td><span style="color:#293ca9;text-decoration: underline; cursor: pointer;	" id="subjectWiseDateQuiz" data-url='<?php echo Url::to(['subject-wise-date-quiz']) ?>' data-testid='<?php echo $exam_quiz_type_detailsvalue->id ?>'><?php echo $exam_quiz_type_detailsvalue['quiz_date'] ?></span></td>
	</tr>
	<?php } ?>
</tbody>
</table>
</div>
