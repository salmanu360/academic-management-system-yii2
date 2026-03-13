<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\RefClass;
use app\models\User;
use kartik\date\DatePicker;
use kartik\select2\Select2;
use yii\helpers\Url;
?>
<?php if (Yii::$app->session->hasFlash('Warning')): ?>
          <div class="alert alert-danger">
              <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
              <?= Yii::$app->session->getFlash('Warning') ?>
          </div>
            <?php endif; ?>
    <?php $form = ActiveForm::begin(); ?>
<div class="box box-default">
        <div class="box-header with-border">
          <h3 class="box-title">Issue Book</h3>

          <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
            <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
          </div>
        </div>
        <!-- /.box-header -->
        <div class="box-body">
        <div class="row">
        <div class="col-md-6">

   <?php
    if($model->isNewRecord!=1){
        echo $form->field($model, 'status')->dropDownList(['return' => 'Return', 'renewal' => 'Renewal'],['prompt'=>'Select Status']);
    }else{
       echo $form->field($model, 'status')->hiddenInput(['value' => 'issued'])->label(false);
    }

      ?>
    <?php
    if($model->isNewRecord!=1){
     }else{

     $bookList = ArrayHelper::map(\app\models\AddBooks::find()->where(['fk_branch_id'=>yii::$app->common->getBranch(),'status'=>'active'])->all(), 'id', 'title');
                        echo $form->field($model, 'book_id')->widget(Select2::classname(), [
                            'data' => $bookList,
                            'options' => ['placeholder' => 'Select Books ...','class'=>'departmentDesignation','data-url'=>url::to(['empsloyee/get-designation'])],
                            'pluginOptions' => [
                                'allowClear' => true
                            ],
                        ]);
                    }
                    ?>

     <?php
     if($model->isNewRecord!=1){
     }else{
      echo Html::label('User Type');
     $userType= ['1' => 'Student','2' => 'Employee'];
    echo Html::activeDropDownList($model, 'user_type',$userType,['prompt'=>'Select User Type','class'=>'form-control userType']);
    }
    ?><br />
    <div class="employeshowForLibrary" style="display: none" >
    <?php 
    $stuQuery = User::find()
            ->select(['employee_info.emp_id',"user.id,concat(user.first_name, ' ' ,  user.last_name) as name"])
            ->innerJoin('employee_info','employee_info.user_id = user.id')
            ->where(['employee_info.fk_branch_id'=>yii::$app->common->getBranch()])->asArray()->all();
        $stuArray = ArrayHelper::map($stuQuery,'id','name');
        echo $form->field($model, 'user_id')->widget(Select2::classname(), [
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
                 <?php $settings = Yii::$app->common->getBranchSettings();
                         ?>
                  <?= Html::dropDownList('ref_class', null,
                      ArrayHelper::map(RefClass::find()->where(['fk_branch_id'=>yii::$app->common->getBranch(),'status'=>'active'])->all(), 'class_id', 'title'),['prompt'=>'Select Class','class'=>'form-control','id'=>'studentClassWise','data-url'=>Url::to(['student/class-wise-students'])]) ?>
   </div>
    <br />

     <div class="showClassWiseStudent" style="display: none">
      <?php 
      echo $form->field($model, 'user_ids')->widget(Select2::classname(), [
        'options' => ['placeholder' => 'Select Student','class'=>'classwisestudent'],
        'pluginOptions' => [
        'allowClear' => true
        ],
        ])->label('Student');
       ?>
    </div><br />
    <?php 
    if($model->isNewRecord!=1){

    }else{

    echo  $form->field($model, 'issue_date')->widget(DatePicker::classname(), [
                         'options' => ['value' => date('Y/m/d')],
                         'pluginOptions' => [
                             'autoclose'=>true,
                             'format' => 'yyyy/mm/dd',
                             'todayHighlight' => true,
                             //'endDate' => '+0d',
                             //'startDate' => '-2y',
                         ]
                     ]);
    }
     ?>

     <?php 
     if($model->isNewRecord!=1){?>
     <div id="renewalBook" style="display: none;">
         <?php 
            echo $form->field($model, 'due_date')->widget(DatePicker::classname(), [
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
     </div>

   <?php }else{
     echo  $form->field($model, 'due_date')->widget(DatePicker::classname(), [
                         'options' => ['value' => date('Y/m/d')],
                         'pluginOptions' => [
                             'autoclose'=>true,
                             'format' => 'yyyy/mm/dd',
                             'todayHighlight' => true,
                             //'endDate' => '+0d',
                             //'startDate' => '-2y',
                         ]
                     ]);
 }
     ?>

     <?php 
     if($model->isNewRecord!=1){
       echo $form->field($model, 'return_date')->widget(DatePicker::classname(), [
                         'options' => ['value' => date('Y/m/d')],
                         'pluginOptions' => [
                             'autoclose'=>true,
                             'format' => 'yyyy/mm/dd',
                             'todayHighlight' => true,
                             //'endDate' => '+0d',
                             //'startDate' => '-2y',
                         ]
                     ]);
    }else{
      
  }
     ?>



    <?php
    if($model->isNewRecord!=1){

     echo $form->field($model, 'fine')->textInput(['maxlength' => true]);
    }else{
         }
    ?>

    <?php 
    if($model->isNewRecord!=1){

        echo $form->field($model, 'remarks')->textInput(['rows' => 6]);
    }else{
        }?>

    

    <?= $form->field($model, 'fk_branch_id')->hiddenInput(['value'=>yii::$app->common->getBranch()])->label(false); ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Issue' : 'Return/Renew', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>
    </div>

    </div>
    </div>
    </div>

