<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use kartik\date\DatePicker;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
?>
<div class="box box-default">
        <div class="box-header with-border">
          <h3 class="box-title">Assignments</h3>

          <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
            <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
          </div>
        </div>
        <!-- /.box-header -->
    <?php $form = ActiveForm::begin(); ?>
        <div class="box-body">
        <div class="row">
         <div class="col-md-6"> 
    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>
    <?php 
$class_array = ArrayHelper::map(\app\models\RefClass::find()->where(['fk_branch_id'=>Yii::$app->common->getBranch(),'status'=>'active'])->all(), 'class_id', 'title'); 
             echo $form->field($model, 'class_id')->widget(Select2::classname(), [
                            'data' => $class_array,
                            'options' => ['placeholder' => 'Select Class ...','data-url'=>url::to(['general/get-class-details']),'id'=>'getdataclass'],
                            'pluginOptions' => [
                                'allowClear' => true
                            ],
                        ]);
     ?>
    <?php 
    if($model->isNewRecord != 1){
        $model->subject_id='';
        echo $form->field($model, 'subject_id')->widget(Select2::classname(), [
        'options' => ['placeholder' => 'Select Subject','data-url'=>Url::to(['class-timetable/get-subjects-timetable']),'id'=>'getSubjectsdata'],
        'pluginOptions' => [
        'allowClear' => true
        ],
        ]);
    }else{
      
     echo $form->field($model, 'subject_id')->widget(Select2::classname(), [
        'options' => ['placeholder' => 'Select Subject','data-url'=>Url::to(['class-timetable/get-subjects-timetable']),'id'=>'getSubjectsdata'],
        'pluginOptions' => [
        'allowClear' => true
        ],
        ]);
 }
     ?>

    <?= $form->field($model, 'fk_branch_id')->hiddenInput(['value'=>yii::$app->common->getBranch()])->label(false);
    $assignBy = \app\models\User::find()
            ->select(['employee_info.emp_id',"user.id,concat(user.first_name, ' ' ,  user.last_name) as name"])
            ->innerJoin('employee_info','employee_info.user_id = user.id')
            ->where(['employee_info.fk_branch_id'=>yii::$app->common->getBranch(),'employee_info.is_active'=>1])->asArray()->all();
        $assignBy = ArrayHelper::map($assignBy,'id','name');
        echo $form->field($model, 'assign_by')->widget(Select2::classname(), [
        'data'=>$assignBy,
        'options' => ['placeholder' => 'Select Teacher'],
        'pluginOptions' => [
        'allowClear' => true
        ],
        ]);
        echo $form->field($model, 'status')->dropDownList([ 'open' => 'Open', 'onhold' => 'Onhold', 'resolved' => 'Resolved', 'closed' => 'Closed', 'reassign' => 'Reassign', ]);
     ?>
    
</div>
         <div class="col-md-6"> 
    <?= $form->field($model, 'description')->textArea(['rows' => 1]);
    if($model->isNewRecord != 1){
        $model->group_id='';
    echo $form->field($model, 'group_id')->widget(Select2::classname(), [
        'options' => ['placeholder' => 'Select Group','data-url'=>Url::to(['general/get-subjects']),'id'=>'classdatagroup'],
        'pluginOptions' => [
        'allowClear' => true
        ],
        ]);
    }else{
    echo $form->field($model, 'group_id')->widget(Select2::classname(), [
        'options' => ['placeholder' => 'Select Group','data-url'=>Url::to(['general/get-subjects']),'id'=>'classdatagroup'],
        'pluginOptions' => [
        'allowClear' => true
        ],
        ]);
}

    echo  $form->field($model, 'date_of_submission')->widget(DatePicker::classname(), [
                         'options' => ['value' => date('Y/m/d')],
                         'pluginOptions' => [
                             'autoclose'=>true,
                             'format' => 'yyyy/mm/dd',
                             'todayHighlight' => true,
                             //'endDate' => '+0d',
                             //'startDate' => '-2y',
                         ]
                     ]);
     echo $form->field($model, 'image')->fileInput() ?>
    
                 <?php 
                    if($model->isNewRecord !=1){
                    echo $model->image;
                  ?>
                  <?php };
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

