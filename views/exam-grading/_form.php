<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\ExamGrading */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="exam-grading-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'grade')->textInput(['maxlength' => true,'placeholder'=>'For example:A+ of A-']) ?>

    <?= $form->field($model, 'marks_obtain_from')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'marks_obtain_to')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'grade_name')->textInput(['maxlength' => true ,'placeholder'=>'For example:Outstanding or Good, or Very good']) ?>

  
	<?php if (!Yii::$app->request->isAjax){ ?>
	  	<div class="form-group">
	        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
	    </div>
	<?php } ?>

    <?php ActiveForm::end(); ?>
    
</div>
