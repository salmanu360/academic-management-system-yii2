<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;
use kartik\date\DatePicker;
$this->title='Dashboard Settings'
?>
<?php if (Yii::$app->session->hasFlash('error')): ?>
    <div class="alert alert-danger alert-dismissable">
         <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
         <?= Yii::$app->session->getFlash('error') ?>
    </div>
<?php endif; ?>
<?php if (Yii::$app->session->hasFlash('success')): ?>
    <div class="alert alert-success alert-dismissable">
         <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
         <?= Yii::$app->session->getFlash('success') ?>
    </div>
<?php endif; ?>
<div class="panel panel-body">
<?php $form = ActiveForm::begin(); ?>
<fieldset class="scheduler-border">
        <legend>Total Fee setting</legend>
<div class="row">
	<div class="col-md-2">
		<?= $form->field($model, 'fee_all')->checkbox(['style'=>'margin-top:30px'])->label(false); ?>
	</div>
	<div class="col-md-6">
		<?php 
		echo  $form->field($model, 'total_fee_date')->widget(DatePicker::classname(), [
                         'options' => [],
                         'pluginOptions' => [
                             'autoclose'=>true,
                             'format' => 'yyyy/mm/dd',
                         ]
                     ]) ?>
	</div>
</div>
</fieldset>

<fieldset>
    <legend>Exam results on parent portal show/hide</legend>
    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'parent_portal_exam_result')->dropDownList([ 'yes' => 'Yes', 'no' => 'No', ]) ?>
        </div>
    </div>
</fieldset>
<br>
<div class="row">
	<div class="col-md-6">
        <?= Html::submitButton($model->isNewRecord ? 'Save' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-success']) ?>
	</div>
</div>
<?php ActiveForm::end(); ?>
</div>