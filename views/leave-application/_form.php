<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use kartik\date\DatePicker;



/* @var $this yii\web\View */
/* @var $model app\models\LeaveApplication */
/* @var $form yii\widgets\ActiveForm */
$LeaveCategory = ArrayHelper::map(\app\models\LeaveCategory::find()->where(['fk_branch_id'=>Yii::$app->common->getBranch()])->all(), 'id', 'leave_category');
?>

    <?php $form = ActiveForm::begin(); ?>

<div class="box box-default">
        <div class="box-header with-border">
          <h3 class="box-title">Create Leave Application</h3>

          <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
            <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
          </div>
        </div>
        <!-- /.box-header -->
        <div class="box-body">
         <div class="col-md-6">
            <?= $form->field($model, 'login_id')->hiddenInput(['value'=>yii::$app->user->identity->id])->label(false) ?>

    <?php 
     echo $form->field($model, 'leave_category')->widget(Select2::classname(), [
        'data' => $LeaveCategory,
        'options' => ['placeholder' => 'Select Leave Category ...'],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]);
     ?>

    <?php 
    echo  $form->field($model, 'from_date')->widget(DatePicker::classname(), [
                         'options' => ['value' => date('Y/m/d')],
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
    echo  $form->field($model, 'to_date')->widget(DatePicker::classname(), [
                         'options' => ['value' => date('Y/m/d')],
                         'pluginOptions' => [
                             'autoclose'=>true,
                             'format' => 'yyyy/mm/dd',
                             'todayHighlight' => true,
                             //'endDate' => '+0d',
                             //'startDate' => '-2y',
                         ]
                     ]);

     ?>

    <?= $form->field($model, 'reason')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'fk_branch_id')->hiddenInput(['value'=>yii::$app->common->getBranch()])->label(false) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Submit Application' : 'Update Application', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        <?= Html::a('Cancel',['index'],['class'=>'btn btn-warning'])?>
    </div>
         </div>
         </div>
         </div>


    <?php ActiveForm::end(); ?>

