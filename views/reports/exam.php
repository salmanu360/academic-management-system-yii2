<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\grid\GridView;
use app\models\StudentInfo;
use kartik\date\DatePicker;
use app\models\RefClass;
use app\models\RefSection;
use app\models\RefGroup;
use yii\widgets\ActiveForm;
use kartik\depdrop\DepDrop;
use kartik\select2\Select2;
$this->title = 'Exam Reports';
?>
<div class="row">
        <div class="col-md-12">
        <a class="btn btn-block btn-social btn-facebook">
                <i class="fa fa-bar-chart-o"></i> Exam Reports
              </a>
        </div>
        </div>
<br />
 <div class="row">
   <div class="col-md-12">
          <!-- Custom Tabs -->
    <div class="nav-tabs-custom">
              <ul class="nav nav-tabs">
              <!-- <li><a href="#tab_1" data-toggle="tab">Exams Taken</a></li> -->
              <li class="active"><a href="#tab_2" data-toggle="tab">Upcoming Exam/Exam Taken</a></li>
              <li><a href="#tab_5" data-toggle="tab">Student Wise Result</a></li>
              
  			</ul>
		<div class="tab-content">
      <div class="tab-pane " id="tab_1">
      
      </div>
			<!-- end of tab 1 and start of tab 2 -->
      <div class="tab-pane active" id="tab_2">
        <div class="row">
          <div class="col-sm-3">
            <label>Upcoming</label>
            <input type="radio" name="upcoming" id="upcoming" checked="checked" value="1">
            <label>Taken</label>
            <input type="radio" name="upcoming" id="taken" value="2">
          </div>
        </div>
        <div class="row">
           <div class="col-md-3 col-sm-3">
                    <label for="class">Class</label>
                 
                  <?= Html::dropDownList('ref_class', null,
                      ArrayHelper::map(RefClass::find()->where(['fk_branch_id'=>yii::$app->common->getBranch(),'status'=>'active'])->all(), 'class_id', 'title'),['prompt'=>'Select Class','class'=>'form-control','id'=>'getdataclass','data-url'=>Url::to(['reports/class-data'])]) ?>
                    
                  </div>
               <div class="col-md-3 col-sm-3">
               <label for="group">Group</label>
               <select name="" id="classdatagroup" class="form-control" data-url="<?= Url::to(['reports/group-data']);?>">
               </select>
                </div>
                <div class="col-md-3 col-sm-3">
            <label for="section">Section</label>
              <select name="" id="classdatasection" class="form-control"></select>
            </div>
          <div class="col-md-3">
            <label>Year</label>
            <select name="fromYear" class="form-control" id="examYearUpcomming" data-url=<?php echo Url::to(['reports/yearly-exam'])?>>
            <option>Select Year</option>
            <?php
               $starting_year  =date('Y', strtotime('-3 year'));
               $ending_year = date('Y', strtotime('+2 year'));
                  for($starting_year; $starting_year <= $ending_year; $starting_year++) {
                    echo '<option value="'.$starting_year.'">'.$starting_year.'</option>';
                  }  
               ?>
            </select>
          </div>
        </div>
        <br />
        <div class="row">
        <div class="col-md-12" id="getUpcommingExams"></div>
        </div>
      </div>

      <!-- end of tab 4 and start of tab 5 -->
       <div class="tab-pane" id="tab_5">
        <div class="row">
          <div class="col-sm-3">
            <label>Exam Wise</label>
            <input type="radio" name="studentResults" id="examWise" checked="checked" value="1">
            <label>Year Wise</label>
            <input type="radio" name="studentResults" id="YearWise" value="2">
          </div>
        </div>
        <?php $form = ActiveForm::begin(); ?>
        <div class="row">
          <div class="col-md-2">
          <?php 

        $class_array = ArrayHelper::map(\app\models\RefClass::find()->where(['fk_branch_id'=>Yii::$app->common->getBranch(),'status'=>'active'])->all(), 'class_id', 'title'); 
         echo $form->field($model, 'fk_class_id')->dropDownList($class_array, ['id'=>'class-id','prompt' => 'Select Class ...']);
        ?>
        </div>
        <div class="col-md-2 col-sm-2">
               <?php echo $form->field($model, 'fk_group_id')->widget(DepDrop::classname(), [
                    'options' => ['id'=>'group-id'],
                    'pluginOptions'=>[
                        'depends'=>['class-id'],
                        'loadingText' => 'Loading Groups ...',
                        'prompt' => 'Select Group...',
                        'url' => Url::to(['/site/get-group'])
                    ]
                ]); ?>
                </div>
        <div class="col-sm-4 fh_item">
                <input type="hidden" id="subject-url" value="<?=Url::to(['/student/section-wise-students'])?>">
            <?php echo $form->field($model, 'fk_section_id')->widget(DepDrop::classname(), [
                    'options' => ['id'=>'section-id','style'=>'width:153.16px'],
                    'pluginOptions'=>[
                        'depends'=>[
                            'group-id','class-id'
                        ],
                        'loadingText' => 'Loading Sections ...',
                        'prompt' => 'Select section',
                        'url' => Url::to(['/site/get-section'])
                    ]
                ]); ?>
            </div>

            <div class="col-sm-3" style="margin-left: -185px;">
          <?php echo $form->field($model, 'student')->widget(Select2::classname(), [
            'options' => ['placeholder' => 'Select Student','class'=>'classwisestudent','id'=>'subject-inner'],
            'pluginOptions' => [
                'allowClear' => true
            ],
        ]);
        ?>
      </div>
       
        <div class="col-sm-2">
                <label>Select Year</label>
                 <div id="yearOne">
                   <select name="fromYear" class="form-control" id="examYear" data-url="<?php echo Url::to(['get-exam-by-year']) ?>">
                    <option>Select Year</option>
                    <?php
                       $starting_year  =date('Y', strtotime('-2 year'));
                       $ending_year = date('Y', strtotime('+2 year'));
                          for($starting_year; $starting_year <= $ending_year; $starting_year++) {
                            echo '<option value="'.$starting_year.'">'.$starting_year.'</option>';
                          }             
                       ?>
                    </select>
                 </div>
                 <div id="yearTwo" style="display: none">
                   <select name="fromYear" class="form-control" id="examAgainstYear" data-url="<?php echo Url::to(['get-exam-yearwise']) ?>">
                    <option>Select Year</option>
                    <?php
                       $starting_year  =date('Y', strtotime('-2 year'));
                       $ending_year = date('Y', strtotime('+2 year'));
                          for($starting_year; $starting_year <= $ending_year; $starting_year++) {
                            echo '<option value="'.$starting_year.'">'.$starting_year.'</option>';
                          }             
                       ?>
                    </select>
                 </div>
            </div>
            
          </div>
          <div class="row" id="examWiseType">
            <div class="col-sm-2">
                <?php
                echo $form->field($model, 'fk_exam_type')->widget(Select2::classname(), [
                'options' => ['id'=>'exam-type-id','class'=>'examttypeStudentMarks','data-url'=>Url::to(['studentmarks-against-exam'])],
                'pluginOptions' => [
                'allowClear' => true
                ],
                ]);
                ?>
            </div>
          </div>
        <?php ActiveForm::end(); ?>
        <div class="row">
          <div class="col-md-12" id="getStudentMarksAgainstExamId"></div>
        </div>
       </div>
      <!-- end of tab 5 and start of tab 6 -->
		</div>
  	</div>
  </div>
 </div>			