<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
?>
<div class="panel panel-primary">
  <div class="panel-heading">Search with First start digit of student registeration No.</div>
  <div class="panel-body">
  	 <?php $form = ActiveForm::begin(['action'=>Url::to(['all-students'])]); ?>
  	 <div class="row">
  	 	<div class="col-md-3">
  	 		<?= $form->field($model, 'username')->textInput(['id'=>'searchbyusernameinput']) ?>
  	 		<input type="hidden" id="url" value="<?php echo Url::to(['all-students']) ?>">
  	 	</div>
  	 	<div class="col-md-2">
	        <?= Html::submitButton($model->isNewRecord ? 'Search' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary','id'=>'searchbyusernamebtn','style'=>'margin-top: 24px;']) ?>
  	 	</div>
  	 </div>
  	 <?php ActiveForm::end(); ?>
  </div>
</div>
<h3 id="loading" style="color: red;display: none;">Loading.... wait please</h3>

<div class="row">
	<div class="col-md-12" id="getallstudents"></div>
</div>