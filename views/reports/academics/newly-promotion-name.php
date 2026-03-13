<?php use yii\helpers\Url;
if (isset($_GET['id'])){

	?>
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
<h3 style='text-align:center'>New Promoted student in Class <?php
$class_details=\app\models\RefClass::find()->where(['class_id'=>$class_id])->one();
 echo strtoupper($class_details->title); ?></h3>
<?php }else{ ?>
<a href="<?php echo Url::to(['newly-promotion-name','id'=>base64_encode($class_id)])?>" class="btn btn-primary pull-right"><i class="fa fa-download"></i>Generate Report</a>
<?php } ?>
<table class="table table-striped" width="100%" style="padding-top: -10px">
	<thead>
		<tr>
			<td>Sr.</td>
			<td>Class</td>
			<td>Reg. NO.</td>
			<td>Name</td>
			<td>Father Name</td>
			<td>Promotion Date</td>
		</tr>
	</thead>
	<tbody>
		<?php $i=1; 
		foreach ($studentDetails as $key => $student) {?>
			<tr>
				<td><?php echo $i; ?></td>
				<td><?php echo Yii::$app->common->getCGSName($student['class_id'],$student['group_id'],$student['section_id']) ?></td>
				<td><?php echo Yii::$app->common->getUserName($student['user_id']) ?></td>
				<td><?php echo Yii::$app->common->getName($student['user_id']) ?></td>
				<td><?php echo Yii::$app->common->getParentName($student['stu_id']) ?></td>
				<td><?php echo date('d-M-Y',strtotime($student['promoted_date'])) ?></td>
			</tr>
		<?php $i++;} ?>
	</tbody>
</table>