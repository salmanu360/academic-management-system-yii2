<?php
use yii\helpers\Url;
use yii\bootstrap\Modal;
use kartik\select2\Select2;
use yii\widgets\ActiveForm;
?>
<div class="reports_wrap ">
    <ul class="nav nav-pills">
        <li ><a data-toggle="tab" href="#Single-Examination">View Results</a></li>
        <!-- <li><a data-toggle="tab" href="#Multiple-Examination">Multiple Examination</a></li> -->
        <!-- <li><a data-toggle="tab" href="#Class-Wise-Examination">Class Wise Examination</a></li> -->
        <li><a data-toggle="tab" href="#Class-Wise-Examination">Result Sheet</a></li>
    </ul>
    <div class="tab-content">
        <div id="Single-Examination" class="tab-pane fade in">
        </div>
        <div id="Multiple-Examination" class="tab-pane fade">
        </div>
        <div id="Class-Wise-Examination" class="tab-pane fade">
        </div> 
      </div>
</div>
<!-- <div class="exportdmcs" data-url="<?//=Url::to('export-all-dmc')?>"> -->
	<!-- <a class="btn green-btn" href="javascript:void(0);" >Export & Print All DMC'S</a> -->
<!-- </div> -->
<?php
Modal::begin([
    'header'=>'<h4></h4> ',
    'id'=>'modal-type',
    'options'=>[
        'data-keyboard'=>false,
        'data-backdrop'=>"static"
    ],
    'size'=>'modal-md',
    'footer' =>'<button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button> <button type="button" class="btn btn-primary pull-right" id="search-exam-dmc" data-url="'.Url::to("std-dmc").'">Search</button>',

]);
// Normal select with ActiveForm & model
 $form = ActiveForm::begin(); ?>
    <input type="hidden" id="class_id" name="class_id" value="<?=$class_id?>">
    <input type="hidden" id="group_id" name="group_id" value="<?=$group_id?>">
    <input type="hidden" id="section_id"  name="section_id" value="<?=$section_id?>">
    <div class="row">
        <div class="col-sm-6">
                <label>Select Year</label>
                 <select name="fromYear" class="form-control" id="examYear" data-url="<?php echo Url::to(['get-exam-by-year']) ?>">
                    <option>Select Year</option>
                    <?php
                       $starting_year  =date('Y', strtotime('-1 year'));
                       $ending_year = date('Y', strtotime('+0 year'));
                          for($starting_year; $starting_year <= $ending_year; $starting_year++) {
                            echo '<option value="'.$starting_year.'">'.$starting_year.'</option>';
                          }             
                         //echo '</select>'; 
                       ?>
                    </select>
        </div>
        <div class="col-sm-6">
    <div id="single-dropdown" style="display: none;">
        <?=$form->field($examModel, 'fk_exam_type[1]')->widget(Select2::classname(), [
            //'data' => $exams,
            'options' => ['placeholder' => 'Select Exam type','class'=>'examttypeStudentMarks'],
            'pluginOptions' => [
                'allowClear' => true
            ],
        ]);?>
    </div>
    </div>
    </div>
    <div id="multiple-dropdown" style="display: none;">
        <?=$form->field($examModel, 'fk_exam_type[2]')->widget(Select2::classname(), [
            'data' => $exams,
            'options' => ['multiple' => true, 'placeholder' => 'Select Multiple Exam type ...'],
            'pluginOptions' => [
                'allowClear' => true
            ],
        ]);?>
    </div>
    <input type="hidden" id="tab_type" name="tab_type" value =""/>
<?php ActiveForm::end(); ?>
<?php
Modal::end();
?>