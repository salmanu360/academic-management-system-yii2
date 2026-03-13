<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\RefCountries;
use app\models\RefProvince;
use app\models\Profession;
use app\models\RefSession;
use app\models\RefGroup;
use app\models\RefShift;
use app\models\RefClass;      
use app\models\RefSection;
use app\models\RefCities;
use app\models\Zone;
use app\models\Stop;
use app\models\RefGardianType;
use app\models\HostelFloor;
use app\models\HostelRoom;
use app\models\HostelBed;
use app\models\RefDegreeType;
use app\models\RefInstituteType;
use app\models\Hostel;
use kartik\date\DatePicker;
use kartik\select2\Select2;
use yii\helpers\Url;
use kartik\depdrop\DepDrop;
$this->registerCssFile(Yii::getAlias('@web')."/css/site.css");
$this->registerCssFile(Yii::getAlias('@web')."/css/wizard/main.css");
$this->registerJsFile(Yii::getAlias('@web').'/js/jquery.steps.js',['depends' => [yii\web\JqueryAsset::className()]]);
$this->registerJsFile(Yii::getAlias('@web').'/js/custom-step.js',['depends' => [yii\web\JqueryAsset::className()]]);
$this->registerJsFile(Yii::getAlias('@web').'/js/previewstudent.js',['depends' => [yii\web\JqueryAsset::className()]]);
$this->title = 'Student Admission';

?>
    <?php $form = ActiveForm::begin(['id'=>'admission-form','enableClientValidation'=>false]); ?>
    <?php echo $form->errorSummary($model); ?>
   <div class="box box-default">
        <div class="box-header with-border">
          <h3 class="box-title">OFFICIAL DETAILS</h3>

          <div class="box-tools pull-right">
          <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
            
          </div>
        </div>
        <!-- /.box-header -->
        <div class="box-body">
         <div class="col-md-6">
                    <p>
                    <?php
                $session = ArrayHelper::map(RefSession::find()->where(['fk_branch_id'=>Yii::$app->common->getBranch()])->all(), 'session_id', 'title');
                echo $form->field($model, 'session_id')->widget(Select2::classname(), [
                    'data' => $session,
                    'options' => ['placeholder' => 'Select Session ...','data-url'=>Url::to(['student/get-class']),'class'=>'sessionAdmission'],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ])->label(Yii::t('app','Session').'<span style="color:#a94442;">*</span>');
                ?> 
                <label for="" class="errorSession" style="color: red"></label>

                   <?php 
                    $class_array = ArrayHelper::map(\app\models\RefClass::find()->where(['fk_branch_id'=>Yii::$app->common->getBranch(),'status'=>'active'])->all(), 'class_id', 'title');?>
                        <?php 
                        if($model->isNewRecord !=1){
                        echo $form->field($model, 'class_id')->widget(Select2::classname(), [
                            'data' => $class_array,
                            'options' => ['id'=>'class-id','prompt' => 'Select'.' '. Yii::t('app','Class'),'class'=>'form-control classprev'],
                            'pluginOptions' => [
                                'allowClear' => true
                            ],
                        ])->label(Yii::t('app','Class').'<span style="color:#a94442;">*</span>');
                        }else{
                        echo $form->field($model, 'class_id')->widget(Select2::classname(), [
                           // 'data' => $class_array,
                            'options' => ['id'=>'class-id','prompt' => 'Select'.' '. Yii::t('app','Class'),'class'=>'form-control classprev'],
                            'pluginOptions' => [
                                'allowClear' => true
                            ],
                        ])->label(Yii::t('app','Class').'<span style="color:#a94442;">*</span>');
                      }

                        ?>
                        <label for="" class="errorClass" style="color: red"></label>
                        <?php
                        if($model->isNewRecord!='1'){
                        $section = ArrayHelper::map(RefSection::find()->where(['fk_branch_id'=>Yii::$app->common->getBranch(),'status'=>'active','class_id'=>$model->class_id])->all(), 'section_id', 'title');
                        echo $form->field($model, 'section_id')->widget(DepDrop::classname(), [
                                'data' => $section,
                                'options' => ['id'=>'section-id','class'=>'form-control sectionPrev'],
                                'pluginOptions'=>[
                                    'depends'=>[
                                        'group-id','class-id'
                                    ],
                                    'prompt' => 'Select section',
                                    'url' => Url::to(['/site/get-section'])
                                ]
                            ])->label(Yii::t('app','Section').'<span style="color:#a94442;">*</span>');
                        }
                        else{
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
                        }
                        ?>
                        <?= $form->field($model, 'avail_sibling_discount')->radioList([1 => 'Avail Sibling Discount', 0 => 'Not Avail Sibling Discount'], ['itemOptions' => ['class' =>'radio-inline inputes','id'=>'gendderStudent']])->label('Select Sibling Discount <span style="color:red">*</span>')?>
                        <input type="hidden" value="<?= Url::to(['student/get-fee'])  ?>" id="getClassFee">
                        <label for="" class="errorSection" style="color: red"></label>  
                        
                    </p>
                </div>
                <div class="col-md-6">
                <?= $form->field($model2, 'cnic')->widget(\yii\widgets\MaskedInput::className(), [
                            'mask' => ['99999-9999999-9', '9999-999-99999'],'options'=>['class'=>'input form-control','id'=>'input1','data-url'=>Url::to(['student/parent-cnic']),'data-branch'=>yii::$app->common->getBranch()]
                        ])->label(Yii::t('app','Parent Cnic').' <span style="color:#a94442;">*</span>')?>
                        <label for="" class="errorCnic" style="color: red"></label> 
                    <p> 
                                    
                   <?php
                if($model->isNewRecord!='1'){
                $group = ArrayHelper::map(RefGroup::find()->where(['fk_branch_id'=>Yii::$app->common->getBranch(),'status'=>'active'])->all(), 'group_id', 'title');
  
                echo $form->field($model, 'group_id')->widget(DepDrop::classname(), [
                    'options' => ['prompt'=>'Select Group','id'=>'group-id','class'=>'form-control groupPrev'],
                    'data' => $group,
                    'pluginOptions'=>[
                        'depends'=>['class-id'],
                        'prompt' => 'Select Group...',
                        'url' => Url::to(['/site/get-group'])
                    ]
                ]);
                }else{
                echo $form->field($model, 'group_id')->widget(DepDrop::classname(), [
                    'options' => ['id'=>'group-id','class'=>'form-control groupPrev'],
                    'pluginOptions'=>[
                        'depends'=>['class-id'],
                        'prompt' => 'Select Group...',
                        'url' => Url::to(['/site/get-group'])
                    ]
                ]);
                 }
                ?>

                <?php
                $shift = ArrayHelper::map(RefShift::find()->where(['fk_branch_id'=>Yii::$app->common->getBranch()])->all(), 'shift_id', 'title');
                    echo $form->field($model, 'shift_id')->widget(Select2::classname(), [
                        'data' => $shift,
                        'options' => ['class' => 'form-control shiftPrev','id'=>'shift-id'],
                        'pluginOptions' => [
                            'allowClear' => true
                        ],
                    ]);
                 ?>   
                
                 <div id="displaychild" style="display: none; font-weight: bold">Child Is:</div>
                 <div class="cnicDisplay" style="font-weight: bold"></div>
            </p>
                </div>
         </div>
         </div>
              <!-- ////////////////////////////// -->
      
      <!-- /.row -->
         <!-- start of personnel info -->
         <div class="box box-default">
        <div class="box-header with-border">
          <h3 class="box-title">Personnel Details</h3>
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

                    if(Yii::$app->common->getBranchSettings()->student_reg_type == 'auto'){

                     /*$countLastId=$branch_std_counter->username;
                     $jsonEncode= json_encode($countLastId);
                     if(count($jsonEncode[15]) > 0){
                     $counts= $jsonEncode[13].''.$jsonEncode[14].''.$jsonEncode[15];
                     }else{
                     $counts= $jsonEncode[13].''.$jsonEncode[14];
                     }*/
                        ?>
                        <?=$form->field($userModel, 'username')->textInput(['readonly'=>true,'maxlength' => true,'value'=>Yii::$app->common->getBranchDetail()->name.'-'.date("Y").'-'.($branch_std_counter+1),'id'=>'registeration','class'=>'form-control input form-control','id'=>'registeration','data-url'=>Url::to('validate-usrname')])->label(Yii::t('app','Register Number').'<span style="color:#a94442;"> *</span>')?>
                        <?php
                    } else{
                        ?>
                        <?=$form->field($userModel, 'username')->textInput(['maxlength' => true,'id'=>'registeration','class'=>'form-control input form-control','id'=>'registeration','data-url'=>Url::to('validate-usrname')])->label(Yii::t('app','Register Number').'<span style="color:#a94442;"> *</span>')?>
                        <?php
                    }
                    ?>
                     <label for="" class="errorRegisteration" style="color: red"></label>
                   <?= $form->field($userModel, 'middle_name')->textInput(['maxlength' => true,'style' => 'text-transform: uppercase','class'=>'input form-control','id'=>'middlenamePerssonel'])->label('Middle Name <small>(Optional)</small>') ?> 
                   <?= $form->field($model, 'contact_no')->textInput(['maxlength' => true,'onkeypress'=>'return event.charCode >= 13 && event.charCode <= 57'])->label('Contact No. <small>(Optional)</small>') ?>
                </div>
                <div class="col-md-6">
                <?= $form->field($userModel, 'first_name')->textInput(['maxlength' => true,'style' => 'text-transform: uppercase','class'=>'input form-control','id'=>'firstnamePersonnel'])->label('First Name <span style="color:red">*</span>') ?>
                        <label for="" class="errorFirstname" style="color: red"></label>
                <?= $form->field($userModel, 'last_name')->textInput(['maxlength' => true,'style' => 'text-transform: uppercase','class'=>'input form-control','id'=>'lastnamepersonel'])->label(Yii::t('app','Last name').'<span style="color:#a94442;"> *</span>') ?>
                <?php $religion_array = ArrayHelper::map(\app\models\RefReligion::find()->all(), 'religion_type_id', 'Title');
                    echo $form->field($model, 'religion_id')->widget(Select2::classname(), [
                        'data' => $religion_array,
                        'options' => ['class'=>'religionPrev form-control','id'=>'religionStudent'],
                        'pluginOptions' => [
                            'allowClear' => true
                        ],
                    ])->label('Religion <span style="color:red">*</span>');?>
                    
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                  <?php
                        echo $form->field($model, 'dob')->widget(DatePicker::classname(), [
                                'options' => [
                                    'value' => (!$model->isNewRecord)?$model->dob:date('Y-m-d'),
                                    'class'=>'inputes','id'=>'dobdate'
                                ],
                                'pluginOptions' => [
                                    'autoclose'=>true,
                                    'format' => 'yyyy-mm-dd',
                                    'todayHighlight' => true,
                                    'endDate' => '-2y',
                                    //'startDate' => '-2y',
                                ]
                        ])->label('Date of Birth <span style="color:red">*</span>');

                    ?>  
                        <label for="" class="errorDOB" style="color: red"></label>

                </div>
                <div class="col-md-6">    
                  <?php
                    echo $form->field($model, 'registration_date')->widget(DatePicker::classname(), [
                        'options' => [
                            'value' => (!$model->isNewRecord)?$model->registration_date:date('Y-m-d'),
                            'class'=>'inputes','id'=>'registration_date'
                        ],
                        'pluginOptions' => [
                            'autoclose'=>true,
                            'format' => 'yyyy-mm-dd',
                            'todayHighlight' => true,
                        ]
                    ])->label('Admission Date <span style="color:red">*</span>');
                    ?>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <?= $form->field($model, 'location1')->textArea(['maxlength' => true,'class'=>'form-control input','id'=>'location1'])->label('Address <span style="color:red">*</span>') ?>
                </div>
                <div class="col-md-6">
                    <?php
                    $country = ArrayHelper::map(RefCountries::find()->all(), 'country_id', 'country_name');
                    echo $form->field($model, 'country_id')->widget(Select2::classname(), [
                        'data' => $country,
                        'options' => ['prompt'=>'Select Country','class'=>'country countryPrev countryError','id','countryStudent','data-url'=>Url::to(['student/country']) ],
                        'pluginOptions' => [
                            'allowClear' => true
                        ],
                    ])->label('Country <span style="color:red">*</span>');?>
                <label class="errorCountry" style="color:red"></label>
                </div>
                <!-- <div class="col-md-6">
                    <?//= $form->field($model, 'withdrawl_no')->textInput(['class'=>'input form-control','id'=>'withdrawlno']) ?>
                </div> -->
            </div>
            <div class="row">
                <div class="col-md-6">
                    <?php
    
                    if(!$model->isNewRecord){
                        $items = ArrayHelper::map(RefProvince::find()->all(), 'province_id', 'province_name');
                        echo $form->field($model, 'province_id')->widget(Select2::classname(), [
                            'data' => $items,
                            'options' => ['placeholder' => 'Select Province ...','class'=>'state','data-url'=>Url::to(['student/province'])],
                            'pluginOptions' => [
                                'allowClear' => true
                            ],
                        ])->label('Province <span style="color:red">*</span>');
                    }else{
                        echo $form->field($model, 'province_id')->widget(Select2::classname(), [
                            //'data' => $items,
                            'options' => ['placeholder' => 'Select Province ...','class'=>'state provincePrev provinceError','id'=>'provinceStudent','data-url'=>Url::to(['student/province'])],
                            'pluginOptions' => [
                                'allowClear' => true
                            ],
                        ])->label('Province <span style="color:red">*</span>');
                    }
                    ?>
                <label class="errorprovince" style="color:red"></label>
                </div>
                <div class="col-md-6">
                    <?php
                    if(!$model->isNewRecord){
                        $district_array = ArrayHelper::map(\app\models\RefDistrict::find()->all(), 'district_id', 'District_Name');
                        echo $form->field($model, 'district_id')->widget(Select2::classname(), [
                            'data' => $district_array,
                            'options' => ['placeholder' => 'Select District ...','class'=>'district'],
                            'pluginOptions' => [
                                'allowClear' => true
                            ],
                        ])->label('District <span style="color:red">*</span>');
                    }else{
                        // $district_array = ArrayHelper::map(\app\models\RefDistrict::find()->all(), 'district_id', 'District_Name');
                        echo $form->field($model, 'district_id')->widget(Select2::classname(), [
                            //'data' => $district_array,
                            'options' => ['placeholder' => 'Select District ...','class'=>'district districtPrev DistrictError','id'=>'districtStudent'],
                            'pluginOptions' => [
                                'allowClear' => true
                            ],
                        ])->label('District <span style="color:red">*</span>');
                    }
                    ?>
                <label class="errorDistrict" style="color:red"></label>

                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <?php
                    if(!$model->isNewRecord){
                        $city = ArrayHelper::map(RefCities::find()->all(), 'city_id', 'city_name');
                        echo $form->field($model, 'city_id')->widget(Select2::classname(), [
                            'data' => $city,
                            'options' => ['placeholder' => 'Select City ...','class'=>'city',Url::to(['student/district'])],
                            'pluginOptions' => [
                                'allowClear' => true
                            ],
                        ])->label('City <span style="color:red">*</span>');
                    }else{
                        echo $form->field($model, 'city_id')->widget(Select2::classname(), [
    
                            'options' => ['placeholder' => 'Select City ...','class'=>'city cityPrev CityError','id'=>'cityStudent',Url::to(['student/district'])],
                            'pluginOptions' => [
                                'allowClear' => true
                            ],
                        ])->label('City <span style="color:red">*</span>');
                    }
                    ?>
                <label class="errorCity" style="color:red"></label>
                </div>
                <div class="col-md-6">
                    <?php
                    echo $form->field($userModel, 'Image')->fileInput()->label('Upload Photo');
                    if(!$userModel->isNewRecord){
                        $src=Yii::$app->request->baseUrl.'/uploads/'.$userModel->Image;
                        echo Html::img( $src, $options = ['width'=>60,'height'=>'60','alt'=>'No Image Uploaded']);
                    }
                    ?>
                </div> 
            </div>
            <div class="row">
                <div class="col-md-6">
                
                    <?= $form->field($model, 'gender_type')->radioList([1 => 'Male', 0 => 'Female'], ['itemOptions' => ['class' =>'radio-inline inputes','id'=>'gendderStudent']])->label('Gender <span style="color:red">*</span>')?>
                </div>
                
                <div class="col-md-6">
                    <?= $form->field($model, 'roll_no')->textInput()->label('Class Roll No (Optional)') ?>
                    <div class="address2Show"  style="display: none">
                        <div class="col-md-6">
                            <?= $form->field($model, 'location2')->textArea(['maxlength' => true,'class'=>'permanent form-control']) ?>
                        </div>
                        <div class="col-md-6">
                            <label><?=Yii::t('app','Country')?></label>
                            <input type="text" name="StudentInfo[fk_ref_country_id21]" class="form-control country21" value="" >
                        </div>
                        <div class="col-md-6">
                            <label><?=Yii::t('app','Province')?></label>
                            <input type="text" name="StudentInfo[fk_ref_province_id211]" id="povinces2" class="form-control provinces21" value="">
                        </div>
                        <div class="col-md-6">
                            <label><?=Yii::t('app','District')?></label>
                            <input type="text" name="StudentInfo[fk_ref_district_id23]" class="form-control district21">
                        </div>
                        <div class="col-md-6">
                            <label><?=Yii::t('app','City')?></label>
                            <input type="text" name="StudentInfo[fk_ref_city_id24]" class="form-control city21">
                        </div>
                        <!-- //get value -->
                        <input type="hidden" name="StudentInfo[fk_ref_country_id2]" class="form-control country2" value="">
                        <input type="hidden" name="StudentInfo[fk_ref_province_id3]" id="thisprovince" class="form-control getprovincesval" value="">
                        <input type="hidden" name="StudentInfo[fk_ref_district_id2]" class="form-control district2">
                        <input type="hidden" name="StudentInfo[fk_ref_city_id2]" class="form-control city2">
                    </div>
                </div>
                <div class="col-md-6">
                     
                </div>
            </div>
            <div class="row">
                <!--=========================== address 2===================!-->
                <div class="address2" style="display:none">
                    <div class="col-md-6">
                        <?= $form->field($model, 'location2')->textArea(['maxlength' => true,'class'=>' form-control']) ?>
                    </div>
                    <div class="col-md-6">
                        <?php
                        $country = ArrayHelper::map(RefCountries::find()->all(), 'country_id', 'country_name');
                        echo $form->field($model, 'fk_ref_country_id2')->widget(Select2::classname(), [
                            'data' => $country,
                            'options' => ['placeholder' => 'Select a Country ...','class'=>'country2','data-url'=>Url::to(['student/country']) ],
                            'pluginOptions' => [
                                'allowClear' => true
                            ],
                        ]);?>
                    </div>
                    <div class="col-md-6">
                        <?php
    
                        if(!$model->isNewRecord){
                            $items = ArrayHelper::map(RefProvince::find()->all(), 'province_id', 'province_name');
                            echo $form->field($model, 'fk_ref_province_id2')->widget(Select2::classname(), [
                                'data' => $items,
                                'options' => ['placeholder' => 'Select Province ...','class'=>'state2','data-url'=>Url::to(['student/province'])],
                                'pluginOptions' => [
                                    'allowClear' => true
                                ],
                            ]);
                        }else{
                            //$items = ArrayHelper::map(RefProvince::find()->all(), 'province_id', 'province_name');
                            echo $form->field($model, 'fk_ref_province_id2')->widget(Select2::classname(), [
                                //'data' => $items,
                                'options' => ['placeholder' => 'Select Province ...','class'=>'state2','data-url'=>Url::to(['student/province'])],
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
                            $district_array = ArrayHelper::map(\app\models\RefDistrict::find()->all(), 'district_id', 'District_Name');
                            echo $form->field($model, 'fk_ref_district_id2')->widget(Select2::classname(), [
                                'data' => $district_array,
                                'options' => ['placeholder' => 'Select District ...','class'=>'district2'],
                                'pluginOptions' => [
                                    'allowClear' => true
                                ],
                            ]);
                        }else{
                            // $district_array = ArrayHelper::map(\app\models\RefDistrict::find()->all(), 'district_id', 'District_Name');
                            echo $form->field($model, 'fk_ref_district_id2')->widget(Select2::classname(), [
                                //'data' => $district_array,
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
                            $city = ArrayHelper::map(RefCities::find()->all(), 'city_id', 'city_name');
                            echo $form->field($model, 'fk_ref_city_id2')->widget(Select2::classname(), [
                                'data' => $city,
                                'options' => ['placeholder' => 'Select City ...','class'=>'city2',Url::to(['student/district'])],
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
        </div>
        </div>
         <!-- end of personnel info -->
         <!-- education info -->
         
         <!-- end of education info -->
         <!-- start of parents info -->
         <!-- start of parents info -->
          <div class="box box-default">
        <div class="box-header with-border">
          <h3 class="box-title">Parents Details</h3>
          <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
            <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
          </div>
        </div>
        <!-- /.box-header -->
        <div class="box-body">
        <div class="row">
        <div class="col-md-6">
        <p>           
                    <?= $form->field($model, 'parent_status')->radioList([1 => 'Alive', 0 => 'Dead'], ['itemOptions' => ['class' =>'radio-inline parent_status' ]]) ?>
                </p>
        </div>
        </div>
        <!-- start of guardian details -->
        <div class="deads" style="display: none">
         <div class="box box-default">
        <div class="box-header with-border">
          <h3 class="box-title">Guardian Details</h3>

          <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
            <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
          </div>
        </div>
        <!-- /.box-header -->
        <div class="box-body">
       
                    <div class="row">
                        <div class="col-md-6">
                            <?= $form->field($model2, 'guardian_name')->textInput() ?>
                        </div>
                        <div class="col-md-6">
                            <?= $form->field($model2, 'relation')->textInput(['maxlength' => true]) ?>
                        </div>
                        <div class="col-md-6">
                            <?=  $form->field($model2, 'guardian_cnic')->widget(\yii\widgets\MaskedInput::className(), [
                                'mask' => ['99999-9999999-9', '9999-999-99999']
                            ]) ?>
                        </div>
                        <div class="col-md-6">
                            <?= $form->field($model2, 'guardian_contact_no')->textInput(['type' => 'number']) ?>

                        </div>
                    </div>
                </div>
        
        </div>
        </div>
         <!-- end of guardian details -->
        <div class="row">
                    <div class="col-md-6">
                        <fieldset>
                            <legend>Father Info</legend>
                            <?= Html::label(Yii::t('app','Name'))?>
                            <span style="color:red">*</span>
                            <?= $form->field($model2, 'first_name')->textInput(['maxlength' => true,'style' => 'text-transform: uppercase','class'=>'input form-control','id'=>'fatherName'])->label(false) ?>
                        <label for="" class="errorFathername" style="color: red"></label>

                            <?php
                            $fatherProfession= ArrayHelper::map(\app\models\Profession::find()->all(),'id','title');
                            echo $form->field($model2, 'profession')->widget(Select2::classname(), [
                                'data' => $fatherProfession,
                                'options' => ['placeholder' => 'Select Profession ...','class'=>'proffessionPrev','id'=>'fatherProfession'],
                                'pluginOptions' => [
                                    'allowClear' => true
                                ],
                            ]);
                            ?>
                            <label for="" class="errorfatherProfession" style="color: red"></label>
                            <?= $form->field($model2, 'designation')->textInput(['class'=>'input form-control','id'=>'fatherDesignation'])->label('Designation <small>(Optional)</small>') ?>
    
                            <?= $form->field($model2, 'organisation')->textInput(['class'=>'input form-control','id'=>'fatherOrg'])->label('Organisation <small>(Optional)</small>') ?>
                        </fieldset>
                    </div>
                    <div class="col-md-6">
                        <legend>Mother Info</legend>
                        <?php echo Html::label(Yii::t('app','Name')) ?><span>(Optional)</span>
                        <?= $form->field($model2, 'mother_name')->textInput(['maxlength' => true,'style' => 'text-transform: uppercase','class'=>'input form-control','id'=>'motherName'])->label(false) ?>
    
                        <?php $getProfession= ArrayHelper::map(\app\models\Profession::find()->all(),'id','title');
                        echo $form->field($model2, 'mother_profession')->widget(Select2::classname(), [
                            'data' => $getProfession,
                            'options' => ['placeholder' => 'Select Profession ...','class'=>'motherProf','id'=>'motherProfession'],
                            'pluginOptions' => [
                                'allowClear' => true
                            ],
                        ])->label('Mother Profession <small>(Optional)</small>');
                        ?>
                        <?= $form->field($model2, 'mother_designation')->textInput(['class'=>'input form-control','id'=>'motherDesignation'])->label('Mother Designation <small>(Optional)</small>') ?>
    
                        <?= $form->field($model2, 'mother_organization')->textInput(['class'=>'input form-control','id'=>'motherOrg'])->label('Mother Organisation <small>(Optional)</small>') ?>
    
                        <?= $form->field($model2, 'gender_type')->radioList([1 => 'Mother', 2 => 'Father',3=>'Both'], ['itemOptions' => ['class' =>'radio-inline inputes','id'=>'Verifiedby']])->label('Verified By')?>
    
                        </p>
                    </div>
                </div>


        </div>
      </div>
         <!-- end of parents info -->
         
         <!-- start of parents address -->
         <div class="box box-default">
        <div class="box-header with-border">
          <h3 class="box-title">Parents address Details</h3>
          <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
            <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
          </div>
        </div>
        <!-- /.box-header -->
        <div class="box-body">
        <div class="row">
                <div class="col-md-6">
                    <fieldset>
                    <legend>
                            Father Details
                        </legend>
                    <?= $form->field($model2, 'contact_no')->textInput(['maxlength' => true,'class'=>'input form-control','id'=>'fatherContact','onkeypress'=>'return event.charCode >= 13 && event.charCode <= 57','placeholder'=>923001234567])->label(Yii::t('app','Contact Number').'<span style="color:#a94442;"> *</span>') ?>
                        <label for="" class="errorFatherContact" style="color: red"></label>


                     <?= $form->field($model2, 'contact_no2')->textInput(['maxlength' => true,'class'=>'input form-control','id'=>'fatherContact','onkeypress'=>'return event.charCode >= 13 && event.charCode <= 57'])->label('Emergency Contact No')->label('Emergency Contact No <small>(Optional)</small>') ?>
                        
                         <?= $form->field($model2, 'office_no')->textInput(['maxlength' => true,'class'=>'input form-control','id'=>'fatherOffice','onkeypress'=>'return event.charCode >= 13 && event.charCode <= 57'])->label('Office No <small>(Optional)</small>') ?>

                        <?= $form->field($model2, 'email')->textInput(['class'=>'input form-control','id'=>'fatherEmail'])->label('Email <small>(Optional)</small>') ?>
                    </fieldset>
                </div>
                <div class="col-md-6">
                    <fieldset>
                        <legend>
                            Mother Details
                        </legend>
                        <?= $form->field($model2, 'mother_contactno')->textInput(['maxlength' => true,'class'=>'input form-control','id'=>'motherContact','onkeypress'=>'return event.charCode >= 13 && event.charCode <= 57'])->label('Mother Contact No <small>(Optional)</small>') ?>    
                        <?= $form->field($model2, 'mother_officeno')->textInput(['maxlength' => true,'class'=>'input form-control','id'=>'motherOffice','onkeypress'=>'return event.charCode >= 13 && event.charCode <= 57'])->label('Mother Office No <small>(Optional)</small>') ?>
                        <?= $form->field($model2, 'mother_email')->textInput(['class'=>'input form-control','id'=>'motherEmail'])->label('Mother Email <small>(Optional)</small>') ?>
                    </fieldset>
                </div>
            </div>
        </div>
        </div>
        <!-- end of parents contact -->
         <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Admit Student' : 'Make Changes In Student Admission', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary','id'=>'stuadmissionform']) ?>
        <?= Html::a('Cancel',['index'],['class'=>'btn btn-warning'])?>
    </div> 
    <?php ActiveForm::end(); ?>

    <?php   
$script= <<< JS
$(document).ready(function() {

var cnic=$('#input1').val();
cnicUpdate(cnic);
});


JS;
$this->registerJs($script);

 ?>