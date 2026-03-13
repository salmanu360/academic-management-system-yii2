<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;

$class_array = ArrayHelper::map(\app\models\RefClass::find()->where(['fk_branch_id'=>Yii::$app->common->getBranch(),'status'=>'active'])->all(), 'class_id', 'title');
$receivable_array = ArrayHelper::map(\app\models\ReceivableCategory::find()->where(['branch_id'=>Yii::$app->common->getBranch()])->all(), 'id', 'title'); 
?>

<div class="receivable-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'receivable_category')->dropDownList($receivable_array, ['prompt' => 'Select Category ...']); ?>

    <?= $form->field($model, 'class_id')->dropDownList($class_array, ['prompt' => 'Select Class ...']); ?>

    <?= $form->field($model, 'name')->textInput() ?>

    <?= $form->field($model, 'contact')->textInput() ?>

    <?= $form->field($model, 'amount')->textInput() ?>

    <?= $form->field($model, 'branch_id')->hiddenInput(['value'=>yii::$app->common->getBranch()])->label(false) ?>

    <?= $form->field($model, 'created_date')->hiddenInput(['value'=>date('Y-m-d')])->label(false) ?>

  
	<?php if (!Yii::$app->request->isAjax){ ?>
	  	<div class="form-group">
	        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
	    </div>
	<?php } ?>

    <?php ActiveForm::end(); ?>
    
</div>
