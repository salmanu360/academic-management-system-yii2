<?php use yii\helpers\Url;
if (isset($_GET['c_id'])){?>
<style type="text/css">
  *{ margin:0; padding:0;}
  th, tr, td  {
    border:0.3px solid black;
    padding:5px;
    font-size:0.8em;
  }

  tr:nth-child(even){background-color: #f2f2f2}
  #first{ min-width: 15px; width: 15px; }
</style>
    <div style="width: 100%; text-align: center; background-color: #337ab7; color: #000; font-size:14px;">
      <h2 style="font-size:16px; font-weight:700; color:#000000; text-transform:capitalize;margin: 0;padding: 15px 0 8px 0;">
        <?=Yii::$app->common->getBranchDetail()->address?>
    </h2>
</div>
<h3 style='text-align:center'><?php if($radioValue == 1){ echo 'Promoted';}else{echo 'Demoted';}?> student in Class 
 <?php echo Yii::$app->common->getCGSName($class_id,$group_id,$section_id); ?></h3>
 <?php }else{ ?>
 <a href="<?php echo Url::to(['promoted-students','c_id'=>$class_id,'g_id'=>$group_id,'s_id'=>$section_id,'radioValue'=>$radioValue])?>" class="btn btn-primary pull-right"><i class="fa fa-download"></i>Generate Report</a>
 <?php } ?>
<table class="table table-striped" width="100%" style="padding-top: -10px">
	<thead>
		<tr style="background: #8bc34a">
			<td>Sr.</td>
			<td>Reg. NO.</td>
			<td>Roll No.</td>
			<td>Name</td>
			<td>Father Name</td>
			<td>Promotion Date</td>
		</tr>
	</thead>
	<tbody>
		<?php $i=1; 
		foreach ($promotedStu as $key => $student) {
			$student_details=\app\models\StudentInfo::find()->where(['stu_id'=>$student->fk_stu_id])->one();
			?>
		<tr>
				<td><?php echo $i; ?></td>
				<td><?php echo Yii::$app->common->getUserName($student_details->user_id) ?></td>
				<td><?php echo $student_details->roll_no?></td>
				<td><?php echo Yii::$app->common->getName($student_details->user_id) ?></td>
				<td><?php echo Yii::$app->common->getParentName($student->fk_stu_id) ?></td>
				<td><?php echo date('d-M-Y',strtotime($student['promoted_date'])) ?></td>
			</tr>
			<?php $i++; } ?>
	</tbody>
</table>