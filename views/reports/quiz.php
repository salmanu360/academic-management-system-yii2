<?php 
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use app\models\RefClass;
use kartik\date\DatePicker;
use yii\widgets\ActiveForm;
use kartik\depdrop\DepDrop;
use kartik\select2\Select2;
 ?>
<div class="row">
	<div class="col-md-12">
		<a class="btn btn-block btn-social btn-facebook">
			<i class="fa fa-bar-chart-o"></i> Quiz Reports
		</a>
	</div>
</div>
<br>
<div class="row">
	<div class="col-md-12">
		<!-- Custom Tabs -->
		<div class="nav-tabs-custom">
			<ul class="nav nav-tabs">
				<li class="active"><a href="#tab_1" data-toggle="tab">Subject/Class Wise</a></li>
				<li><a href="#tab_2" data-toggle="tab">Student Wise</a></li>
			</ul>
			<div class="tab-content">
				<!-- start of tab 1 -->
				<div class="tab-pane active" id="tab_1">
					<div class="row">
						<div class="col-md-6">
							<label for="">Subject wise</label>
							<input type="radio" name="quizReport" checked="checked" id="classSubjectQuizReport" value='subjectWise'>
							<label for="">Class wise</label>
							<input type="radio" name="quizReport" id="classQuizReport" value='classWise'>
						</div>
					</div>
				<div class="row">
			    <div class="col-md-3">
			    	<label for="group">Class</label>
			    	<div id="removeClassQuiz" style="display: none">
					<?= Html::dropDownList('class_id', null,
                    ArrayHelper::map(RefClass::find()->where(['fk_branch_id'=>yii::$app->common->getBranch(),'status'=>'active'])->all(), 'class_id', 'title'),['prompt'=>'Select Class','class'=>'form-control','id'=>'studentClassWise','data-url'=>Url::to(['class-wise-quiz-session'])]) ?>
					</div>
					<div id="subjectClassHide">
						<?= Html::dropDownList('class_id', null,
                    ArrayHelper::map(RefClass::find()->where(['fk_branch_id'=>yii::$app->common->getBranch(),'status'=>'active'])->all(), 'class_id', 'title'),['prompt'=>'Select Class','class'=>'form-control','id'=>'getdataclass','data-url'=>Url::to(['general/get-class-details'])]) ?>
					</div>
			    </div>
			   <div class="col-md-3" id="groupShow">
			   <label for="group">Group</label>
               <select name="" id="classdatagroup" class="form-control" data-url="<?= Url::to(['general/get-subjects']);?>">
               </select>
			   </div>
				<div class="col-md-3" id="classSubjectHideShow">
				<label for="group">Subjects</label>
			   <select name="" id="getSubjectsdata" class="form-control" data-url="<?php echo Url::to(['get-subject-wise-quiz']) ?>">
               </select>
				</div>
				
			    </div><br>
			    <div class="row">
			    	<div class="col-md-12 renderView"></div>
			    </div>
			    <div class="row">
			    	<div class="col-md-12 classwisestudent"></div>
			    </div>
				</div>
				<!-- end of tab1 and start of tab2 -->
				<div class="tab-pane" id="tab_2">
					<!-- <div class="row">
							          <div class="col-sm-2">
							            <label class="control-label">Registeration Number:</label>
							          </div>
							          <div class="col-sm-3">
							              <input type="text" id="registerationNO" class="form-control">
							          </div>
							          <div class="col-sm-2">
							            <button id="findByRegisteration" class="btn btn-primary btn-sm" data-url="<?//= Url::to(['student-wise-quiz']) ?>"> View</button>
							          </div>
							        </div> -->
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
            'options' => ['placeholder' => 'Select Student','class'=>'studentWiseQuizReport','id'=>'subject-inner','data-url'=>Url::to(['student-wise-quiz-report'])],
            'pluginOptions' => [
                'allowClear' => true
            ],
        ]);
        ?>
      </div>
          </div>
          <?php ActiveForm::end(); ?>
		        <br>
		        <div class="row">
			    	<div class="col-md-12" id="studentQuizDetails"></div>
			    </div>
				</div>
				<!-- end of tab2 and start of tab3 -->
			</div>
		</div>
	</div>
</div>