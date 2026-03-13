<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\MessageControl */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="message-control-form">

    <?php $form = ActiveForm::begin(); ?>

    <?php
        echo $form->field($model, 'message_id')->dropDownList(
            ['prompt'=>'Select','admission' => 'Admission', 'hiring' => 'Hiring']
    ); ?>

    <?= $form->field($model, 'message')->textarea(['rows' => 6,'placeholder'=>'Type your message but below will be used as at message end.(student Name Login is Register No. & password is password(http://33344450.com/schoolName))']) ?>

    <?= $form->field($model, 'branch_id')->hiddenInput(['value'=>Yii::$app->common->getBranch()])->label(false) ?>

    <?= $form->field($model, 'created_at')->hiddenInput(['value'=>date('Y-m-d')])->label(false) ?>

    <?= $form->field($model, 'created_by')->hiddenInput(['value'=>Yii::$app->user->id])->label(false) ?>

  
	<?php if (!Yii::$app->request->isAjax){ ?>
	  	<div class="form-group">
	        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
	    </div>
	<?php } ?>

    <?php ActiveForm::end(); ?>
    
</div>
