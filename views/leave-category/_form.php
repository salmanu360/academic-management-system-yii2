<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\LeaveCategory */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="leave-category-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'leave_category')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'fk_branch_id')->hiddenInput(['value'=>yii::$app->common->getBranch()])->label(false) ?>

   

    <?php 
    if($model->isNewRecord !=1){
    echo  $form->field($model, 'status')->dropDownList([ 'active' => 'Active', 'inactive' => 'Inactive', ], ['prompt' => '']);
    }else{
    	 
    echo $form->field($model, 'status')->hiddenInput(['value'=>'active'])->label(false);

    }

     ?>

  
	<?php if (!Yii::$app->request->isAjax){ ?>
	  	<div class="form-group">
	        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
	    </div>
	<?php } ?>

    <?php ActiveForm::end(); ?>
    
</div>
