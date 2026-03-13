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
					<div class="col-md-3">
						<?php 
						echo $form->field($model, 'subject_id')->widget(Select2::classname(), [
							'options' => ['placeholder' => 'Select Subject','data-url'=>Url::to(['class-timetable/get-subjects-timetable']),'id'=>'getSubjectsdata'],
							'pluginOptions' => [
								'allowClear' => true
							],
							]); ?>
						</div>
						<div class="col-md-3">
							<?php
							if(Yii::$app->user->identity->fk_role_id == 1){ 
							echo $form->field($model, 'teacher_id')->widget(Select2::classname(), [
								'data' => Yii::$app->common->getBranchEmployee(),
								'options' => ['placeholder' => 'Select Teacher ...'],
								'pluginOptions' => [
									'allowClear' => true
								],
							]);
						}else{
							echo $form->field($model, 'teacher_id')->widget(Select2::classname(), [
								'data' => Yii::$app->common->getLoginEmployee(),
								'options' => ['placeholder' => 'Select Teacher ...'],
								'pluginOptions' => [
									'allowClear' => true
								],
							]);
						}
							?>
						</div>
						

					</div>
					<div class="row">
					<div class="col-md-3">
						<?php echo $form->field($model, 'class_work')->textInput() ?>
					</div>
					<div class="col-md-3">
						<?php echo $form->field($model, 'home_task')->textInput() ?>
					</div>
					<div class="col-md-3">
						<?php echo $form->field($model, 'remarks')->textInput() ?>
					</div>
					</div>
					<div class="row">
						<div class="col-md-3">
							
							<?= $form->field($model, 'date')->hiddenInput(['value'=>date('Y-m-d')])->label(false); ?>
							<?= $form->field($model, 'fk_branch_id')->hiddenInput(['value'=>Yii::$app->common->getBranch()])->label(false); ?>
							<?= $form->field($model, 'user_id')->hiddenInput(['value'=>Yii::$app->user->id])->label(false); ?>
						
							<?= Html::submitButton($model->isNewRecord ? 'Save' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
						</div>
						</div>
					<?php ActiveForm::end(); ?>
				</div>
			</div>