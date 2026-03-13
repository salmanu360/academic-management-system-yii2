<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\date\DatePicker;
use kartik\datetime\DateTimePicker;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;
use yii\helpers\Url;
use yii\widgets\MaskedInput;
?>
<div class="visitors-form">
    <div class="box box-default">
        <div class="box-header with-border">
          <h3 class="box-title">Add Visitor</h3>

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
               <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
               <?= $form->field($model, 'phone')->textInput(['maxlength' => true,'onkeypress'=>'return event.charCode >= 13 && event.charCode <= 57','placeholder'=>'923469475085']) ?>

               <?= $form->field($model, 'company')->textInput(['maxlength' => true]) ?>
               <?php 
               echo Html::label('To meet user type');
               $userType= ['1' => 'Student','2' => 'Employee'];
               echo Html::activeDropDownList($model, 'user_type',$userType,['prompt'=>'Select User Type','class'=>'form-control userType']);
               echo '<br />';
               echo $form->field($model, 'address')->textarea(['rows' => 4]) ?>

           </div>
           <div class="col-md-6"> 
               <?= $form->field($model, 'cnic')->widget(\yii\widgets\MaskedInput::className(), [
                'mask' => ['99999-9999999-9', '9999-999-99999']]) ?>
               <?= $form->field($model, 'email')->widget(\yii\widgets\MaskedInput::className(),
                 [
                 'clientOptions' => [
                 'alias' =>  'email'
                    ],
                  ]); ?>
               <?= $form->field($model, 'representing')->dropDownList([ 1 => 'Vendor', 2 => 'Friend', 3 => 'Family', 4 => 'Interview', 5 => 'Meeting', 6 => 'Other', 7 => 'Visit']) ?> 
               <div class="employeshowForLibrary" style="display: none" >
                <?php 
                $stuQuery = \app\models\User::find()
                ->select(['employee_info.emp_id',"user.id,concat(user.first_name, ' ' ,  user.last_name) as name"])
                ->innerJoin('employee_info','employee_info.user_id = user.id')
                ->where(['employee_info.fk_branch_id'=>yii::$app->common->getBranch()])->asArray()->all();
                $stuArray = ArrayHelper::map($stuQuery,'id','name');
                echo $form->field($model, 'to_meet')->widget(Select2::classname(), [
        // 'data' => Yii::$app->common->getBranchEmployee(),
                    'data'=>$stuArray,
                    'options' => ['placeholder' => 'Select Employee'],
                    'pluginOptions' => [
                    'allowClear' => true
                    ],
                    ]);

                    ?>
                </div>
                <div class="getClassForLibrary" style="display: none">
                    <label for="class">Class</label>
                    <?= Html::dropDownList('ref_class', null,
                    ArrayHelper::map(\app\models\RefClass::find()->where(['fk_branch_id'=>yii::$app->common->getBranch(),'status'=>'active'])->all(), 'class_id', 'title'),['prompt'=>'Select Class','class'=>'form-control','id'=>'studentClassWise','data-url'=>Url::to(['student/class-wise-students'])]) ?>
                </div>
                <div class="showClassWiseStudent" style="display: none">
                  <?php 
                  echo $form->field($model, 'to_meet_stu')->widget(Select2::classname(), [
                    'options' => ['placeholder' => 'Select Student','class'=>'classwisestudent'],
                    'pluginOptions' => [
                    'allowClear' => true
                    ],
                    ])->label('Student');
                    ?>
                </div>
                <?= $form->field($model, 'date')->widget(DateTimePicker::classname(), [
                    'type' => DateTimePicker::TYPE_INPUT,
                    'options' => ['value' => date('Y-m-d h:i A')],
                    'pluginOptions' => [
                    'autoclose' => true,
                    'format' => 'yyyy-mm-dd HH:ii P',
                    'todayHighlight' => true,
                    ]
                    ]) ?>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                 <?= $form->field($model, 'branch_id')->hiddenInput(['value'=>yii::$app->common->getBranch()])->label(false); ?>

                 <div class="form-group">
                    <?= Html::submitButton($model->isNewRecord ? 'Add' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
                </div> 
            </div>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>   
</div>
