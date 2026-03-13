<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;
use kartik\depdrop\DepDrop;
use yii\helpers\Url;
$settings = Yii::$app->common->getBranchSettings();
$classArray = ArrayHelper::map(\app\models\RefClass::find()->where(['fk_branch_id'=>Yii::$app->common->getBranch(),'status'=>'active'])->all(),'class_id','title');
$feeHeadArray = ArrayHelper::map(\app\models\FeeHead::find()->where(['branch_id'=>Yii::$app->common->getBranch(),'extra_head'=>0])->all(),'id','title');
?>
<div class="fee-group-form">
    <?php $form = ActiveForm::begin(); ?>
    <?= $form->field($model, 'fk_branch_id')->hiddenInput(['value'=>yii::$app->common->getBranch()])->label(false) ?>
    <?php 
        if($model->isNewRecord == 1){
        echo $form->field($model, 'fk_class_id')->widget(Select2::classname(), [
                            'data' => $classArray,
                            'options' => ['placeholder' => 'Select Class ...','data-url'=>url::to(['general/get-class-details']),'id'=>'getdataclass'],
                            'pluginOptions' => [
                                'allowClear' => true
                            ],
                        ]);   
    }else{
        echo $form->field($model, 'fk_class_id')->dropDownList($classArray, ['prompt'=>'Select class','id'=>'class-id','disabled'=>'disabled']);
    }
        ?>
        <?php
            echo $form->field($model, 'fk_group_id')->widget(Select2::classname(), [
        'options' => ['placeholder' => 'Select Group','data-url'=>Url::to(['general/get-subjects']),'id'=>'classdatagroup'],
        'pluginOptions' => [
        'allowClear' => true
        ],
        ]);?>
    <?= $form->field($model, 'fk_fee_head_id')->widget(Select2::classname(), [
                'data' => $feeHeadArray,
                'options' => ['placeholder' => 'Select Fee Head ...','required'=>'required'],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]);?>
    <?= $form->field($model, 'created_date')->hiddenInput(['value'=>date('Y-m-d')])->label(false) ?>
    <?= $form->field($model, 'updated_date')->hiddenInput(['value'=>date('Y-m-d')])->label(false) ?>
        <?php 
         echo $form->field($model, 'updated_by')->hiddenInput(['value'=>yii::$app->user->identity->id])->label(false);
         ?>
    <?php 

    if($model->isNewRecord != 1){
    }else{
        echo $form->field($model, 'updated_date')->hiddenInput(['value'=>date('Y-m-d')])->label(false);
    }
     ?>
    <?php 
    if($model->isNewRecord != 1){
        echo $form->field($model, 'is_active')->dropDownList([ 'yes' => 'Yes', 'no' => 'No', ], ['prompt' => '']);
    }else{
        echo $form->field($model, 'is_active')->hiddenInput(['value'=>'yes'])->label(false);
    }
     ?>
    <?= $form->field($model, 'amount')->textInput() ?>
	<?php if (!Yii::$app->request->isAjax){ ?>
	  	<div class="form-group">
	        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
	    </div>
	<?php } ?>
    <?php ActiveForm::end(); ?>
</div>
