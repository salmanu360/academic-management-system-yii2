<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\date\DatePicker;
use kartik\depdrop\DepDrop;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use kartik\datetime\DateTimePicker;
use yii\helpers\Url;
$settings = Yii::$app->common->getBranchSettings();
$class_array = ArrayHelper::map(\app\models\RefClass::find()->where(['fk_branch_id'=>Yii::$app->common->getBranch(),'status'=>'active'])->all(), 'class_id', 'title');
?>
<div class="box box-primary">
<div class="panel-heading">
<div class="exam-form">
    <?php $form = ActiveForm::begin(); ?>
   
    <div class="row">
        
        <div class="col-md-6">
            <?= $form->field($model, 'total_marks')->textInput() ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'passing_marks')->textInput() ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'start_date')->widget(DateTimePicker::classname(), [
                                'type' => DateTimePicker::TYPE_INPUT,
                                'options' => ['value' => date('Y-m-d h:i A')],
                                'pluginOptions' => [
                                    'autoclose' => true,
                                    'format' => 'yyyy-mm-dd HH:ii P',
                                    'todayHighlight' => true,
                                ]
                            ])->label(false); ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
             <?= $form->field($model, 'end_date')->widget(DateTimePicker::classname(), [
                                'type' => DateTimePicker::TYPE_INPUT,
                                'options' => ['value' => date('Y-m-d h:i A')],
                                'pluginOptions' => [
                                    'autoclose' => true,
                                    'format' => 'yyyy-mm-dd HH:ii P',
                                    'todayHighlight' => true,
                                ]
                            ])->label(false); ?>
        </div>
    </div>
	  	<div class="form-group">
	        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn green-btn' : 'btn green-btn']) ?>
	    </div>
    <?php ActiveForm::end(); ?>
</div>
</div>
</div>
