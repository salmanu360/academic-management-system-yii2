<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\FeeHead */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="fee-head-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>
    
    <?=$form->field($model, 'extra_head')->radioList([1 => 'Yes', 0 => 'No'], ['itemOptions' => ['class' =>'radio-inline']])?>

    <?=$form->field($model, 'one_time_payment')->radioList([1 => 'Yes', 0 => 'No'], ['itemOptions' => ['class' =>'radio-inline']])?>
    
    <?=$form->field($model, 'promotion_head')->radioList([1 => 'Yes', 0 => 'No'], ['itemOptions' => ['class' =>'radio-inline']])?>

    <?= $form->field($model, 'date')->hiddenInput(['value'=>date('Y-m-d H:i:s')])->label(false); ?>

    <?= $form->field($model, 'branch_id')->hiddenInput(['value'=>yii::$app->common->getbranch()])->label(false); ?>

  
	<?php if (!Yii::$app->request->isAjax){ ?>
	  	<div class="form-group">
	        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
	    </div>
	<?php } ?>

    <?php ActiveForm::end(); ?>
    
</div>
