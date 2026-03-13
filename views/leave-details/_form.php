<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;


/* @var $this yii\web\View */
/* @var $model app\models\LeaveDetails */
/* @var $form yii\widgets\ActiveForm */
$LeaveCategory = ArrayHelper::map(\app\models\LeaveCategory::find()->where(['fk_branch_id'=>Yii::$app->common->getBranch()])->all(), 'id', 'leave_category');
$designation = ArrayHelper::map(\app\models\RefDesignation::find()->where(['fk_branch_id'=>Yii::$app->common->getBranch()])->all(), 'designation_id', 'Title');
?>

<div class="leave-details-form">

    <?php $form = ActiveForm::begin(); ?>

    <?php 
     echo $form->field($model, 'leave_category')->widget(Select2::classname(), [
        'data' => $LeaveCategory,
        'options' => ['placeholder' => 'Select Leave Category ...'],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]);
     ?>

     <?php 
     echo $form->field($model, 'designation')->widget(Select2::classname(), [
        'data' => $designation,
        'options' => ['placeholder' => 'Select Designation ...'],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]);
     ?>

    <?= $form->field($model, 'count')->textInput(['maxlength' => true,'onkeypress'=>'return event.charCode >= 13 && event.charCode <= 57']) ?>


    <?= $form->field($model, 'fk_branch_id')->hiddenInput(['value'=>yii::$app->common->getBranch()])->label(false) ?>
    

  
	<?php if (!Yii::$app->request->isAjax){ ?>
	  	<div class="form-group">
	        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
	    </div>
	<?php } ?>

    <?php ActiveForm::end(); ?>
    
</div>
