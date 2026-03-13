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
$this->title='Official Details';
?>
<style>
	.select2-container .select2-selection--single{
		    height: 34px;
	}
</style>
<?php if (Yii::$app->session->hasFlash('success')): ?>
    <div class="alert alert-success alert-dismissable">
         <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
         <?= Yii::$app->session->getFlash('success') ?>
    </div>
<?php endif; ?>


<?php echo $this->render('step_menu') ?>
<div class="box-primary">
	<div class="box box-body">
    <?php $form = ActiveForm::begin(); ?>
		<div class="row">
			<div class="col-md-6">
        <?php $session = ArrayHelper::map(RefSession::find()->where(['fk_branch_id'=>Yii::$app->common->getBranch()])->all(), 'session_id', 'title');
                echo $form->field($model, 'session_id')->widget(Select2::classname(), [
                    'data' => $session,
                    'options' => ['placeholder' => 'Select Session ...','data-url'=>Url::to(['student/get-class']),'class'=>'sessionAdmission'],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ])->label(Yii::t('app','Session').'<span style="color:#a94442;">*</span>');
                ?>
          <?php 
                    $class_array = ArrayHelper::map(\app\models\RefClass::find()->where(['fk_branch_id'=>Yii::$app->common->getBranch(),'status'=>'active'])->all(), 'class_id', 'title');?>
                        <?php 
                        echo $form->field($model, 'class_id')->widget(Select2::classname(), [
                            'data' => $class_array,
                            'options' => ['id'=>'class-id','prompt' => 'Select'.' '. Yii::t('app','Class'),'class'=>'form-control classprev'],
                            'pluginOptions' => [
                                'allowClear' => true
                            ],
                        ])->label(Yii::t('app','Class').'<span style="color:#a94442;">*</span>');
                        ?>
                        <?php
                if($model->isNewRecord!='1'){
                $group = ArrayHelper::map(RefGroup::find()->where(['fk_branch_id'=>Yii::$app->common->getBranch(),'status'=>'active','fk_class_id'=>$model->class_id])->all(), 'group_id', 'title');
    
                echo $form->field($model, 'group_id')->widget(Select2::classname(), [
                        'data' => $group,
                        'pluginOptions' => [
                            'allowClear' => true
                        ],
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
                        if($model->isNewRecord!='1'){
                            if(!empty($model->group_id)){
                            $section = ArrayHelper::map(RefSection::find()->where(['fk_branch_id'=>Yii::$app->common->getBranch(),'status'=>'active','class_id'=>$model->class_id,'fk_group_id'=>$model->group_id
                        ])->all(), 'section_id', 'title');
                            }else{
                                $section = ArrayHelper::map(RefSection::find()->where(['fk_branch_id'=>Yii::$app->common->getBranch(),'status'=>'active','class_id'=>$model->class_id])->all(), 'section_id', 'title');
                            }   
                        
                        echo $form->field($model, 'section_id')->widget(Select2::classname(), [
                            'data' => $section,
                            'options' => ['placeholder' => 'Select section ...','class'=>'sectionPrev'],
                            'pluginOptions' => [
                                'allowClear' => true
                            ],
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
                    <?= $form->field($model, 'contact_no')->textInput(['maxlength' => true,'onkeypress'=>'return event.charCode >= 13 && event.charCode <= 57'])->label('Contact No. <small>(Optional)</small>') ?>
                  <?= $form->field($model, 'tribe')->textInput(['maxlength' => true]) ?>
                  <?= $form->field($model, 'gender_type')->radioList([1 => 'Male', 0 => 'Female'], ['itemOptions' => ['class' =>'radio-inline inputes','id'=>'gendderStudent']])->label('Gender <span style="color:red">*</span>')?>

                

                   
      </div> <!-- first col-md-6 -->
      <div class="col-md-6">
        <?php $religion_array = ArrayHelper::map(\app\models\RefReligion::find()->all(), 'religion_type_id', 'Title');
                    echo $form->field($model, 'religion_id')->widget(Select2::classname(), [
                        'data' => $religion_array,
                        'options' => ['class'=>'religionPrev form-control','id'=>'religionStudent'],
                        'pluginOptions' => [
                            'allowClear' => true
                        ],
                    ])->label('Religion <span style="color:red">*</span>');?>
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
        <?= $form->field($model, 'roll_no')->textInput()->label('Class Roll No (Optional)') ?>
        <?= $form->field($model, 'location1')->textInput(['maxlength' => true,'class'=>'form-control input','id'=>'location1'])->label('Address <span style="color:red">*</span>') ?>
        <?php
                    $country = ArrayHelper::map(RefCountries::find()->all(), 'country_id', 'country_name');
                    echo $form->field($model, 'country_id')->widget(Select2::classname(), [
                        'data' => $country,
                        'options' => ['prompt'=>'Select Country','class'=>'country countryPrev countryError','id','countryStudent','data-url'=>Url::to(['student/country']) ],
                        'pluginOptions' => [
                            'allowClear' => true
                        ],
                    ])->label('Country <span style="color:red">*</span>');?>
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
                    <?= $form->field($model, 'avail_sibling_discount')->radioList([1 => 'Yes', 0 => 'No'], ['itemOptions' => ['class' =>'radio-inline inputes']])?>
      </div>
      </div>
      <div class="form-group"> 
        <?= Html::submitButton($model->isNewRecord ? 'Save' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary','id'=>'stuadmissionform']) ?>
    </div>
  </div>
<?php ActiveForm::end(); ?> 
</div>
