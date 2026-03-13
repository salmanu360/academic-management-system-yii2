<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\RefClass;
$class = ArrayHelper::map(RefClass::find()->where(['fk_branch_id'=>Yii::$app->common->getBranch(),'status'=>'active'])->all(),'class_id','title');
?>

<div class="subjects-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'fk_class_id')->dropDownList($class, ['prompt' => 'Select '.Yii::t('app','Class'),'data-url'=>\yii\helpers\Url::to(['subjects/get-groups'])]) ?>

    <?php if($model->isNewRecord !=1){

     echo $form->field($model, 'fk_group_id')->textInput(['value'=>$model->fk_group_id]);
    }else{
        echo $form->field($model, 'fk_group_id')->dropDownList([], ['prompt' => 'Select '.Yii::t('app','Group')]);
    }?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true])->label('Subject Name  <span class="required" style="color:red">*</span>') ?>
    
    <?= $form->field($model, 'code')->textInput(['maxlength' => true])->label('Subject Code') ?>
    <?=$form->field($model, 'is_division')->radioList([0 => 'Subject', 1 => 'Sub Subject'], ['itemOptions' => ['class' =>'radio-inline']])->label('Details<span style="color:red"> *</span>')?>
    <?php
    if($model->isNewRecord == 1){
        echo $form->field($model, 'status')->hiddenInput(['value'=>'Active'])->label(false);
    }else{
        echo $form->field($model, 'status')->dropDownList([ 'active' => 'Active', 'inactive' => 'Inactive', ], ['prompt' => 'Select Status...']);
    }
     

      ?>
  
	<?php if (!Yii::$app->request->isAjax){ ?>
	  	<div class="form-group">
	        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn green-btn' : 'btn green-btn']) ?>
	    </div>
	<?php } ?>

    <?php ActiveForm::end(); ?>
    
</div>
