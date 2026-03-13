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
use app\models\StudentLeaveInfo;
use yii\widgets\ActiveForm;
use kartik\depdrop\DepDrop; 
use kartik\select2\Select2;
use yii\widgets\Pjax;
$this->title = 'General Reports';?>
    <div class="row">
        <div class="col-md-12">
        <a class="btn btn-block btn-social btn-facebook">
                <i class="fa fa-bar-chart-o"></i> General Reports
              </a>
        </div>
        </div>
        <br>
 <div class="row">
        <div class="col-md-12">
          <!-- Custom Tabs -->
          <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
              <li class="active"><a href="#tab_1" data-toggle="tab">New Promotion/Demotion</a></li>
              <li><a href="#tab_6" data-toggle="tab">Promotion Details</a></li>
              <li><a href="#tab_2" data-toggle="tab">New Admissions</a></li>
              <!-- <li><a href="#tab_3" data-toggle="tab">New Promotion</a></li> -->
              <li><a href="#tab_4" data-toggle="tab">Yearly Admission</a></li>
              <li><a href="#tab_5" data-toggle="tab">Issued SLC</a></li>
              <li><a href="<?php echo Url::to(['sibling'])?>">Siblings</a></li>
              <li><a href="#tab_8" data-toggle="tab">Discount Avail</a></li>
              <!-- <li><a href="#tab_9" data-toggle="tab">Total Fee (<?php //echo date('M') ?>)</a></li> -->
              <li><a href="<?php echo Url::to(['fee/month'])?>">Total Fee (<?php echo date('M') ?>)</a></li>
            </ul>
            <div class="tab-content">
              <div class="tab-pane active" id="tab_1">
                <div class="row">
                  <div class="col-md-6">
                    <label for="">Promotion</label>
                    <input type="radio" name="promotionDemotion" value="1" checked="checked">
                    <label for="">Demotion</label>
                    <input type="radio" name="promotionDemotion" value="0">
                  </div>
                </div>
                <div class="row">
                	<div class="col-md-3 col-sm-3">
                		<?php 
		    $class_array = ArrayHelper::map(\app\models\RefClass::find()->where(['fk_branch_id'=>Yii::$app->common->getBranch(),'status'=>'active'])->all(), 'class_id', 'title'); 
		     
		    echo Html::label('Class');
		    echo Html::activeDropDownList($model, 'classid',$class_array,['prompt'=>'Select Class','class'=>'form-control','id'=>'getdataclass','data-url'=>Url::to(['reports/class-data'])]);
                        ?>
                	</div>
               <div class="col-md-3 col-sm-3">
               <label for="group">Group</label>
               <select name="" id="classdatagroup" class="form-control" data-url="<?= Url::to(['reports/group-data']);?>">
               </select>
                </div>
                <div class="col-md-3 col-sm-3">
				    <label for="section">Section</label>
				      <select name="" id="classdatasection" class="form-control promotedStudents" data-url="<?= Url::to(['reports/section-data']);?>"></select>
              <input type="hidden" id="promotedDataUrl" value="<?php echo Url::to(['promoted-students']); ?>">
				    </div>
				    </div>
				    <br />
					<div class="row">
				    <div class="col-md-12" id="promotedStudentsShow">
				   <!--  <a href="javaScript:void()" id="counStudent" style="font-size: 16px;
           font-weight: bold; text-decoration: none" data-url="<?//= Url::to(['reports/student-data-classwise']);?>"></a> -->
				     </div>
 
   
    </div>
              </div>
              <!-- /.tab-pane -->
              <div class="tab-pane" id="tab_2">
                <input type="submit" name="Generate Report" id="newAdmissionClassWise" class="btn btn-primary pull-right" value="Generate Report" data-url=<?php echo Url::to(['reports/new-admission-classwise-pdf']) ?> />

                <table class="table table-striped">
            <thead>
              <tr>
                <th>Class</th>
                <th>No of Students</th>
                <th>Percentage</th>
              </tr>
            </thead>
            <tbody>
              <?php 
              $getAllClasses=RefClass::find()->where(['fk_branch_id'=>yii::$app->common->getBranch(),'status'=>'active'])->all();
               foreach ($getAllClasses as $allclass) {
                $newadmisnAvg=yii::$app->db->createCommand("select abc.class_id,abc.class_name,abc.No_Of_Student as `No_of_student_newly_admitted`, ( (abc.No_Of_Student) / (SELECT count(*) FROM student_info si2 inner join ref_class rc on rc.class_id=si2.class_id where si2.fk_branch_id=".yii::$app->common->getBranch()." and rc.title='".$allclass->title."' and si2.is_active =1) ) * 100 as `Percentage_of_newly_admitted_student` from (select rc.class_id, rc.title as class_name, count(*) as `No_Of_Student` from student_info si inner join ref_class rc on rc.class_id=si.class_id where si.stu_id not in (select fk_stu_id from stu_reg_log_association) and title = '".$allclass->title."' and si.fk_branch_id=".yii::$app->common->getBranch()." and si.is_active = 1 GROUP by rc.class_id, rc.title) abc")->queryAll();
                  /// echo '<pre>';print_r($newadmisnAvg);
                   //continue;
               $admissionarray=[];
               foreach ($newadmisnAvg as $newadmisnAvgx) {?>
              <tr>
                <td><?php echo $admissionarray[]=$newadmisnAvgx['class_name'];?></td>
                <td><?php echo $admissionarray[]=$newadmisnAvgx['No_of_student_newly_admitted'];?></td>
                <td><?php echo round($admissionarray[]=$newadmisnAvgx['Percentage_of_newly_admitted_student'],2).'%';?></td>
              </tr>
              <?php } } ?>
              
            </tbody>
          </table>

              </div>
              <!-- /.tab-pane -->

              <div class="tab-pane" id="tab_3">
                <div id="promtionClassDetails">
                  <input type="submit" name="Generate Report" id="promotedClassWise" class="btn btn-primary pull-right" value="Generate Report" data-url=<?php echo Url::to(['reports/new-promotion-classwise-pdf']) ?> />

                <table class="table table-striped">
        <thead>
          <tr>
            <th>Class</th>
            <th>No of Students</th>
            <!-- <th>Percentage</th> -->
          </tr>
        </thead>
        <tbody>
          <?php 
                $getAllClass=RefClass::find()->where(['fk_branch_id'=>yii::$app->common->getBranch(),'status'=>'active'])->all();
                foreach ($getAllClass as $allclasx) {
                 $studentPercetn=yii::$app->db->createCommand("select abc.class_id,abc.class_name,abc.No_Of_Student as `No_of_student_newly_admitted`,
                   ((abc.No_Of_Student)/
                    (SELECT count(*) FROM  student_info si2 
                    inner join ref_class rc on rc.class_id=si2.class_id
                    where si2.fk_branch_id='".Yii::$app->common->getBranch()."' and rc.title= '".$allclasx->title."' and si2.is_active =1))* 100
                    as `Percentage_of_newly_admitted_student`
                    from 
                    (select rc.class_id, rc.title as class_name, count(*) as `No_Of_Student` from student_info si
                    inner join ref_class rc on rc.class_id=si.class_id
                    where si.stu_id in (select fk_stu_id from stu_reg_log_association) and title = '".$allclasx->title."'  and si.fk_branch_id=".yii::$app->common->getBranch()." and si.is_active = 1
                    GROUP by rc.class_id, rc.title) abc")->queryAll();
               $stuarray=[];
               // echo '<pre>';print_r($studentPercetn);die;
               foreach ($studentPercetn as $studentPercetx) {?>
            <tr>
              <td><?php echo $stuarray[]=$studentPercetx['class_name'];?></td>
              <td><span id="newlyPromotedName" style="cursor: pointer;text-decoration: underline;color: #3c8dbc;" data-classid="<?php echo $studentPercetx['class_id'] ?>" data-url="<?php echo Url::to(['newly-promotion-name']) ?>"><?php echo $stuarray[]=$studentPercetx['No_of_student_newly_admitted'];?></span></td>
              <!-- <td><?php //echo round($stuarray[]=$studentPercetx['Percentage_of_newly_admitted_student'],2).'%';?></td> -->
            </tr>
              <?php } } ?>
            </tbody>
          </table>
                </div>
          <div class="row">
            <div class="col-md-12" id="showNewlyPromotionName"></div>
          </div>
              </div>
              <!-- /.tab-pane -->
              <div class="tab-pane" id="tab_4">
              	 <div class="row">
				    <div class="col-md-3 yearCalendar">
				    <select name="fromYear" class="form-control YearCal" data-url=<?php echo Url::to(['reports/yearly-admission'])?>>
				    <option>Select Year</option>
				    <?php
				       '<select name="fromYear">';
				       $starting_year  =date('Y', strtotime('-10 year'));
				       $ending_year = date('Y', strtotime('+0 year'));
				          for($starting_year; $starting_year <= $ending_year; $starting_year++) {
				            echo '<option value="'.$starting_year.'">'.$starting_year.'</option>';
				          }             
				         //echo '</select>'; 
				       ?>
				    </select>
				    </div>
				    </div>
				    <br />
				    <div class="row">
				    <div class="col-md-12 getYearadmission table-responsive"></div>
				    </div>
              </div>
              <!-- /.tab-pane -->
              <div class="tab-pane" id="tab_5">
              	<div class="row">
              		<div class="col-md-3">
              			<select name="fromYear" id="yearLeave" class="form-control" data-url=<?php echo Url::to(['reports/leave-school'])?>>
					    <option>Select Year</option>
					    <?php
					       '<select name="fromYear">';
					       $starting_year  =date('Y', strtotime('-15 year'));
					       $ending_year = date('Y', strtotime('+0 year'));
					          for($starting_year; $starting_year <= $ending_year; $starting_year++) {
					            echo '<option value="'.$starting_year.'">'.$starting_year.'</option>';
					          } 
			               ?>
			    </select>
              		</div>
              		<div class="col-md-3" style="display: none" id="showPdfLeave">
				       <input type="submit" name="Generate Report" value="Generate Report" class="btn btn-primary" id="yearlevpdf" data-url="<?php echo Url::to(['reports/leave-school-pdf'])?>" />
				    </div>
				    <div class="col-md-3" style="display: none" id="leaveYearpdf">
				       <input type="submit" name="Generate Report" value="Generate Report" class="btn btn-primary" id="levyearpdf" data-url="<?php echo Url::to(['reports/leave-scholl-class-pdf'])?>" />
				    </div>
				    <div class="col-md-3" style="display: none" id="leaveYearstudpdf">
				       <input type="submit" name="Generate Report" value="Generate Report" class="btn btn-primary" id="leaveYearstudntpdf" data-url="<?php echo Url::to(['reports/leave-schol-class-student-pdf'])?>" />
				    </div>
				    <input type="hidden" name="" value="" id="clsxId">
              	</div>
              	<div class="row">
				    <div class="col-md-12" id="showleavestu"></div>
				</div>
              </div>
              <!-- end of tab 5 and start of tab 6 -->
              <div class="tab-pane" id="tab_6">
              <div class="row">
                <div class="col-md-3">
                  <label for="class">Class</label>
                
                  <?= Html::dropDownList('ref_class', null,
                      ArrayHelper::map(RefClass::find()->where(['fk_branch_id'=>yii::$app->common->getBranch(),'status'=>'active'])->all(), 'class_id', 'title'),['prompt'=>'Select Class','class'=>'form-control','id'=>'studentClassWise','data-url'=>Url::to(['student/class-wise-students'])]) ?>
                </div>
                <div class="col-md-3">
                              <?php
                              echo Html::label('Student');
                                echo Select2::widget([
                                  'name' => 'state_2',
                                  'value' => '',
                                  'options' => ['multiple' => false, 'placeholder' => 'Select Student ...','class'=>'classwisestudent','data-url'=>Url::to(['reports/show-stu-details'])]
                              ]); ?>
                  
                </div>
              </div>
              <div class="row">
              <div class="col-md-12" id="showStudentDetails"></div>
                
              </div>
              </div>
              <!-- end of tab 6 and start of tab_7 -->
              <div class="tab-pane" id="tab_7">
                <!-- sibling -->
              </div>
              <div class="tab-pane" id="tab_8">
               <div class="row">
                 <div class="col-md-3">
                  <label for="class">Class</label>
                  <?= Html::dropDownList('ref_class', null,
                      ArrayHelper::map(RefClass::find()->where(['fk_branch_id'=>yii::$app->common->getBranch(),'status'=>'active'])->all(), 'class_id', 'title'),['prompt'=>'Select Class','class'=>'form-control','id'=>'studentClassWise','data-url'=>Url::to(['discount-avail'])]) ?>
                </div>
               </div><br />
               <div class="row">
                 <div class="col-md-12 classwisestudent"></div>
               </div>
               </div>
              <div class="tab-pane" id="tab_9">
                <?php $form = ActiveForm::begin(); ?> 
        <div class="row">
           <div class="col-sm-9">
                <div class="col-sm-4 fh_item" style="color: black !important;">
                <?php 
                    echo  $form->field($model, 'class_id')->dropDownList($class_array, ['id'=>'class-id','prompt' => 'Select'.' '. Yii::t('app','Class')]);
                
                    ?>
                </div>
                <div class="col-sm-4 fh_item" style="color: black !important;">
                    <?php
                        echo $form->field($model, 'group_id')->widget(DepDrop::classname(), [
                            'options' => ['id'=>'group-id','value'=>1],
                            'pluginOptions'=>[
                            'depends'=>['class-id'],
                            'prompt' => 'Select Group...',
                            'url' => Url::to(['/site/get-group'])
                        ]
                    ]);?>
                        
                        
                </div>
                <div class="col-sm-4 fh_item" style="color: black !important;">
                    <input type="hidden" id="subject-url" value="<?=Url::to(['/fee/get-current-month-notsubmited-fee-report'])?>">
                    <?php
                    
                    // Dependent Dropdown
                    echo $form->field($model, 'section_id')->widget(DepDrop::classname(), [
                        'options' => ['id'=>'section-id'],
                        'pluginOptions'=>[
                            'depends'=>[
                                'group-id','class-id'
                            ],
                            'prompt' => 'Select section',
                            'url' => Url::to(['/site/get-section'])
                        ]
                    ]);
                    ?>
                </div>
           </div> 
        </div> 
        <div  id="subject-details">
            <div id="subject-inner"></div>
        </div> 
        <?php ActiveForm::end(); ?>
              </div>
              <!-- /.tab-pane -->

            </div>
            <!-- /.tab-content -->
          </div>
          <!-- nav-tabs-custom -->
        </div>
        <!-- /.col -->

        
        <!-- /.col -->
      </div>