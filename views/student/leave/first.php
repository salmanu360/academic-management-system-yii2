<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
?>
<?php if (Yii::$app->session->hasFlash('success')): ?>
 <div class="alert alert-success">
  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
  <?= Yii::$app->session->getFlash('success') ?>
</div>
<?php endif; ?>
<div class="panel panel-primary">
  <div class="panel-heading">Issue SLC</div>
  <div class="panel-body">
  	 <?php $form = ActiveForm::begin(); ?>
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