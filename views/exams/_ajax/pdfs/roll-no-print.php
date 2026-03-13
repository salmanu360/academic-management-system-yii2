<?php
use yii\helpers\Url; ?>
<style type="text/css">
@media print {
  .page-break {
    page-break-before: always; /* Ensure a new page before this element */
  }
}
*{ margin:0; padding:5;}
th, tr, td  {
	border:1px solid black;
	padding:10px;
	font-size:1em;
}
tr:nth-child(even){background-color: #f2f2f2}
tr.noborder td {
	border: 0;
}
#container{width:100%;}
#left{float:left;width:200px;}
#right{float:right;width:100px;}
#center{margin:0 auto;width:400px;}
</style>
<link rel="stylesheet" type="text/css" media="print" href="print.css" />
<?php
$i=0;
foreach ($getStudents as $key => $studentsValue) {
	$exam_checkValue = \app\models\Exam::find()->where([
		'fk_class_id'   => $studentsValue->class_id,
		'fk_group_id'   => (empty($studentsValue->group_id))?null:$studentsValue->group_id,
                    // 'fk_section_id' => $data['section_id'],
		'fk_exam_type'  => $exam_type->id
	])->orderBy(['start_date'=>SORT_ASC])->all();
	$i++;
	$user=\app\models\User::find()->where(['id'=>$studentsValue->user_id])->one();
	$file_name = $user->Image;
	$file_path = Yii::getAlias('@webroot').'/uploads/';
	if(!empty($file_name) && file_exists($file_path.$file_name)) {
		$web_path = Yii::getAlias('@web').'/uploads/';
		$imageName = $user->Image;

	}else{
		$web_path = Yii::getAlias('@web').'/img/';
		if($studentsValue->gender_type == 1){
			$imageName = 'male.jpg';
		}else{
			$imageName = 'female.png';

		}
	}
	?>
	<section class="invoice page-break">  
		<div style="border-bottom:1px dashed #3f3c8b;">
			<div id="container">
				<div id="left">
					<img width="85px" height="71px" class="user-image" src="<?= Url::to('@web/uploads/school-logo/'.Yii::$app->common->getBranchDetail()->logo)?>" alt="school logo"></div>
					<div id="right"><img width="85px" height="71px" class="user-image" src="<?= $web_path.$imageName?>" alt="school logo"></div>
					<div id="center"> <span style="font-weight: bold;font-size: 16px"><?= strtoupper(Yii::$app->common->getBranchDetail()->address)?></span>
						<br><span><?= (!empty(Yii::$app->common->getBranchDetail()->phone)?'Ph No.: '. Yii::$app->common->getBranchDetail()->phone:'')?> <?= (!empty(Yii::$app->common->getBranchDetail()->mobile)?'Mob.: '. Yii::$app->common->getBranchDetail()->mobile:'')?></span></div>
				</div>
				<hr>
				<h4 style="text-align: center;margin-top: -10px">Datesheet / Roll No. Slip of <?= strtoupper($exam_type->type);?></h4>
				<p style="margin-left: 10px">Exam Dates: <span style="color:red"><?= date('D d M Y',strtotime($exam_type->exam_date));?></span></p>
				<!-- <p style="margin-left: 540px; margin-top: -100px">Print Date: <?//= date('D d M Y');?></p> -->
				<p style="color:red; font-size: 13px;margin-left: 10px"><b>NOTE: </b> Candidiates are provisionally allowed to appear in the Exam. Subject to verification of credentials and elegibillity criteria</p>
				<div class="table-responsive">
					<h4 style="margin-top: -5px;margin-left: 10px">
						<span>Roll Number: <?php echo ($studentsValue->roll_no)?$studentsValue->roll_no:'N/A' ?></span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						<span>Reg. Number: <?php echo strtoupper($user->username) ?></span>
					</h4>
					<h4 style="margin-left: 10px">
						<span>
							Name: <?= strtoupper(Yii::$app->common->getName($studentsValue->user_id)); ?>
						</span>&nbsp;&nbsp;
						<span>Father Name: <?= strtoupper(Yii::$app->common->getParentName($studentsValue->stu_id)); ?></span>&nbsp;&nbsp;
						<span>
							Class: <?= strtoupper(Yii::$app->common->getCGSName($class_id,$group_id,$section_id)) ?>
						</span>
					</h4>
					<table class="table table-striped" width="100%">
						<tbody>
							<tr>
								<?php foreach ($exam_checkValue as $key => $exam_checkDate) {?>
									<td><?= date('d-m-Y H:i:s',strtotime($exam_checkDate->start_date)).' <br>'. date('l',strtotime($exam_checkDate->start_date)) ?></td>
								<?php } ?>
							</tr>
							<tr>
								<?php foreach ($exam_checkValue as $key => $exam_checkValue) {?>
									<td><?= strtoupper($exam_checkValue->fkSubject->title) ?></td>
								<?php } ?>
							</tr>
						</tbody>
					</table>
				</div>
				<p style="margin-left: 10px"><strong>Note:</strong></p>
				<ol>
					<li>Those students will not be allowed in the exam whom have not paid remaining dues.</li>
					<li>Late commers will not be permitted to sit in the examination hall.</li>
					<li>Parents are requested to get preparation of their children according to the given syllabus.</li>
					<li><?= strtoupper(Yii::$app->common->getBranchDetail()->name)?> reserves the right to modify the date sheet of exam due to weather and any other certain situation.</li>
				</ol>
				<br />
				<br />
				<div style="margin-top: 30px; margin-left: 8px">Dated: <?= date('d-m-Y');?></div>
				<div style="text-align: right;margin-top: -60px">
					<?php $controlerSign=\app\models\Signature::find()->where(['branch_id'=>Yii::$app->common->getBranch(),'category'=>2])->one();
					if(!empty($controlerSign)){?>
						<img style="height:55px;" src="<?php echo Yii::$app->request->baseUrl.'/uploads/doc_signs/'.$controlerSign->image.'.png' ?>" alt="">
					<?php } ?>
					<br>
					Controller of Examination
				</div><br>
			</div>
		</section>
		<div style="height: 400px"></div>
		<?php 
		if ($i % 2 == 0) {?>
			<?php } } ?>