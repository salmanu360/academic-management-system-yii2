<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use kartik\date\DatePicker;
use kartik\depdrop\DepDrop;
use yii\helpers\Url;
$this->title='Outsider Student';
?>

<div class="student-outside-form">
<div class="box box-warning">
    <div class="box-header with-border">
        <h3 class="box-title"> Add Outside Student</h3>
    </div>
    <?php $form = ActiveForm::begin(); ?>
    <div class="box-body">
    <div class="row">
    <div class="col-md-6">

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]);
    $class_array = ArrayHelper::map(\app\models\RefClass::find()->where(['fk_branch_id'=>Yii::$app->common->getBranch(),'status'=>'active'])->all(), 'class_id', 'title');
        echo $form->field($model, 'class_id')
        ->dropDownList($class_array, ['id'=>'class-id','prompt' => 'Select'.' '. Yii::t('app','Class'),'class'=>'form-control classprev'])->label(Yii::t('app','Class').' <span style="color:#a94442;">*</span>');
        echo $form->field($model, 'section_id')->widget(DepDrop::classname(), [
                                'options' => ['id'=>'section-id','class'=>'form-control sectionPrev'],
                                'pluginOptions'=>[
                                    'depends'=>[
                                        'group-id','class-id'
                                    ],
                                    'prompt' => 'Select section',
                                    'url' => Url::to(['/site/get-section'])
                                ]
                            ])->label(Yii::t('app','Section').'<span style="color:#a94442;">*</span>');
        echo $form->field($model, 'organization')->textInput();
        echo $form->field($model, 'parent_contact')->textInput(['maxlength' => true,'onkeypress'=>'return event.charCode >= 13 && event.charCode <= 57','placeholder'=>'923469475085'])->label('Parent Contact (optional)');
       
                        
     ?>

    </div>
    <div class="col-md-6">
    <?= $form->field($model, 'parent_name')->textInput();
    echo $form->field($model, 'group_id')->widget(DepDrop::classname(), [
                    'options' => ['id'=>'group-id','class'=>'form-control groupPrev'],
                    'pluginOptions'=>[
                        'depends'=>['class-id'],
                        'prompt' => 'Select Group...',
                        'url' => Url::to(['/site/get-group'])
                    ]
                ]);
    echo $form->field($model, 'regesteration_date')->widget(DatePicker::classname(), [
                         'options' => ['value' => date('Y/m/d')],
                         'pluginOptions' => [
                             'autoclose'=>true,
                             'format' => 'yyyy/mm/dd',
                             'todayHighlight' => true,
                             'endDate' => '+0d',
                         ]
                     ]);
         echo $form->field($model, 'address')->textInput();
        echo  $form->field($model, 'contact_no')->textInput(['maxlength' => true,'onkeypress'=>'return event.charCode >= 13 && event.charCode <= 57','placeholder'=>'923469475085']);
         echo $form->field($model, 'branch_id')->hiddenInput(['value'=>yii::$app->common->getBranch()])->label(false);
     ?>
        
    </div>
    </div>
    <div class="row">
    <div class="col-md-6">
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>
    </div>
    </div>
    </div>
    <?php ActiveForm::end(); ?>

    </div>
    </div>
