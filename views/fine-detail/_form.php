<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use kartik\select2\Select2;
$FineType = ArrayHelper::map(\app\models\FineType::find()->where(['fk_branch_id'=>Yii::$app->common->getBranch(),'status'=>'active'])->all(), 'id', 'title');
?>
<div class="fine-detail-form">
    <?php $form = ActiveForm::begin(); ?>
    <?php 
    echo $form->field($model, 'fk_fine_typ_id')->widget(Select2::classname(), [
        'data' => $FineType,
        'options' => ['placeholder' => 'Select Fine Type ...'],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]); ?>
    <label for="class">Class</label> 
    <?= Html::dropDownList('ref_class', null,
      ArrayHelper::map(\app\models\RefClass::find()->where(['fk_branch_id'=>yii::$app->common->getBranch(),'status'=>'active'])->all(), 'class_id', 'title'),['prompt'=>'Select Class','class'=>'form-control','id'=>'studentClassWise','data-url'=>Url::to(['student/class-wise-students'])]) ?>
     <br />
     <?php 
     $model->fk_stu_id ="";
      echo $form->field($model, 'fk_stu_id')->widget(Select2::classname(), [
        'options' => ['placeholder' => 'Select Student','class'=>'classwisestudent'],
        'pluginOptions' => [
        'allowClear' => true
        ],
        ])->label('Student');
       ?>
    <?= $form->field($model, 'fk_branch_id')->hiddenInput(['value'=>yii::$app->common->getBranch()])->label(false) ?>

    <?= $form->field($model, 'created_date')->hiddenInput(['value'=>date('Y-m-d')])->label(false) ?>
    <?= $form->field($model, 'updated_date')->hiddenInput(['value'=>date('Y-m-d')])->label(false)?>
    <?= $form->field($model, 'remarks')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'amount')->textInput() ?>
    <?php
    if($model->isNewRecord !=1){
     echo $form->field($model, 'is_active')->dropDownList([ 'yes' => 'Yes', 'no' => 'No', ], ['prompt' => '']);
    }else{
     echo $form->field($model, 'is_active')->hiddenInput(['value'=>'yes'])->label(false);
    }
    ?>
    <?= $form->field($model, 'payment_received')->textInput() ?>
	<?php if (!Yii::$app->request->isAjax){ ?>
	  	<div class="form-group">
	        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
	    </div>
	<?php } ?>
    <?php ActiveForm::end(); ?>
</div>