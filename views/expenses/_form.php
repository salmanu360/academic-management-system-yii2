<?php
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use kartik\date\DatePicker;
use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $model app\models\Expenses */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="expenses-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'fk_branch_id')->hiddenInput(['value'=>yii::$app->common->getBranch()])->label(false); ?>

    <?php $expenseCategoryArray = ArrayHelper::map(\app\models\ExpenseCategory::find()->all(), 'id', 'title');
                        echo $form->field($model, 'expense_category_id')->widget(Select2::classname(), [
                            'data' => $expenseCategoryArray,
                            'options' => ['placeholder' => 'Select Expense Category ...'],
                            'pluginOptions' => [
                                'allowClear' => true
                            ],
                        ]);
                    ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>

    <?php $PaymentMethodArray = ArrayHelper::map(\app\models\PaymentMethod::find()->all(), 'id', 'title');
                        echo $form->field($model, 'payment_mehtod')->widget(Select2::classname(), [
                            'data' => $PaymentMethodArray,
                            'options' => ['placeholder' => 'Select Payment Method ...'],
                            'pluginOptions' => [
                                'allowClear' => true
                            ],
                        ]);
                    ?>

    
    <?=  $form->field($model, 'date')->widget(DatePicker::classname(), [
                         'options' => ['value' => date('Y-m-d')],
                         'pluginOptions' => [
                             'autoclose'=>true,
                             'format' => 'yyyy-mm-dd',
                             'todayHighlight' => true,
                             //'endDate' => '+0d',
                             //'startDate' => '-2y',
                         ]
                     ]);

     ?>

    <?= $form->field($model, 'amount')->textInput(['maxlength' => true,'onkeypress'=>'return event.charCode >= 13 && event.charCode <= 57']) ?>


  
	<?php if (!Yii::$app->request->isAjax){ ?>
	  	<div class="form-group">
	        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
	    </div>
	<?php } ?>

    <?php ActiveForm::end(); ?>
    
</div>
