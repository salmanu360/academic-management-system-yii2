<?php
use yii\helpers\Html; 
use yii\widgets\ActiveForm; 
use kartik\date\DatePicker;
?> 
<div class="sms-settings-form"> 
    <?php $form = ActiveForm::begin(); ?> 
    <?= $form->field($model, 'status')->dropDownList([ '0', '1', ], ['prompt' => '']) ?>
    <?php
    echo $form->field($model, 'date')->widget(DatePicker::classname(), [
                         'options' => ['value' => $model->date],
                         'pluginOptions' => [
                             'autoclose'=>true,
                             'format' => 'yyyy/mm/dd',
                             'todayHighlight' => true,
                             //'endDate' => '+0d',
                             //'startDate' => '-2y',
                         ]
                     ]);
     ?>
     <?php
    echo $form->field($model, 'sms_expiry_date')->widget(DatePicker::classname(), [
                         'options' => ['value' => $model->sms_expiry_date],
                         'pluginOptions' => [
                             'autoclose'=>true,
                             'format' => 'yyyy/mm/dd',
                             'todayHighlight' => true,
                             //'endDate' => '+0d',
                             //'startDate' => '-2y',
                         ]
                     ]);
     ?>
    <?= $form->field($model, 'fk_branch_id')->hiddenInput(['value'=>yii::$app->common->getBranch()])->label(false) ?>
    <?= $form->field($model, 'mask')->textInput() ?>
    <?= $form->field($model, 'school_name')->textInput() ?>
    <div class="form-group"> 
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?> 
    </div> 
    <?php ActiveForm::end(); ?> 
</div> 