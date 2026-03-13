<?php 
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use app\models\RefClass;
use kartik\date\DatePicker;

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
				<li class="active"><a href="#tab_1" data-toggle="tab">Class Subject Wise</a></li>
			</ul>
			<div class="tab-content">
				<!-- start of tab 1 -->
				<div class="tab-pane active" id="tab_1">
					<div class="row">
						<div class="col-md-6">
							<label for="">Subject wise</label>
							<input type="radio" name="quizReport" checked="checked" id="classSubjectQuizReport" value='subjectWise'>
							<label for="">Class wise Date</label>
							<input type="radio" name="quizReport" id="classQuizReport" value='classWise'>
							<label for="">Class wise</label>
							<input type="radio" name="quizReport" id="classQuizSession" value='classWiseSession'>
						</div>
					</div>
				<div class="row">
			    <div class="col-md-3">
			    	<label for="group">Class</label>
					<?= Html::dropDownList('class_id', null,
                    ArrayHelper::map(RefClass::find()->where(['fk_branch_id'=>yii::$app->common->getBranch(),'status'=>'active'])->all(), 'class_id', 'title'),['prompt'=>'Select Class','class'=>'form-control','id'=>'getdataclass','data-url'=>Url::to(['general/get-class-details'])]) ?>
			    </div>
			    <div class="col-md-3">
			   <label for="group">Group</label>
               <select name="" id="classdatagroup" class="form-control" data-url="<?= Url::to(['general/get-subjects']);?>">
               </select>
					</div>
				<div class="col-md-3" id="classSubjectHideShow">
				<label for="group">Subjects</label>
			   <select name="" id="getSubjectsdata" class="form-control">
               </select>
				</div>
				<input type="hidden" value="<?php echo Url::to(['reports/class-wise-session-quiz']); ?>" id='class-wise-session-quiz'>
				<div class="col-md-3" id="quizDateHide">
				<input type="hidden" value="<?php echo Url::to(['reports/class-wise-quiz']); ?>" id='class-wise-quiz'>
					<?php echo '<label>Date:</label>';
                                      echo DatePicker::widget([
                                      'name' => 'overallstart', 
                                      'options' => ['placeholder' => ' ','id'=>'quizReport1','data-url'=>Url::to(['reports/class-subject-date'])],
                                      'pluginOptions' => [
                                          'format' => 'yyyy-m-dd',
                                          'todayHighlight' => true,
                                          'autoclose'=>true,
                                      ]
                                    ]);?>
				</div>
			    </div><br>
			    <div class="row">
			    	<div class="col-md-12" id="getFirstReportData"></div>
			    </div>
				</div>
			</div>
		</div>
	</div>
</div>