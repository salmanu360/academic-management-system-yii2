<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\grid\GridView;
use app\models\StudentInfo;
use kartik\date\DatePicker;
use app\models\RefClass;
use app\models\RefSection;
use app\models\User;
use app\models\RefGroup;
use yii\widgets\ActiveForm;
use kartik\depdrop\DepDrop;
use kartik\select2\Select2;
$this->title = 'Certificates';
?><br>
<div class="row">
        <div class="col-md-12">
        <a class="btn btn-block btn-social btn-facebook">
                <i class="fa fa-bar-chart-o"></i> Certificate Reports
              </a>
        </div>
        </div>
<br />
<div class="row">
        <div class="col-md-12">
        	<div class="nav-tabs-custom">
              <ul class="nav nav-tabs">
              <li class="active"><a href="#tab_1" data-toggle="tab">SLC/Character</a></li>
              <li><a href="#tab_3" data-toggle="tab">Sports</a></li>
              <li><a href="#tab_4" data-toggle="tab">Age</a></li>
              <li><a href="<?php echo Url::to(['id'])?>">ID CARD</a></li>
              <li><a href="#tab_2" data-toggle="tab">Staff Experience Certificate </a></li>
              
  			</ul>
		<div class="tab-content">
			<div class="tab-pane active" id="tab_1">
				
			 <?php $form = ActiveForm::begin(); ?>
       <div class="row">
         <div class="col-md-8">
          <label>SLC</label>
           <input type="radio" name="generalCertificate" id="slcCertificate" value="1" checked="checked">
          <label>Character</label>
           <input type="radio" name="generalCertificate" id="characterCertificate" value="2">
         </div>
       </div>
			 <div class="row">
			 	<div class="col-md-2">		
			 <?php
			 $class_array = ArrayHelper::map(\app\models\RefClass::find()->where(['fk_branch_id'=>Yii::$app->common->getBranch(),'status'=>'active'])->all(), 'class_id', 'title'); 
			  echo $form->field($model, 'class')->dropDownList($class_array, ['id'=>'class-id','prompt' => 'Select Class ...']);  ?>
			 <?php ActiveForm::end(); ?>
			</div>
			<div class="col-md-3">
				<?php 
				echo $form->field($model, 'group')->widget(DepDrop::classname(), [
                    'options' => ['id'=>'group-id'],
                    'pluginOptions'=>[
                        'depends'=>['class-id'],
                        'loadingText' => 'Loading Groups ...',
                        'prompt' => 'Select Group...',
                        'url' => Url::to(['/site/get-group'])
                    ]
                ]); ?>
			</div>
			<div class="col-sm-3">
                <input type="hidden" name="" id="getIactiveStudentsInput" value="<?= Url::to(['student/get-inactive-students']) ?>">
                <?php echo $form->field($model, 'section')->widget(DepDrop::classname(), [
                    'options' => ['id'=>'section-id','class'=>'section-id-inactive form-control'],
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
			  <div class="col-sm-3">
			  	<?php echo $form->field($model, 'stu_id')->widget(Select2::classname(), [
            	'options' => ['placeholder' => 'Select Student','class'=>'studentIdSlc','id'=>'subject-inner','data-url'=>Url::to(['leaving-pdf'])],
            	'pluginOptions' => [
                'allowClear' => true
            	],
	        	])->label('Student');
 				?>
			  </div>	
			  </div>
              <div class="row">
                    <div class="col-sm-6" id="studentInactive">
                        <?php if (Yii::$app->session->hasFlash('Warning')): ?>
                   <div class="alert alert-danger">
                      <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                      <?= Yii::$app->session->getFlash('Warning') ?>
                   </div>
                <?php endif; ?>
                    </div>
                </div>	
        <div id='showLoader'></div>
			  
 			</div>
 			<!-- end of tab 1 & start of tab 2 -->
 			<div class="tab-pane" id="tab_2">
				<div class="row">
        <div class="col-sm-3">
          <?php 
        $stuQuery = User::find()
            ->select(['employee_info.emp_id',"user.id,concat(user.first_name, ' ' ,  user.last_name) as name"])
            ->innerJoin('employee_info','employee_info.user_id = user.id')
            ->where(['employee_info.fk_branch_id'=>yii::$app->common->getBranch()])->asArray()->all();
        $stuArray = ArrayHelper::map($stuQuery,'id','name');
        echo $form->field($model, 'user_id')->widget(Select2::classname(), [
        'data'=>$stuArray,
        'options' => ['placeholder' => 'Select Employee','id'=>'employeeCertificate','data-url'=>Url::to(['employee-certificate'])],
        'pluginOptions' => [
        'allowClear' => true
        ],
        ])->label('Select Employee');

     ?>
        </div> 

        <div class="col-md-3">
                   <?php echo '<label>Leave Date:</label>';
                                      echo DatePicker::widget([
                                      'name' => 'overallstart', 
                                      //'value' => date('01-m-Y'),
                                      'options' => ['placeholder' => ' ','id'=>'employeeCertificateCalendar'],
                                      'pluginOptions' => [
                                          'format' => 'dd-m-yyyy',
                                          'todayHighlight' => true,
                                          'autoclose'=>true,
                                      ]
                                    ]);?>

                          </div>    
        </div>
 			</div>
      <!-- end of tab_2 and start of tab_3 -->
      <div class="tab-pane" id="tab_3">
        <div class="row">
          <div class="col-md-6">
            <label for="">Active</label>
            <input type="radio" name="studentAlumniCheck" id="activeStudents" value='1' checked="checked">
            <label for="">Alumni</label>
            <input type="radio" name="studentAlumniCheck" id="alumniStudents" value='0'>
          </div>
        </div>
        <div class="row">
        <div class="col-md-3">
                <label for="class">Class</label>
                <?= Html::dropDownList('ref_class', null,
                    ArrayHelper::map(RefClass::find()->where(['fk_branch_id'=>yii::$app->common->getBranch(),'status'=>'active'])->all(), 'class_id', 'title'),['prompt'=>'Select Class','class'=>'form-control','id'=>'studentClassWise','data-url'=>Url::to(['student/class-active-inactive-students'])]) ?>
        </div>
          <div class="col-md-3">
                        <?php
                        echo Html::label('Student');
                          echo Select2::widget([
                            'name' => 'state_2',
                            'value' => '',
                            'options' => ['multiple' => false, 'placeholder' => 'Select Student ...','class'=>'classwisestudent','id'=>'studentFeeOne','data-url'=>Url::to(['reports/student-fee-details'])]
                        ]); ?>
          </div>
        <div class="col-md-3">
          <?php 
           $sports_array = ['Cricket'=>'Cricket','Football'=>'Football','Badminton'=>'Badminton','Basketball'=>'Basketball','Boxing'=>'Boxing','Climbing'=>'Climbing','Cycling'=>'Cycling','Diving'=>'Diving','Fencing'=>'Fencing','Field hockey'=>'Field hockey','Golf'=>'Golf','Gymnastics'=>'Gymnastics','handball'=>'handball','Judo'=>'Judo','Karate'=>'Karate','Table tennis'=>'Table tennis','Tennis'=>'Tennis','Volleyball'=>'Volleyball','water polo'=>'water polo','weightlifting'=>'weightlifting'];
            echo '<label>Sports</label>';
            echo Html::dropDownList('sports', null,$sports_array,['prompt'=>'Select Sports','class'=>'form-control','id'=>'sportsArray','data-url'=>Url::to(['sports-pdf'])]) ?>
        </div>
      </div>
      </div>
      <!-- end of tab_4 and start of tab_3 -->
      <!-- start of tab_4 and start of tab_5 -->
      <div class="tab-pane" id="tab_4">
        <div class="row">
          <div class="col-sm-2">
            <label class="control-label">Registeration Number:</label>
          </div>
          <div class="col-sm-3">
              <input type="text" name="generate_report" id="registerationNO" class="form-control">
          </div>
          <div class="col-sm-2">
            <button id="findByRegisteration" class="btn btn-primary btn-sm" data-url="<?= Url::to(['age-certificate']) ?>"><i class="fa fa-print"></i> Print</button>
          </div>
          <!-- <div class="col-md-3"><label for="">Registeration No. :</label><input type="text" name="registeration_no" id="registerationNO"></div>
          <div class="col-md-3"><input type="button" name="findByRegisteration" id="findByRegisteration" class="btn btn-primary btn-sm" value="Print"></div> -->
        </div>
      </div>
      <!-- end of tab_4 and start of tab_6 -->
</div>
</div>
</div>