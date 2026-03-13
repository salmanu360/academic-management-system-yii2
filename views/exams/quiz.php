<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
?>
<div class="box box-warning">
    <div class="box-header with-border">
        <h3 class="box-title"><i class="fa fa-search"></i> Select Criteria</h3>
    </div>
    <?php $form = ActiveForm::begin(); ?>
    <div class="box-body">
    <div class="row">
    <div class="col-md-3">
            <?php 
            $class_array = ArrayHelper::map(\app\models\RefClass::find()->where(['fk_branch_id'=>Yii::$app->common->getBranch(),'status'=>'active'])->all(), 'class_id', 'title'); 
            echo  $form->field($model, 'fk_class_id')->dropDownList($class_array, ['id'=>'class-id','prompt' => 'Select'.' '. Yii::t('app','Class')]);

            /*$class_array = ArrayHelper::map(\app\models\RefClass::find()->where(['fk_branch_id'=>Yii::$app->common->getBranch(),'status'=>'active'])->all(), 'class_id', 'title'); 
             echo $form->field($model, 'fk_class_id')->widget(Select2::classname(), [
                            'data' => $class_array,
                            'options' => ['placeholder' => 'Select Class ...','data-url'=>url::to(['general/get-class-details']),'id'=>'getdataclass'],
                            'pluginOptions' => [
                                'allowClear' => true
                            ],
                        ]);*/
         
                        ?>
    </div>
    
    <div class="col-md-3">

    <?php 
    echo $form->field($model, 'group_id')->widget(DepDrop::classname(), [
                            'options' => ['id'=>'group-id'],
                            'pluginOptions'=>[
                            'depends'=>['class-id'],
                            'prompt' => 'Select Group...',
                            'url' => Url::to(['/site/get-group'])
                        ]
                    ]);
      /*echo $form->field($model, 'fk_group_id')->widget(Select2::classname(), [
        'options' => ['placeholder' => 'Select Group','data-url'=>Url::to(['general/get-subjects']),'id'=>'classdatagroup'],
        'pluginOptions' => [
        'allowClear' => true
        ],
        ]);*/
       ?>   
</div>
<div class="col-md-3">

    <?php 
         echo $form->field($model, 'fk_subject_id')->widget(Select2::classname(), [
        'options' => ['placeholder' => 'Select Subject','data-url'=>Url::to(['class-timetable/get-subjects-timetable']),'id'=>'getSubjectsdata'],
        'pluginOptions' => [
        'allowClear' => true
        ],
        ]);
       ?> 
   </div>
</div>  
<?= $form->field($model, 'fk_branch_id')->hiddenInput(['value'=>yii::$app->common->getBranch()])->label(false) ?>
<div class="row">
    <div class="col-md-6">
        <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Save' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>
    </div>
</div>
</div>
    <?php ActiveForm::end(); ?>
</div>