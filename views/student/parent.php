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
$this->title='Parent Details';
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
                        <fieldset>
                            <legend>Father Info</legend>
                            <?= $form->field($model, 'cnic')->widget(\yii\widgets\MaskedInput::className(), [
                            'mask' => ['99999-9999999-9', '9999-999-99999'],'options'=>['class'=>'input form-control','id'=>'input1','data-url'=>Url::to(['student/parent-cnic']),'data-branch'=>yii::$app->common->getBranch()]
                        ])->label(Yii::t('app','Parent Cnic').' <span style="color:#a94442;">*</span>')?>
                        <div class="cnicDisplay" style="font-weight: bold"></div>
                            <?= Html::label(Yii::t('app','Name'))?>
                            <span style="color:red">*</span>
                            <?= $form->field($model, 'first_name')->textInput(['maxlength' => true,'style' => 'text-transform: uppercase','class'=>'input form-control','id'=>'fatherName'])->label(false) ?>
                            <?php
                            $fatherProfession= ArrayHelper::map(\app\models\Profession::find()->all(),'id','title');
                            echo $form->field($model, 'profession')->widget(Select2::classname(), [
                                'data' => $fatherProfession,
                                'options' => ['placeholder' => 'Select Profession ...','class'=>'proffessionPrev','id'=>'fatherProfession'],
                                'pluginOptions' => [
                                    'allowClear' => true
                                ],
                            ]);
                            ?>
                            <?= $form->field($model, 'designation')->textInput(['class'=>'input form-control','id'=>'fatherDesignation'])->label('Designation <small>(Optional)</small>') ?>
    
                            <?= $form->field($model, 'organisation')->textInput(['class'=>'input form-control','id'=>'fatherOrg'])->label('Organisation <small>(Optional)</small>') ?>
                        </fieldset>
                    
                    <?= $form->field($model, 'contact_no')->textInput(['maxlength' => true,'class'=>'input form-control','onkeypress'=>'return event.charCode >= 13 && event.charCode <= 57','placeholder'=>923001234567])->label(Yii::t('app','Contact Number').'<span style="color:#a94442;"> *</span>') ?>

                     <?= $form->field($model, 'contact_no2')->textInput(['maxlength' => true,'class'=>'input form-control','id'=>'fatherContact','onkeypress'=>'return event.charCode >= 13 && event.charCode <= 57'])->label('Emergency Contact No')->label('Emergency Contact No <small>(Optional)</small>') ?>
                        
                         <?= $form->field($model, 'office_no')->textInput(['maxlength' => true,'class'=>'input form-control','id'=>'fatherOffice','onkeypress'=>'return event.charCode >= 13 && event.charCode <= 57'])->label('Office No <small>(Optional)</small>') ?>

                        <?= $form->field($model, 'email')->textInput(['class'=>'input form-control','id'=>'fatherEmail'])->label('Email <small>(Optional)</small>') ?>
                 
                    </div>
                    <div class="col-md-6">
                        <legend>Mother Info</legend>
                        <?php echo Html::label(Yii::t('app','Name')) ?><span>(Optional)</span>
                        <?= $form->field($model, 'mother_name')->textInput(['maxlength' => true,'style' => 'text-transform: uppercase','class'=>'input form-control','id'=>'motherName'])->label(false) ?>
    
                        <?php $getProfession= ArrayHelper::map(\app\models\Profession::find()->all(),'id','title');
                        echo $form->field($model, 'mother_profession')->widget(Select2::classname(), [
                            'data' => $getProfession,
                            'options' => ['placeholder' => 'Select Profession ...','class'=>'motherProf','id'=>'motherProfession'],
                            'pluginOptions' => [
                                'allowClear' => true
                            ],
                        ])->label('Mother Profession <small>(Optional)</small>');
                        ?>
                        <?= $form->field($model, 'mother_designation')->textInput(['class'=>'input form-control','id'=>'motherDesignation'])->label('Mother Designation <small>(Optional)</small>') ?>
    
                        <?= $form->field($model, 'mother_organization')->textInput(['class'=>'input form-control','id'=>'motherOrg'])->label('Mother Organisation <small>(Optional)</small>') ?>
                        
    					<?= $form->field($model, 'mother_contactno')->textInput(['maxlength' => true,'class'=>'input form-control','id'=>'motherContact','onkeypress'=>'return event.charCode >= 13 && event.charCode <= 57'])->label('Mother Contact No <small>(Optional)</small>') ?>    
                        <?= $form->field($model, 'mother_officeno')->textInput(['maxlength' => true,'class'=>'input form-control','id'=>'motherOffice','onkeypress'=>'return event.charCode >= 13 && event.charCode <= 57'])->label('Mother Office No <small>(Optional)</small>') ?>
                        <?= $form->field($model, 'mother_email')->textInput(['class'=>'input form-control','id'=>'motherEmail'])->label('Mother Email <small>(Optional)</small>') ?>
                        <?= $form->field($model, 'gender_type')->radioList([1 => 'Father', 2 => 'Mother',3=>'Both'], ['itemOptions' => ['class' =>'radio-inline inputes','id'=>'Verifiedby']])?>
                        
                    </div>
</div>
<div class="form-group"> 
        <?= Html::submitButton($model->isNewRecord ? 'Save' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary','id'=>'stuadmissionform']) ?>
    </div>
<?php ActiveForm::end(); ?>
</div>
</div>