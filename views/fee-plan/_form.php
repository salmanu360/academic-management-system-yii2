<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;
use yii\helpers\Url;
?>
<div class="fee-plan-form">
    <?php $form = ActiveForm::begin(); ?>
    <label for="class">Class</label>
    <?php 
    if($model->isNewRecord !=1){
        $user_id = \app\models\StudentInfo::find()->select(['class_id'])->where(['stu_id'=>$model->stu_id])->one();?>
        <input type="text" class="form-control" value="<?php echo $user_id->class->title; ?>" readonly>
    <?php 
   echo $form->field($model, 'stu_id')->hiddenInput(['value'=>$model->stu_id])->label(false);
}else{
    echo  Html::dropDownList('ref_class', null,
        ArrayHelper::map(\app\models\RefClass::find()->where(['fk_branch_id'=>yii::$app->common->getBranch(),'status'=>'active'])->all(), 'class_id', 'title'),['prompt'=>'Select Class','class'=>'form-control','id'=>'studentClassWise','data-url'=>Url::to(['student/class-wise-students'])]);
    } 
    if($model->isNewRecord !=1){?>
        <label for="">Student</label>
        <input type="text" class="form-control" value="<?php echo yii::$app->common->getName($model->student->user_id); ?>" readonly>
   <?php     
   echo $form->field($model, 'stu_id')->hiddenInput(['value'=>$model->stu_id])->label(false);
    }else{
        $user_id = \app\models\StudentInfo::find()->select(['user_id'])->where(['stu_id'=>$model->stu_id])->one();
        $model->stu_id=(count($model->stu_id)>0)?yii::$app->common->getName($user_id->stu_id):'';
        echo $form->field($model, 'stu_id')->widget(Select2::classname(), [
            'options' => ['placeholder' => 'Select Student','class'=>'classwisestudent'],
            'pluginOptions' => [
                'allowClear' => true
            ],
        ])->label('Student');
    }
    if($model->isNewRecord != 1){?>
    <label for="">Fee Head</label>
        <input type="text" class="form-control" value="<?php echo $model->head->title; ?>" readonly>
    <?php
        echo $form->field($model, 'fee_head_id')->hiddenInput(['value'=>$model->fee_head_id])->label(false);
    }else{
        $head = ArrayHelper::map(\app\models\FeeHead::find()->where(['branch_id'=>yii::$app->common->getBranch(),'extra_head'=>0])->all(), 'id', 'title');
    echo $form->field($model, 'fee_head_id')->widget(Select2::classname(), [
        'data' => $head,
        'options' => ['placeholder' => 'Select Head ...'],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]);
    }
    ?>
    <?php $discountName = ArrayHelper::map(\app\models\FeeDiscountTypes::find()->where(['fk_branch_id'=>yii::$app->common->getBranch()])->all(), 'id', 'title');
    echo $form->field($model, 'fk_fee_discounts_type_id')->widget(Select2::classname(), [
        'data' => $discountName,
        'options' => ['placeholder' => 'Select Discount Type ...'],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]);?>

    <?= $form->field($model, 'discount')->textInput(['required'=>'required']) ?>
    <?= $form->field($model, 'branch_id')->hiddenInput(['value'=>yii::$app->common->getBranch()])->label(false) ?>
	<?php if (!Yii::$app->request->isAjax){ ?>
	  	<div class="form-group">
	        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
	    </div>
	<?php } 
     ActiveForm::end(); ?>
</div>