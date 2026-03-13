 <?php use yii\helpers\Html;
 use yii\widgets\ActiveForm;
 use yii\helpers\Url;
 use kartik\date\DatePicker;
 ?>
 <div class="panel panel-default">
 	<div class="panel-body">
 		<div class="row">
 			<div class="col-md-4">
 				<?php echo Html::a('<i class="glyphicon glyphicon-download-alt"> Download</i>', ['download-exam-schedule','cid'=>$class_id,'gid'=>$group_id,'eid'=>$exam_id], 
 				['class' => 'btn btn-primary btn-sm']) ?>

 				<?php echo Html::a('<i class="glyphicon glyphicon-envelope"></i> Send Date Sheet in SMS', ['datesheet-send','cid'=>$class_id,'gid'=>$group_id,'eid'=>$exam_id], 
 				['class' => 'btn btn-danger btn-sm','onClick'=>"return confirm('Are you sure you want to send this dateSheet to parents..?')"]) ?>
 			</div>
 		</div>
 		<?php $form = ActiveForm::begin(['action'=>Url::to(['update-exam'])]); 
 		echo $form->field($model, 'fk_exam_type')->hiddenInput(['value'=>$exam_id])->label(false) ?>
 		<div class="table-responsive">
 			<table class="table">
 				<thead>
 					<tr class="info">
 						<th>#</th>
 						<th>SUBJECT</th>
 						<th>TOTAL MARKS</th>
 						<th>PASSING MARKS</th>
 						<th>DATE & TIME</th>
 					</tr>
 				</thead>
 				<tbody>
 					<?php $i=0;foreach ($examData as $key => $exam) { $i++;?>   
 						<tr>
 							<td><?= $i; ?></td>
 							<td><?= strtoupper($exam->fkSubject->title) ?></td>
 							<td><?= $form->field($model, 'total_marks['.$exam->id.']')->textInput(['value'=>$exam->total_marks,'type' => 'number','style'=>'width: 118px;;'])->label(false);?></td>
 							<td><?= $form->field($model, 'passing_marks['.$exam->id.']')->textInput(['value'=>$exam->passing_marks,'type' => 'number','style'=>'width: 118px;'])->label(false); ?></td>
 							<td><?= $form->field($model, 'start_date['.$exam->id.']')->textInput(['value'=>date('d-m-Y H:i:s',strtotime($exam->start_date))])->label(false); ?></td>
 						</tr> 
 					<?php }  ?>
 					<tr>
 						<td><?= $form->field($model2, 'id')->hiddenInput(['value'=>$examType->id])->label(false); ?></td>
 						<th>Exam Name <?= $form->field($model2, 'type')->textInput(['value'=>$examType->type])->label(false); ?></th>
 						<th>Exam Date 
 							<?= $form->field($model2, 'exam_date')->widget(DatePicker::classname(), [
                                'options' => ['value' => $examType->exam_date],
                                'pluginOptions' => [
                                    'autoclose' => true,
                                    'format' => 'yyyy-mm-dd',
                                    'todayHighlight' => true,
                                ]
                            ])->label(false); ?>



 						</th>
 						<th>Passing Percentage(optional) <?= $form->field($model2, 'passing_percentage')->textInput(['value'=>$examType->passing_percentage])->label(false); ?></th>
 					</tr> 

 				</tbody>
 			</table>
 		</div>
 		<div class="form-group">
 			<button type="submit" class="btn btn-success pull-right" onclick="return confirm('Are you sure you want to update?,this will all effect your award list and DMC')">Update</button>
 			<?php ActiveForm::end(); ?>
 		</div>
 	</div>
 </div>