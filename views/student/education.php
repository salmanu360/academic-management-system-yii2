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
$this->title='Education Details';
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
	<div class="alert alert-info">Leave empty this form if you don't want to fill</div>
	<div class="box box-body">
		<?php $form = ActiveForm::begin(); ?>
<div class="row">
	<div class="col-md-6">
		<?php $degree_array = ArrayHelper::map(\app\models\RefDegreeType::find()->all(), 'degree_type_id', 'Title');
                    echo $form->field($model, 'degree_type_id')->widget(Select2::classname(), [
                        'data' => $degree_array,
                        'options' => ['placeholder' => 'Select Certificate ...','class'=>'degreePrev','id'=>'degreeStudent'],
                        'pluginOptions' => [
                            'allowClear' => true
                        ],
                    ])->label('Previous Certificate (Optional)');
                    ?>
                    <?= $form->field($model, 'Institute_name')->textInput(['maxlength' => true,'class'=>'input form-control','id'=>"instituteStudent"])->label('Institute Name (Optional)') ?>
                    <?= $form->field($model, 'grade')->textInput(['maxlength' => true,'class'=>'input form-control','id'=>"gradeStudent"])->label('Grade (optional)') ?>
                    <?= $form->field($model, 'marks_obtained')->textInput(['class'=>'input form-control','id'=>'marksontainedStudent'])->label('Marks Obtained (optional)') ?>
                    <?= $form->field($model, 'total_marks')->textInput(['class'=>'input form-control','id'=>'marksStudent'])->label('Total Marks (Optional)') ?>

	</div>
	<div class="col-md-6">
		<?= $form->field($model, 'degree_name')->textInput(['maxlength' => true,'class'=>'input form-control','id'=>"degreenameStudent"])->label('Degree Name (Optional)') ?>
                     <?= $form->field($model, 'start_date')->widget(DatePicker::classname(), [
                        'options' => ['value' => date('Y-m-d'),'class'=>'inputes','id'=>'startdateStudent'],
                        'pluginOptions' => [
                            'autoclose'=>true,
                            'format' => 'yyyy/mm/dd',
                            'todayHighlight' => true,
                        ]
                    ])->label('Start Date (optional)');?>
                    <?= $form->field($model, 'end_date')->widget(DatePicker::classname(), [
                        'options' => ['value' =>date('Y-m-d'),'class'=>'inputes','id'=>'enddateStudent'],
                        'pluginOptions' => [
                            'autoclose'=>true,
                            'format' => 'yyyy/mm/dd',
                            'todayHighlight' => true,
                        ]
                    ])->label('End Date (optional)');?>
	</div>

</div>
<div class="form-group"> 
        <?= Html::submitButton($model->isNewRecord ? 'Save' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary','id'=>'stuadmissionform']) ?>
    </div>
<?php ActiveForm::end(); ?>
</div>
</div>
