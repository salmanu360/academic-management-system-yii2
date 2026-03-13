<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use yii\widgets\MaskedInput;
use app\models\RefGardianType;
use app\models\RefCountries;
use app\models\RefProvince;
use app\models\RefCities;
use app\models\RefDesignation;
use app\models\RefDepartment;
use app\models\RefReligion;
use app\models\RefDistrict;
use kartik\date\DatePicker;
use kartik\select2\Select2;
use yii\helpers\Url;
use app\models\Profession;
?>
<div onload="myFunction()"></div>
<div class="employee-info-form" style="margin-top: -20px">
    <?php 
    $form = ActiveForm::begin([
    'id' => 'employeeForm',
    'class'=>'mform',
    ]);
     ?>
     <!-- new form -->     
<div class="box box-default">
        <div class="box-header with-border">
          <h3 class="box-title">Employee Personnel Info </h3><span class="pull-center" style="margin-left:120px;color: #d45252">Note: * Input Cannot be blank</span>

          <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
            <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
          </div>
        </div>
        <!-- /.box-header -->
        <div class="box-body">
         <div class="col-md-6"> 
                <?php echo Html::label('Registration No.'); ?>
                <span class="required" style="color:red">*</span>
                <?= $form->field($usermodel, 'username')->textInput(['readonly' => !$usermodel->isNewRecord,'id'=>'registerationemployee','data-url'=>Url::to(['student/validate-usrname']),'style' => 'text-transform: uppercase'])->label(false) ?>
                <label for="" id="usernameEmployee"></label>
                <span class="regNo" style="color:red"></span>  
                  
                <?php echo Html::label('First Name'); ?>
                <span class="required" style="color:red">*</span>
                <?= $form->field($usermodel, 'first_name')->textInput(['maxlength' => true,'style' => 'text-transform: uppercase'])->label(false) ?> 
                 <?= $form->field($usermodel, 'middle_name')->textInput(['maxlength' => true,'style' => 'text-transform: uppercase']) ?> 
                
                <?= $form->field($usermodel, 'last_name')->textInput(['maxlength' => true,'style' => 'text-transform: uppercase']) ?> 
                <?= $form->field($model, 'contact_no')->textInput(['maxlength' => true,'placeholder'=>'923001234567','onkeypress'=>'return event.charCode >= 13 && event.charCode <= 57'])->label('Contact No.<span style="color:red"> *</span>') ?>
                <?= $form->field($usermodel, 'email')->widget(\yii\widgets\MaskedInput::className(),
                 [
                 'clientOptions' => [
                 'alias' =>  'email'
                    ],
                  ]); ?>
                  <?= $form->field($model, 'cnic')->widget(\yii\widgets\MaskedInput::className(), [
                'mask' => ['99999-9999999-9', '9999-999-99999']]) ?>
                 <?php echo Html::label('Department'); ?>
                <span class="required" style="color:red">*</span>  
                 <?php $department_array = ArrayHelper::map(\app\models\RefDepartment::find()->where(['fk_branch_id'=>yii::$app->common->getBranch()])->all(), 'department_type_id', 'Title');
                        echo $form->field($model, 'department_type_id')->widget(Select2::classname(), [
                            'data' => $department_array,
                            'options' => ['prompt' => 'Select Department ...','class'=>'departmentDesignation form-control','data-url'=>url::to(['employee/get-designation'])],
                            'pluginOptions' => [
                                'allowClear' => true
                            ],
                        ])->label(false);
                    ?>
                    <?php echo Html::label('Designation'); ?>
                <span class="required" style="color:red">*</span> 
                    <?php
                    if($model->isNewRecord == 1){
                        $designation_array = ArrayHelper::map(\app\models\RefDesignation::find()->where(['fk_branch_id'=>yii::$app->common->getBranch()])->all(), 'designation_id', 'Title');
                        echo $form->field($model, 'designation_id')->widget(Select2::classname(), [
                            'options' => ['prompt' => 'Select Designation ...','class'=>'getDesignation form-control'],
                            'pluginOptions' => [
                                'allowClear' => true
                            ],
                        ])->label(false);
                    }else{
                        $designation_array = ArrayHelper::map(\app\models\RefDesignation::find()->where(['fk_branch_id'=>yii::$app->common->getBranch()])->all(), 'designation_id', 'Title');
                        echo $form->field($model, 'designation_id')->widget(Select2::classname(), [
                            'data' => $designation_array,
                            'options' => ['placeholder' => 'Select Designation ...','class'=>'getDesignation'],
                            'pluginOptions' => [
                                'allowClear' => true
                            ],
                        ])->label(false);
                    }
                    ?>
                    <div class="spouse" style="display: none">
                        <?php if($usermodel->isNewRecord!='1'){
                        if(!empty($model2->spouse_name) && !empty($model2->no_of_children)){?>
                        <?= $form->field($model2, 'spouse_name')->textInput() ?>
                        <?php } }?>
                    </div>
                     <!-- if married or divorced -->
                         <?php if($usermodel->isNewRecord!='1'){
                            if(!empty($model2->spouse_name) && !empty($model2->no_of_children)){
                             echo  $form->field($model2, 'spouse_name')->textInput();
                        }else{  } }else{ } ?>
                    
             </div>
            <div class="col-md-6">
                <?php echo Html::label('Assign Role');?>
                 <span class="required" style="color:red">*</span>
                 <?php $roleassign= ['1' => 'Administrator','4' => 'Teacher', '5' => 'Accountant', '6' => 'Librarian'];
                 echo $form->field($usermodel, 'fk_role_id')->dropDownList($roleassign,['prompt'=>'Select Role','class'=>'form-control selectpicker assignrole','data-live-search'=>'true','data-live-search-style'=>'begins'])->label(false) ?>
                 <span id="assignroleError" style="color: red"></span>
            <?php
                if($model->isNewRecord == 1){
                  echo  $form->field($model, 'dob')->widget(DatePicker::classname(), [
                         'options' => ['value' => date('Y/m/d')],
                         'pluginOptions' => [
                             'autoclose'=>true,
                             'format' => 'yyyy/mm/dd',
                             'todayHighlight' => true,
                             'endDate' => '+0d',
                             //'startDate' => '-2y',
                         ]
                     ])->label('DOB<span style="color:red"> *</span>');
                }else{
                    echo $form->field($model, 'dob')->widget(DatePicker::classname(), [
                         //'options' => ['value' => date('Y/m/d')],
                         'pluginOptions' => [
                             'autoclose'=>true,
                             'format' => 'yyyy/mm/dd',
                             'todayHighlight' => true,
                             'endDate' => '+0d',
                             //'startDate' => '-2y',
                         ]
                     ])->label('DOB<span style="color:red"> *</span>');

                }
                 ?>
              <?php if($model->isNewRecord == 1){
                  echo $form->field($model, 'hire_date')->widget(DatePicker::classname(), [
                         'options' => ['value' => date('Y/m/d')],
                         'pluginOptions' => [
                             'autoclose'=>true,
                             'format' => 'yyyy/mm/dd',
                             'todayHighlight' => true,
                         ]
                     ])->label('Joining Date<span style="color:red"> *</span>');
                     }else{
                       echo  $form->field($model, 'hire_date')->widget(DatePicker::classname(), [
                         //'options' => ['value' => date('Y/m/d')],
                         'pluginOptions' => [
                             'autoclose'=>true,
                             'format' => 'yyyy/mm/dd',
                             'todayHighlight' => true,
                             //'endDate' => '+0d',
                             //'startDate' => '-0d',
                              ]
                     ])->label('Joining Date<span style="color:red"> *</span>');
                     }
                     ?>
                <?= $form->field($model, 'emergency_contact_no')->textInput(['maxlength' => true,'onkeypress'=>'return event.charCode >= 13 && event.charCode <= 57']) ?>
                 <?php echo Html::label('Religion'); ?> <span style="color:red"></span>
                <span class="required" style="color:red">*</span>    
                 <?php $religion_array = ArrayHelper::map(\app\models\RefReligion::find()->all(), 'religion_type_id', 'Title');
                        echo $form->field($model, 'religion_type_id')->widget(Select2::classname(), [
                            'data' => $religion_array,
                            //'options' => ['placeholder' => 'Select Religion ...'],
                            'pluginOptions' => [
                                'allowClear' => true
                            ],
                        ])->label(false);
                    ?>
               <?= $form->field($model, 'Nationality')->textInput(['maxlength' => true,'value'=>'pakistani'])->label('Nationality<span style="color:red"> *</span>') ?>
               <?= $form->field($usermodel, 'Image')->fileInput() ?>
                 <?php if($usermodel->isNewRecord!='1'){
                    if(!empty($usermodel->Image)){
                    $src=Yii::$app->request->baseUrl.'/uploads/'.$usermodel->Image;
                    echo Html::img( $src, $options = ['width'=>60,'height'=>'60'] );
                } } ?>
                <div style="height: 24px"></div>
                <?=$form->field($model, 'gender_type')->radioList([1 => 'Male', 0 => 'Female'], ['itemOptions' => ['class' =>'radio-inline']])->label('Gender<span style="color:red"> *</span>')?>
                 <?= $form->field($model, 'marital_status')->radioList([1 => 'Single', 2 => 'Married',3 =>'Divorced'], ['itemOptions' => ['class' =>'radio-inline maritial_status' ]]) ?>
                 <div class="spouse" style="display: none">
                     <?php if($usermodel->isNewRecord!='1'){
                        if(!empty($model2->spouse_name) && !empty($model2->no_of_children)){?>
                    <?= $form->field($model2, 'no_of_children')->textInput() ?>
                    <?php } }?>
                 </div>
                 <?php if($usermodel->isNewRecord!='1'){
                            if(!empty($model2->spouse_name) && !empty( $model2->no_of_children)){
                             echo  $form->field($model2, 'no_of_children')->textInput();
                        }else{ } }else{ }?>
                        
            </div>
            <!-- /.col -->
          </div>
          <!-- /.row -->
        </div>
        <!-- /.box-body -->
      </div>
     <!-- end of personnel info -->
    <div class="box box-default">
        <div class="box-header with-border">
          <h3 class="box-title">Employee Parents Info</h3>
          <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
            <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
          </div>
        </div>
        <!-- /.box-header -->
        <div class="box-body">
          <div class="row">
            <div class="col-md-6">
            <?php echo Html::label('First Name'); ?>
            <span class="required" style="color:red">*</span>
            <?= $form->field($model2, 'first_name')->textInput(['maxlength' => true,'style' => 'text-transform: uppercase','id'=>'empParentsFirstName','required'=>'required'])->label(false); ?>
            <span id="pfirstName" style="color: red"></span>
            <?= $form->field($model2, 'middle_name')->textInput(['maxlength' => true,'style' => 'text-transform: uppercase']) ?>
            <?= $form->field($model2, 'last_name')->textInput(['maxlength' => true,'style' => 'text-transform: uppercase']) ?>
            <?= $form->field($model2, 'contact_no')->textInput(['maxlength' => true,'onkeypress'=>'return event.charCode >= 13 && event.charCode <= 57']) ?>
            <?= $form->field($model2, 'fk_branch_id')->HiddenInput(['value'=>Yii::$app->common->getBranch()])->label(false) ?>
            <?=$form->field($model2, 'gender')->radioList([1 => 'Male', 0 => 'Female'], ['itemOptions' => ['class' =>'radio-inline']])?>
        </div>
            <!-- /.col -->
            <div class="col-md-6">
             
              <?= $form->field($model2, 'cnic')->widget(\yii\widgets\MaskedInput::className(), [
            'mask' => ['99999-9999999-9', '9999-999-99999']
            ]) ?>
             <?php $Professions= ArrayHelper::map(\app\models\Profession::find()->all(),'id','title');
                            echo $form->field($model2, 'profession')->widget(Select2::classname(), [
                                'data' => $Professions,
                                'options' => ['placeholder' => 'Select Profession ...'],
                                'pluginOptions' => [
                                    'allowClear' => true
                                ],
                            ]);
                            ?>
             <?= $form->field($model2, 'email')->widget(\yii\widgets\MaskedInput::className(),
                 [
                 'clientOptions' => [
                 'alias' =>  'email'
                    ],
                  ]); ?>
             <?= $form->field($model2, 'contact_no2')->textInput(['maxlength' => true,'onkeypress'=>'return event.charCode >= 13 && event.charCode <= 57']) ?>
            <!-- end of employee parents info -->
        </div>
            <!-- /.col -->
          </div>
          <!-- /.row -->
        </div>
        </div>
         <!-- end of employee parents info -->
<div class="box box-default">
        <div class="box-header with-border">
          <h3 class="box-title">Employee Address Info</h3>
          <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
            <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
          </div>
        </div>
        <!-- /.box-header -->
        <div class="box-body">
          <div class="row">
            <div class="col-md-6">
                  <?= $form->field($model, 'location2')->textArea(['maxlength' => true]) ?>
                    <?php
                        $country_array = ArrayHelper::map(\app\models\RefCountries::find()->all(), 'country_id', 'country_name');
                        echo $form->field($model, 'country_id')->widget(Select2::classname(), [
                            'data' => $country_array,
                            'options' => ['placeholder' => 'Select Country ...','class'=>'country','data-url'=>Url::to(['student/country'])],
                            'pluginOptions' => [
                                'allowClear' => true
                            ],]); ?>
                <?php 
             if(!$model->isNewRecord){
                 $district_array = ArrayHelper::map(\app\models\RefDistrict::find()->all(), 'district_id', 'District_Name');
                        echo $form->field($model, 'district_id')->widget(Select2::classname(), [
                            'data' => $district_array,
                            'options' => ['placeholder' => 'Select District ...','class'=>'district'],
                            'pluginOptions' => [
                                'allowClear' => true
                            ],
                        ]);
                
             }else{
                        echo $form->field($model, 'district_id')->widget(Select2::classname(), [
                            //'data' => $district_array,
                            'options' => ['placeholder' => 'Select District ...','class'=>'district'],
                            'pluginOptions' => [
                                'allowClear' => true
                            ],
                        ]);
             }          
                    ?>
                <?= $form->field($model, 'different_address')->radioList([1 => 'As Above',2=>'different'], ['itemOptions' => ['class' =>'radio-inline permanent_addressoother']]);?>
             </div>
            <!-- /.col -->
            <div class="col-md-6">
                <?php 
                         if(!$model->isNewRecord){
                            $province_array = ArrayHelper::map(\app\models\RefProvince::find()->all(), 'province_id', 'province_name');
                        echo $form->field($model, 'province_id')->widget(Select2::classname(), [
                            'data' => $province_array,
                            'options' => ['placeholder' => 'Select Province ...','class'=>'state','data-url'=>Url::to(['student/province'])],
                            'pluginOptions' => [
                                'allowClear' => true
                            ],
                        ]);
                    }else{
                        echo $form->field($model, 'province_id')->widget(Select2::classname(), [
                            'options' => ['placeholder' => 'Select Province ...','class'=>'state','data-url'=>Url::to(['student/province'])],
                            'pluginOptions' => [
                                'allowClear' => true
                            ],
                        ]);
                         }
                    ?>
                <?php 
    
                        if(!$model->isNewRecord){
                            $city_array = ArrayHelper::map(\app\models\RefCities::find()->all(), 'city_id', 'city_name');
                        echo $form->field($model, 'city_id')->widget(Select2::classname(), [
                            'data' => $city_array,
                            'options' => ['placeholder' => 'Select City ...','class'=>'city',Url::to(['student/district'])],
                            'pluginOptions' => [
                                'allowClear' => true
                            ],
                        ]);
                        }else{
                        echo $form->field($model, 'city_id')->widget(Select2::classname(), [
                            'options' => ['placeholder' => 'Select City ...','class'=>'city',Url::to(['student/district'])],
                            'pluginOptions' => [
                                'allowClear' => true
                            ],
                        ]);
                        }
                    ?>  
             </div>
            <!-- /.col -->
          </div>
          <!-- /.row -->
                <!--=========================== address 2===================!-->
        <div class="addressother" style="display:none">   <!-- style="display:none" -->
        <div class="row">
            <div class="col-md-6">
               <?= $form->field($model, 'location1')->textArea(['maxlength' => true,'class'=>' form-control']) ?>
                <?php
                if(!$model->isNewRecord){

                $country = ArrayHelper::map(RefCountries::find()->all(), 'country_id', 'country_name');
                echo $form->field($model, 'fk_ref_country_id2')->widget(Select2::classname(), [
                    'data' => $country,
                    'options' => ['class'=>'country2','data-url'=>Url::to(['student/country']) ],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ]);
                }else{
                    $country = ArrayHelper::map(RefCountries::find()->all(), 'country_id', 'country_name');
                echo $form->field($model, 'fk_ref_country_id2')->widget(Select2::classname(), [
                    'data' => $country,
                    'options' => ['placeholder' => 'Select Country ...','class'=>'country2','data-url'=>Url::to(['student/country']) ],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ]);
                }
                ?>
                <?php
                if(!$model->isNewRecord){
                    $district_array = ArrayHelper::map(\app\models\RefDistrict::find()->all(), 'district_id', 'District_Name');
                    echo $form->field($model, 'fk_ref_district_id2')->widget(Select2::classname(), [
                        'data' => $district_array,
                        'options' => ['class'=>'district2'],
                        'pluginOptions' => [
                            'allowClear' => true
                        ],
                    ]);
                }else{
                    echo $form->field($model, 'fk_ref_district_id2')->widget(Select2::classname(), [
                        'options' => ['placeholder' => 'Select District ...','class'=>'district2'],
                        'pluginOptions' => [
                            'allowClear' => true
                        ],
                    ]);
                }
                ?>
            </div>
            <div class="col-md-6">
                <?php
                if(!$model->isNewRecord){
                    $items = ArrayHelper::map(RefProvince::find()->all(), 'province_id', 'province_name');
                    echo $form->field($model, 'fk_ref_province_id2')->widget(Select2::classname(), [
                        'data' => $items,
                        'options' => ['class'=>'state2','data-url'=>Url::to(['student/province'])],
                        'pluginOptions' => [
                            'allowClear' => true
                        ],
                    ]);
                }else{
                    echo $form->field($model, 'fk_ref_province_id2')->widget(Select2::classname(), [
                        'options' => ['placeholder' => 'Select Province ...','class'=>'state2','data-url'=>Url::to(['student/province'])],
                        'pluginOptions' => [
                            'allowClear' => true
                        ],
                    ]);
                }
                ?><?php if(!$model->isNewRecord){
                    $city = ArrayHelper::map(RefCities::find()->all(), 'city_id', 'city_name');
                    echo $form->field($model, 'fk_ref_city_id2')->widget(Select2::classname(), [
                        'data' => $city,
                        'options' => ['class'=>'city2',Url::to(['student/district'])],
                        'pluginOptions' => [
                            'allowClear' => true
                        ],
                    ]);
                }else{
                    echo $form->field($model, 'fk_ref_city_id2')->widget(Select2::classname(), [

                        'options' => ['placeholder' => 'Select City ...','class'=>'city2',Url::to(['student/district'])],
                        'pluginOptions' => [
                            'allowClear' => true
                        ],
                    ]);
                }
                ?>
            
        </div>
        </div>  
                <!-- =============== end of address 2 ==========!-->
    </div>
    <div class="address2Show" style="display: none"> <input type="text" name="EmployeeInfo[fk_ref_country_id22]" class="form-control country2" value="">
        <input type="text" name="EmployeeInfo[fk_ref_province_id22]" id="thisprovince" class="form-control getprovincesval" value="">
        <input type="text" name="EmployeeInfo[fk_ref_district_id22]" class="form-control district2">
        <input type="text" name="EmployeeInfo[fk_ref_city_id22]" class="form-control city2">
    </div>
        </div>
        </div>
        <!-- /.box-body -->           
     <!-- start of salary --> 
<div class="box box-default">
        <div class="box-header with-border">
          <h3 class="box-title">Salary Info</h3>
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
          if($employeesalaryselection->isNewRecord == 1){
            $groups = ArrayHelper::map(\app\models\SalaryPayGroups::find()->where(['status'=>1,'fk_branch_id'=>yii::$app->common->getBranch()])->all(), 'id', 'title');
                    echo $form->field($employeePayroll, 'fk_group_id')->widget(Select2::classname(), [
                        'data' => $groups,
                        'options' => ['prompt'=>'Select Pay Head','required'=>'required','data-url'=>Url::to(['salary-allownces/get-stages']),'class'=>'groups payHeadAdd'],
                        'pluginOptions' => [
                            'allowClear' => true
                        ],
                     ])->label('Pay Head <span style="color:red"> *</span>');
          }else{
            $groups = ArrayHelper::map(\app\models\SalaryPayGroups::find()->where(['status'=>1,'fk_branch_id'=>yii::$app->common->getBranch()])->all(), 'id', 'title');
                    echo $form->field($employeePayroll, 'fk_group_id')->widget(Select2::classname(), [
                        'data' => $groups,
                        'options' => ['placeholder' => 'Select Pay Head ...','required'=>'required','data-url'=>Url::to(['salary-allownces/get-stages']),'class'=>'groups'],
                        'pluginOptions' => [
                            'allowClear' => true
                        ],
                     ])->label('Pay Head <span style="color:red"> *</span>');
          }?>
          <span id="payHeadError" style="color:red"></span>
          <?php 
          if($employeePayroll->isNewRecord == 1){
          $getStagePayrol= $employeePayroll->fk_pay_stages;
           $sl = ArrayHelper::map(\app\models\SalaryAllownces::find()->where(['status'=>1,'fk_branch_id'=>yii::$app->common->getBranch()])->all(), 'id', 'title');
                    echo $form->field($employeesalaryselection, 'fk_allownces_id')->widget(Select2::classname(), [
                        //'data' => $sl,
                        'options' => ['prompt'=>'Select Pay Allownce','class','gtalwnc','data-url'=>Url::to(['employee/get-allownce']),'multiple' => true],
                        'pluginOptions' => [
                            'allowClear' => true
                        ],
                    ])->label('Pay Allownces');
                     }else{
                      $getStagePayrol= $employeePayroll->fk_pay_stages;
                     $alwncarray = ArrayHelper::map(\app\models\SalaryAllownces::find()->where(['fk_stages_id'=>$getStagePayrol,'status'=>1,'fk_branch_id'=>yii::$app->common->getBranch()])->all(), 'id', 'title');
                     $getAlwnc = ArrayHelper::map(\app\models\EmployeeAllowances::find()->select('fk_allownces_id')->where(['status'=>1,'fk_emp_id'=>$_GET['id'],'status'=>1])->all(), 'fk_allownces_id', 'fk_allownces_id');
                     $employeesalaryselection->fk_allownces_id=$getAlwnc;
                      echo $form->field($employeesalaryselection, 'fk_allownces_id')->widget(Select2::classname(), [
                        'data' => $alwncarray,
                        'options' => ['prompt'=>'Select Pay Allownce','class','gtalwnc','data-url'=>Url::to(['employee/get-allownce']),'multiple' => true],
                        'pluginOptions' => [
                            'allowClear' => true
                        ],
                       ])->label('Pay Allownces');
                     }
                    ?>

    <div class="alwnc alw_min col-sm-12">    
       </div>
         </div>
            <!-- /.col -->
            <div class="col-sm-6"> 
         <input type="hidden" name="EmployeePayroll[total_allownce]" id="getTotalAlwnx">
         <input type="hidden" name="EmployeePayroll[total_deductions]" id="getTotalDedcx">
            <input type="hidden" value="" name="EmployeePayroll[total_amount]" id="payrollTotalAmount">
         <?php
            if($employeePayroll->isNewRecord == 1){
          $stage = ArrayHelper::map(\app\models\SalaryPayStages::find()->where(['status'=>1,'fk_branch_id'=>yii::$app->common->getBranch()])->where(['fk_branch_id'=>yii::$app->common->getBranch()])->all(), 'id', 'title');
                    echo $form->field($employeePayroll, 'fk_pay_stages')->widget(Select2::classname(), [
                        //'data' => $groups,
                        'options' => ['prompt' => 'Select Pay Head Type ...','required'=>'required','class'=>'getstage getstageamnt paytypeAdd','data-url'=>Url::to(['employee/get-stage-detail'])],
                        'pluginOptions' => [
                            'allowClear' => true
                        ],
         ])->label('Pay Head Type <span style="color:red"> *</span>');?>
       <span id="payTypeError" style="color:red"></span>
        <?php $deduction = ArrayHelper::map(\app\models\SalaryDeductionType::find()->where(['fk_branch_id'=>yii::$app->common->getBranch()])->all(), 'id', 'title');
                    echo $form->field($employeesalarydeductiondetail, 'fk_deduction_id')->widget(Select2::classname(), [
                       // 'data' => $deduction,
                        'options' => ['prompt'=>'Select Pay Deduction','class'=>'deduct','data-url'=>Url::to(['employee/get-allownce']),'multiple' => true],
                        'pluginOptions' => [
                            'allowClear' => true
                        ],
                    ])->label('Pay Deductions');
    
                }else{
                   // $employeesalaryselection->fk_pay_stages="";
                    $stage = ArrayHelper::map(\app\models\SalaryPayStages::find()->where(['status'=>1,'fk_branch_id'=>yii::$app->common->getBranch()])->all(), 'id', 'title');
                    echo $form->field($employeePayroll, 'fk_pay_stages')->widget(Select2::classname(), [
                        'data' => $stage,
                        'options' => ['placeholder' => 'Select Pay Head Type ...','required'=>'required','class'=>'getstage getstageamnt','data-url'=>Url::to(['employee/get-stage-detail'])],
                        'pluginOptions' => [
                            'allowClear' => true
                        ],
                 ])->label('Pay Head Type<span style="color:red"> *</span>');;
                    $deductions = ArrayHelper::map(\app\models\EmployeeDeductions::find()->select('fk_deduction_id')->where(['fk_emp_id'=>$_GET['id'],'status'=>1,'fk_branch_id'=>yii::$app->common->getBranch()])->asArray()->all(), 'fk_deduction_id', 'fk_deduction_id');
                    $deductionss = ArrayHelper::map(\app\models\SalaryDeductionType::find()->where(['fk_branch_id'=>yii::$app->common->getBranch()])->all(), 'id', 'title');
                    $employeesalarydeductiondetail->fk_deduction_id=$deductions;
                   $getStagePayroll= $employeePayroll->fk_pay_stages;
                    $deduction = ArrayHelper::map(\app\models\SalaryDeductionType::find()->where(['fk_stages_id'=>$getStagePayroll])->all(), 'id', 'title');
                    echo $form->field($employeesalarydeductiondetail, 'fk_deduction_id')->widget(Select2::classname(), [
                        'data' => $deduction,
                        'options' => ['prompt'=>'Select Pay Deduction','class'=>'deduct','data-url'=>Url::to(['employee/get-allownce']),'multiple' => true],
                        'pluginOptions' => [
                            'allowClear' => true
                        ],
                    ])->label('Pay Deductions');
                    ?>
                <?php } ?><div style="display: none;" class="tlt">
                    <label>Net amount</label>
                    <input type="text" name="" readonly="readonly" value="" class="ttl form-control">
                   </div>
                   <div class="dedctns col-sm-6"> 
                   </div> 
         </div> </div>  </div>  </div>     
         <div class="row">
         <div class="row" id="getBasicSalary">
         <div class="col-sm-12">
             <table class="table table-striped calculateNet" style="display: none">
                   <tr>
                       <td id="getTotalNet">Basic Salary</td>
                       <td id="getnetamount"><input type="text" name="EmployeePayroll[basic_salary]" value="" id="getBscSalry" readonly="readonly" style="border: none"></td>
                   </tr>
                   <tr>
                   </tr>
              </table>
          </div>
         <div class="col-sm-12"></div>
         </div>  
         <div class="form-group col-sm-12">
         <?= Html::a('Cancel',['employee/index'], ['class' => 'btn btn-danger pull-left']) ?>
         <?= Html::submitButton($model->isNewRecord ? 'Add' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-primary pull-right employeeFormSubmit' : 'btn green-btn pull-right', 'value'=>'create', 'name'=>'submitCreate','style'=>'margin-left:3px']) ?>
         <?= ($model->isNewRecord)?Html::submitButton('Create & Add Education', ['class' => $model->isNewRecord ? 'btn btn-success green-btn pull-right employeeFormSubmit' : 'btn green-btn pull-right', 'value'=>'create_continue', 'name'=>'submit']):'' ?>
    </div> 
        <?php ActiveForm::end(); ?>
    </div> 
    <input type="hidden" id="uss" data-url=<?php echo Url::to(['employee/get-allownce']); ?>>
    <?php
    $id=yii::$app->request->get('id');
if(!empty(yii::$app->request->get('id'))){
$script= <<< JS
$(document).ready(function() {
var url=$('#uss').data('url');
var stageid= $('#employeepayroll-fk_pay_stages').val();
var alwncid=$('#employeeallowances-fk_allownces_id').val();
var deductid=$('.deduct').val();
//alert(stageid);
allownce(url,stageid,alwncid,deductid);
});
/*$(document).ready(function() {
var id=$('.deduct').val();
  var url=$('.deduct').data('url');
  var gettotalAlwnc=$('#getalownceamount').val();
  //alert(gettotalAlwnc);
  getDeduction(url,id,gettotalAlwnc);
});*/

JS;
$this->registerJs($script);
}

?>
<?php
if($model->isNewRecord !=1 && $model->different_address){
  
    //echo $model->different_address;
$script= <<< JS

$(document).ready(function() {
$('.addressother').show();
});
JS;
$this->registerJs($script);
}
?>
