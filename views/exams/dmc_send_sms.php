<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
?>
<style>
input{
	background: white;
	border: none;
}
</style>
<div class="panel panel-default">
	<div class="panel-body">
		<div class="col-md-12 print-padding-bottom">
			<div id="sss">
				<div class="col-md-12">
					<div id="class-wise-container" class="table-responsive kv-grid-container">
						<?php $form = ActiveForm::begin(['action'=>Url::to(['exams/send-dmc-sms'])]); ?>
						<input type="hidden" name="class" value="<?php echo $class_id ?>">
						<table class="table table-stripped">
							<thead class="report-header">
								<tr class="info">
									<th valign="middle" style="border: 1px solid #0000008f">#</th>
									<th valign="middle" style="border: 1px solid #0000008f">Reg. No.</th>
									<th valign="middle" style="border: 1px solid #0000008f">Roll No.</th>
									<th valign="middle" style="border: 1px solid #0000008f">Name</th>
									<?php
									$max_marks= 0;
									$passingMarks=[];
									$subject=[];
									foreach ($heads_marks['heads'] as $key=>$sub){
										echo "<th style='border: 1px solid #0000008f'>".ucfirst($sub)."<br/>(".$heads_marks['total_marks'][$key].")</th>";
										$max_marks= $max_marks+$heads_marks['total_marks'][$key];
										$passingMarks[]=$heads_marks['passing_marks'];
										$subject[]=$sub;
									}
									?>
									<th valign="middle" style="border: 1px solid #0000008f">Total Marks</th>
									<th valign="middle" style="border: 1px solid #0000008f">Percentage</th>
									<th valign="middle" style="border: 1px solid #0000008f">Position</th>
								</tr>
							</thead>
							<tbody style="    border: 1px solid #0000008f ;">
								<?php
								$i=1;
								$student=[];
								foreach ($query as $student_id=>$marks){
									$user_id=$marks['name'];
									$stuQuery = \app\models\User::find()
									->select(['student_info.stu_id','student_parents_info.contact_no',"concat(user.first_name, ' ' ,  user.last_name) as name"])
									->innerJoin('student_info','student_info.user_id = user.id')
									->innerJoin('student_parents_info','student_parents_info.stu_id = student_info.stu_id')
									->where(['user.id'=>$user_id])->asArray()->one();
									$totalMarks_arr = [];
									$totalMarks = 0;
									$total_marks_obtain= 0;
									$percentage=0;
									echo "<tr>";
									echo "<td style='border: 1px solid #0000008f;'>".$i."</td>";  
									$obtained_marks=0;
									$obtained_marks=$marks[0];
									$name=$marks['name'];
									$studentReg=$marks['student_id'];
									$student_roll_no=$marks['student_roll_no'];
									unset($marks['passing_marks']);
									unset($marks['name']);
									unset($marks['student_id']);
									unset($marks['student_roll_no']);
									echo "<td style='border: 1px solid #0000008f;'>";
									echo $studentReg;
									echo "</td>";
									echo "<td style='text-align:center;border: 1px solid #0000008f;'>";
									echo $student_roll_no;
									echo "</td>";
									echo "<td style='border: 1px solid #0000008f;'>";
									echo Yii::$app->common->getName($name);
									echo "</td>";
									foreach ($marks as $key => $obtained_marks) {
										echo "<td style='border: 1px solid #0000008f;text-align:center'>";?>
										<input type="text" readonly value="<?php echo $obtained_marks?>" name="marks[<?php echo $stuQuery["stu_id"] ?>][<?php echo $subject[$key]?>]">
										<?php
										echo "</td>";
									}
									$total_marks_obtain = array_sum($marks);
									if($max_marks>0){
										$percentage= $total_marks_obtain*100/$max_marks;
									}else{
										$percentage = 0;
									}
									echo "<td style='border: 1px solid #0000008f;'>";?>
									<input readonly type="text" value="<?php echo floatval($total_marks_obtain) .'/'. $max_marks?>" name="total_marks[<?php echo $stuQuery["stu_id"] ?>]">
									<?php
									echo "</td>";
									echo "<td style='text-align:center;border: 1px solid #0000008f;'>";
									echo round($percentage,1)."%";
									echo "</td>";
									if(isset($positions)){
										$positionGet=Yii::$app->common->multidimensional_search($positions, ['student_id'=>$student_id]);
										if(!$positionGet){
											echo "<td>N/A</td>";
										}else{
											echo '<td class="pts" width="20" style="text-align:center;border: 1px solid #0000008f" >'.Yii::$app->common->multidimensional_search($positions, ['student_id'=>$student_id]).'</td>';
										}
									}
									$i++;
									$student=$positions;
								}
								?>
							</tbody>
						</table>
						<div class="row">
							<div class="col-md-6">
								<button type="submit" class="btn btn-success">Send SMS</button>
								<a href="" class="btn btn-danger">Refresh</a>
							</div>
						</div>
						<?php ActiveForm::end(); ?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>