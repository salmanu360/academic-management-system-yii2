<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\date\DatePicker;


/* @var $this yii\web\View */
/* @var $model app\models\noticeboard */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="noticeboard-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'fk_branch_id')->hiddenInput(['value'=>yii::$app->common->getBranch()])->label(false); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'notice')->textArea(['maxlength' => true]) ?>

    <?= $form->field($model, 'date')->widget(DatePicker::classname(), [
                         'options' => ['value' => date('Y-m-d')],
                         'pluginOptions' => [
                             'autoclose'=>true,
                             'format' => 'yyyy-mm-dd',
                             'todayHighlight' => true,
                            // 'endDate' => '+0d',
                             //'startDate' => '-2y',
                         ]
                     ]);

     ?>

     <?= $form->field($model, 'end_date')->widget(DatePicker::classname(), [
                         'options' => ['value' => date('Y-m-d')],
                         'pluginOptions' => [
                             'autoclose'=>true,
                             'format' => 'yyyy-mm-dd',
                             'todayHighlight' => true,
                            // 'endDate' => '+0d',
                             //'startDate' => '-2y',
                         ]
                     ]);

     ?>

  
	<?php if (!Yii::$app->request->isAjax){ ?>
	  	<div class="form-group">
	        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
	    </div>
	<?php } ?>

    <?php ActiveForm::end(); ?>
    
</div>
