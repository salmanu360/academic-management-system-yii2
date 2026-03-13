<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\FeeDiscountTypes */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="fee-discount-types-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <div class="col-md-6"> <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?></div>
        <div class="col-md-6"> <?= $form->field($model, 'description')->textInput(['maxlength' => true]) ?></div>
    </div>
    <div class="row">
        <?php
        if($model->isNewRecord){
            $model->is_active=1;
        }
        ?>
        <div class="col-md-6"> <?= $form->field($model, 'is_active')->dropDownList([ 1 => 'Yes', 0 => 'No'], ['prompt' => 'Select Status']) ?></div>
        <div class="col-md-6"></div>
    </div>
  
	<?php if (!Yii::$app->request->isAjax){ ?>
	  	<div class="form-group">
	        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn green-btn' : 'btn green-btn']) ?>
	    </div>
	<?php } ?>

    <?php ActiveForm::end(); ?>
    
</div>
