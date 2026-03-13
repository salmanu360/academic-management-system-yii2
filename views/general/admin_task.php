<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use kartik\select2\Select2;
use kartik\date\DatePicker;
?>
<div class="box box-warning">
	<div class="box-body">
		<?php if (Yii::$app->session->hasFlash('success')): ?>
			<div class="alert alert-success">
				<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
				<?= Yii::$app->session->getFlash('success') ?>
			</div>
		<?php endif; ?>
		<?php $form = ActiveForm::begin(); ?>
		<div class="row">
			<div class="col-md-3">
				<?php 
				$class_array = ArrayHelper::map(\app\models\RefClass::find()->where(['fk_branch_id'=>Yii::$app->common->getBranch(),'status'=>'active'])->all(), 'class_id', 'title'); 
				echo $form->field($model, 'class_id')->widget(Select2::classname(), [
					'data' => $class_array,
					'options' => ['placeholder' => 'Select Class ...','data-url'=>url::to(['general/get-class-details']),'id'=>'getdataclass'],
					'pluginOptions' => [
						'allowClear' => true
					],
					]); ?>
				</div>

				<div class="col-md-3">
					<?php 
					echo $form->field($model, 'group_id')->widget(Select2::classname(), [
						'options' => ['placeholder' => 'Select Group','data-url'=>Url::to(['general/get-subjects']),'id'=>'classdatagroup'],
						'pluginOptions' => [
							'allowClear' => true
						],
						]); ?>
					</div>
					<div style="height: 23px"></div>
					<div class="col-md-1"><?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>	</div>
					</div>
					
					<?php ActiveForm::end(); ?>
				</div>
			</div>
<?php if(Yii::$app->request->get('c_id')){
	$data=Yii::$app->request->get();
	 $form = ActiveForm::begin(['action'=>'save-admin-task']);
	 if(!isset($data['g_id'])){
	 	$group_id=null;
	 }else{
	 	$group_id=$data['g_id'];
	 }
	 $classSubjects=\app\models\Subjects::find()->where([
          'fk_class_id'=>$data['c_id'],
          'fk_group_id'=>($group_id)?$group_id:null,
          'is_division'=>0,
          'fk_branch_id'=>yii::$app->common->getBranch(),'status'=>'active'])->all();
	 ?>
	 <div class="box">
	 <div class="table-responsive">
	 <table class="table table-striped">
	 	 <thead>
	 	 	<tr class="info">
	 	 		<td>Sr.</td>
	 	 		<td>Subject</td>
	 	 		<td>Class Work</td>
	 	 		<td>Home Work</td>
	 	 		<td>Teacher</td>
	 	 		<td>Remarks</td>
	 	 	</tr>
	 	 </thead>
	 	 <tbody>
	 	 	<?php 
	 	 	$i=1;
	 	 	foreach ($classSubjects as $key => $classSubjectsValue) {
	 	 		$homeTaskDetails=\app\models\HomeTask::find()->where(['subject_id'=>$classSubjectsValue->id,'date'=>date('Y-m-d'),'class_id'=>$data['c_id'],'group_id'=>$group_id])->one();
	 	 		?>
	 	 	<tr>
	 	 		<td><?php echo $i; ?></td>
	 	 		<td><?php echo $classSubjectsValue->title ?>
	 	 			<?php echo $form->field($model, 'subject_id[]')->hiddenInput(['value'=>$classSubjectsValue->id])->label(false); ?>
	 	 			<?php echo $form->field($model, 'class_id')->hiddenInput(['value'=>$data['c_id']])->label(false); ?>
	 	 			<?php echo $form->field($model, 'group_id')->hiddenInput(['value'=>$group_id])->label(false); ?>
	 	 		</td>
	 	 		<td><?php echo $form->field($model, 'class_work[]')->textInput(['value'=>$homeTaskDetails['class_work']])->label(false); ?></td>
	 	 		<td><?php echo $form->field($model, 'home_task[]')->textInput(['value'=>$homeTaskDetails['home_task']])->label(false); ?></td>
	 	 		<td><?php $empDetails = \app\models\User::find()
            ->select(['employee_info.emp_id',"concat(user.first_name, ' ' ,  user.last_name) as name"])
            ->innerJoin('employee_info','employee_info.user_id = user.id')
            ->where(['user.fk_branch_id'=>Yii::$app->common->getBranch(),'user.fk_role_id'=>4])->asArray()->all();
            if(Yii::$app->user->identity->fk_role_id == 1){ 
            	// $empDetailsValue['emp_id']
             ?>
	 	 			<select name="HomeTask[teacher_id][]" class="form-control">
	 	 				<?php if(count($homeTaskDetails) > 0){}else{ ?>
	 	 				<option value="">Select Teacher</option>
	 	 				<?php } ?>
	 	 				<?php foreach ($empDetails as $key => $empDetailsValue) {
	 	 					 if(count($homeTaskDetails) > 0){
	 	 					?>
	 	 			 
	 	 			 <option value="<?= $homeTaskDetails->teacher_id ?>"><?= $homeTaskDetails->teacher->user->first_name ?></option>
	 	 			 <?php }else{ ?>
	 	 			<option value="<?= $empDetailsValue['emp_id'] ?>"><?= $empDetailsValue['name'] ?></option>
	 	 			<?php } } ?>
	 	 		    </select>
	 	 		    <?php } ?>
	 	 		</td>
	 	 		<td><?php echo $form->field($model, 'remarks[]')->textInput(['value'=>$homeTaskDetails['remarks']])->label(false); ?></td>
	 	 	</tr>
	 	 	<?php $i++;} ?>
	 	 </tbody>
	 </table>
	 
							<?= $form->field($model, 'date')->hiddenInput(['value'=>date('Y-m-d')])->label(false); ?>
							<?= $form->field($model, 'fk_branch_id')->hiddenInput(['value'=>Yii::$app->common->getBranch()])->label(false); ?>
							<?= $form->field($model, 'user_id')->hiddenInput(['value'=>Yii::$app->user->id])->label(false); ?>

							<?= Html::submitButton($model->isNewRecord ? 'Save' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
							<a href="" class="btn btn-warning">Reset</a>
					
	</div>
	</div>
	 <?php ActiveForm::end();
} ?>
