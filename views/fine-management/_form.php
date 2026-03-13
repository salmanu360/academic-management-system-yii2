<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\date\DatePicker;
use kartik\depdrop\DepDrop;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
$settings = Yii::$app->common->getBranchSettings();
$class_array = ArrayHelper::map(\app\models\RefClass::find()->where(['fk_branch_id'=>Yii::$app->common->getBranch(),'status'=>'active'])->all(), 'class_id', 'title');

/* @var $this yii\web\View */
/* @var $model app\models\FineClassWise */
/* @var $form yii\widgets\ActiveForm */
if($model->isNewRecord){
    $model->status = 'active';
}
?>

<div class="fine-class-wise-form">

    <?php $form = ActiveForm::begin(); ?>
 
    <?= $form->field($model, 'class_id')->dropDownList($class_array, ['id'=>'class-id','prompt' => 'Select '.Yii::t('app','Class').'...']); ?>

    <?= $form->field($model, 'amount')->textInput(['type'=>'number']) ?>

    <?= $form->field($model, 'status')->dropDownList([ 'active' => 'Active', 'inactive' => 'Inactive', ], ['prompt' => 'Select Status']) ?>
 

  
	<?php if (!Yii::$app->request->isAjax){ ?>
	  	<div class="form-group">
	        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
	    </div>
	<?php } ?>

    <?php ActiveForm::end(); ?>
    
</div>
