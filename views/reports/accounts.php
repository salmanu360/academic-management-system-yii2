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
$this->title = 'Financial Reports';
?>
    <div class="row">
        <div class="col-md-12">
        <a class="btn btn-block btn-social btn-facebook">
                <i class="fa fa-bar-chart-o"></i> Financial Reports
              </a>
        </div>
        </div>
<br>
<div class="row">
        <div class="col-md-12">
          <!-- Custom Tabs -->
          <div class="nav-tabs-custom">
            <?php echo $this->render('finance/accounts_menu.php') ?>
            <div class="tab-content">
            <!-- start of tab 6 -->
            <div class="tab-pane active" id="tab_6">
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
                                  'options' => ['multiple' => false, 'placeholder' => 'Select Student ...','class'=>'classwisestudent','id'=>'studentFeeOne','data-url'=>Url::to(['reports/student-fee-details'])]
                              ]); ?>
                  
                </div>
               <div style="height: 23px"></div>
               
               </div><br />
               <div class="row">
               <div class="col-md-12 col-sm-12" id="studentFeeDetails">
                  
               </div>
               </div>
              </div>
            <!-- end of tab 6 and start of tab 7 -->
            <!-- uncomment if need today class fre recv -->
            <!-- <div class="tab-pane" id="tab_7">
               <div class="row">
                 <div class="col-md-3">
                  <label for="class">Class</label>
                  <?/*= Html::dropDownList('ref_class', null,
                      ArrayHelper::map(RefClass::find()->where(['fk_branch_id'=>yii::$app->common->getBranch(),'status'=>'active'])->all(), 'class_id', 'title'),['prompt'=>'Select Class','class'=>'form-control','id'=>'todayLedger','data-url'=>Url::to(['reports/today-class-ledger'])]) */?>
                </div>
               <div style="height: 23px"></div>
               
               </div><br />
               <div class="row">
               <div class="col-md-12 col-sm-12" id="todayClassLedgerView">
                  
               </div>
               </div>
              </div> -->
              <!-- end of tab 7 and start of tab 8 -->
              <div class="tab-pane" id="tab_8">
                <div class="row">
                  <div class="col-sm-3">
                    <label>Active</label>
                    <input type="radio" name="studentAlumniCheck" id="activeStudents" value="1" checked="checked">
                    <label>Alumni</label>
                    <input type="radio" name="studentAlumniCheck" id="alumniStudents" value="0">
                  </div>
                </div>
               <div class="row">
                 <div class="col-md-3">
                  <label for="class">Class</label>
                  <?= Html::dropDownList('ref_class', null,
                      ArrayHelper::map(RefClass::find()->where(['fk_branch_id'=>yii::$app->common->getBranch(),'status'=>'active'])->all(), 'class_id', 'title'),['prompt'=>'Select Class','class'=>'form-control','id'=>'studentClassWise','data-url'=>Url::to(['student/class-students-active-inactive'])]) ?>
                </div>
                <div class="col-md-3">
                              <?php
                              echo Html::label('Student');
                                echo Select2::widget([
                                  'name' => 'feearrear',
                                  'value' => '',
                                  'options' => ['multiple' => false, 'placeholder' => 'Select Student ...','class'=>'classwisestudent','id'=>'studentFeeThree','data-url'=>Url::to(['reports/student-arrear'])]
                              ]); ?>
                  
                </div>
               <div style="height: 23px"></div>
               
               </div><br />
               <div class="row">
               <div class="col-md-12 col-sm-12" id="studentArrear">
                  
               </div>
               </div>
              </div>
              <!-- end of tab 8 and start of tab 9 -->
              
              <!-- end of tab 9 and start of tab 10 -->
              <div class="tab-pane" id="tab_10">
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
              <select name="" id="classdatasection" class="form-control" data-url="<?= Url::to(['reports/class-arrears']);?>"></select>
            </div>
            </div>
            <br/>
               <div class="row">
                <div class="col-md-12" id="counStudent">
                
                </div>
               </div>
               
              </div>
              <!-- end of tab 10 start of tab 11 -->
          <!-- <div class="tab-pane" id="tab_11">
          <?php //$form = ActiveForm::begin(); ?>
          <div class="row">
           <div class="col-sm-9">
            <div class="col-sm-4 fh_item">
              <?php 
              /*$class_array = ArrayHelper::map(\app\models\RefClass::find()->where(['status'=>'active','fk_branch_id'=>Yii::$app->common->getBranch()])->all(), 'class_id', 'title');*/ ?>
              <?//= $form->field($model, 'fk_class_id')->dropDownList($class_array, ['id'=>'class-id','prompt' => 'Select Class ...']); ?>
            </div>
            <div class="col-sm-4 fh_item">
               <?php
          /*echo $form->field($model, 'fk_group_id')->widget(DepDrop::classname(), [
            'options' => ['id'=>'group-id'],
            'pluginOptions'=>[
              'depends'=>['class-id'],
              'prompt' => 'Select Group...',
              'url' => Url::to(['/site/get-group'])
            ]
          ]);*/
        ?>
            </div>
            <div class="col-sm-4 fh_item">
                <input type="hidden" id="subject-url" value="<?//=Url::to(['/reports/class-fee'])?>">  <?php
                /*echo $form->field($model, 'fk_section_id')->widget(DepDrop::classname(), [
                    'options' => ['id'=>'section-id'],
                    'pluginOptions'=>[
                        'depends'=>[
                            'group-id','class-id'
                        ],
                        'prompt' => 'Select section',
                        'url' => Url::to(['/site/get-section'])
                    ]
                ]);*/
                ?>
            </div>
           </div> 
        </div>
            <?php //ActiveForm::end(); ?>
            <br/>
               <div class="row">
                <div class="col-md-12">
                  <div  id="subject-details">
                  <div id="subject-inner"></div>
                  </div>
                </div>
               </div>
              </div> -->
              <!-- end of tab 11 and start of tab 12 -->
              
              <!-- end of tab 12 and start of tab 13-->
              <!-- <div class="tab-pane" id="tab_13">
               <div class="row">
                <div class="col-md-3">
                  <select name="fromYear" id="yearlyFeeReport" class="form-control" data-url=<?php //echo Url::to(['reports/yearly-report'])?>>
              <option>Select Year</option>
              <?php
                 /*'<select name="SelectYear">';
                 $starting_year  =date('Y', strtotime('-15 year'));
                 $ending_year = date('Y', strtotime('+0 year'));
                    for($starting_year; $starting_year <= $ending_year; $starting_year++) {
                      echo '<option value="'.$starting_year.'">'.$starting_year.'</option>';
                    } */
                     ?>
                   </select>
                </div>
               </div>
               <div class="row">
                 <div class="col-md-12" id="getYearlyReport"></div>
               </div>
              </div> -->
              <!-- end of tab 13 and start of tab_14 -->
              <div class="tab-pane" id="tab_14">
                <div class="row">
                  <div class="col-sm-3">
                    <label>Monthly</label>
                    <input type="radio" name="studentFeeDetail" id="studentFeeDetailMonthly" value="1" checked="checked">
                    <label>Yearly</label>
                    <input type="radio" name="studentFeeDetail" id="studentFeeDetailYearly" value="2">
                  </div>
                </div>
               <div class="row">
                <div class="col-md-3">
                  <label for="class">Class</label>
                  <?= Html::dropDownList('ref_class', null,
                      ArrayHelper::map(RefClass::find()->where(['fk_branch_id'=>yii::$app->common->getBranch(),'status'=>'active'])->all(), 'class_id', 'title'),['prompt'=>'Select Class','class'=>'form-control classIdYearly','id'=>'studentClassWise','data-url'=>Url::to(['student/class-wise-students'])]) ?>
                </div>
                <div class="col-md-3">
                              <?php
                              echo Html::label('Student');
                                echo Select2::widget([
                                  'name' => 'state_2',
                                  'value' => '',
                                  'options' => ['multiple' => false, 'placeholder' => 'Select Student ...','class'=>'classwisestudent','id'=>'studentFee14','data-url'=>Url::to(['reports/student-fee-details'])]
                              ]); ?>
                </div>
                <div class="col-md-3">
                  <div id="monthlyStudentFee">
                    <?php 
                    echo '<label>Select Month:</label>';
                    echo DatePicker::widget([
                    'name' => 'startdate', 
                    //'value' => date('01-m-Y'),
                    'options' => ['placeholder' => ' ','id'=>'monthlyDateFeeStudentsRcv','data-url'=>Url::to(['reports/monthly-report-classwise'])],
                    'pluginOptions' => [
                        'autoclose' => true,
                        'startView'=>'year',
                        'minViewMode'=>'months',
                        'format' => 'yyyy-mm'
                    ]
                  ]);?>
                  </div>
                 <div id="yearlyStudentFee" style="display: none">
                    <label for="">Select Year</label>
                  <select name="fromYear" id="yearlyFeeReportClassWise" class="form-control" data-url=<?php echo Url::to(['reports/yearly-report-classwise'])?>>
                  <option>Select Year</option>
              <?php
                 '<select name="SelectYear">';
                 $starting_year  =date('Y', strtotime('-3 year'));
                 $ending_year = date('Y', strtotime('+1 year'));
                    for($starting_year; $starting_year <= $ending_year; $starting_year++) {
                      echo '<option value="'.$starting_year.'">'.$starting_year.'</option>';
                    } 
                     ?>
          </select>
                 </div>
                </div>
               </div>
               
               <div class="row">
                <br />
                 <div class="col-md-12" id="yearlyFeeReportClassWiseStudents"></div>
               </div>
              </div>
              <!-- end of tab 14 -->
              <div class="tab-pane" id="tab_1">
               <div class="pad15 row" id="cashflowCalendar"">
                 <div class="col-md-3">
                   <?php echo '<label>Start Date:</label>';
                                      echo DatePicker::widget([
                                      'name' => 'overallstart', 
                                      'value' => date('01-m-Y'),
                                      'options' => ['placeholder' => ' ','id'=>'startDate'],
                                      'pluginOptions' => [
                                          'format' => 'dd-m-yyyy',
                                          'todayHighlight' => true,
                                          'autoclose'=>true,
                                      ]
                                    ]);?>

                          </div>
                                <!-- start of class -->
                     <div class="col-md-3">
                     <?php echo '<label>End Date:</label>';
                           echo DatePicker::widget([
                           'name' => 'overallEnd', 
                           'value' => date('d-m-Y'),
                           'options' => ['placeholder' => ' ','id'=>'endDate'],
                           'pluginOptions' => [
                              'format' => 'dd-m-yyyy',
                              'todayHighlight' => true,
                              'autoclose'=>true,
                           ]
                          ]); ?>
               </div>
               <div style="height: 23px"></div>
               <div class="col-md-5 sg_btns">
                                <input type="submit" name="submit" class="cashflow btn btn-primary" data-url="<?php echo Url::to(['reports/overll-cash-flow'])?>" />
                                <button name="Generate Report" class="cashflow btn btn-success" data-url="<?php echo Url::to(['reports/overll-cash-flow-pdf'])?>"><i class="fa fa-download"></i> Generate Report</button>
                            </div> 
               </div><br />
               <div class="row">
               <div class="col-md-12 col-sm-12">
                  <div class="nopad pad_cntent"><div id="cashflowhere"></div></div>
                  </div>
               </div>
              </div>
              <!-- /.tab-pane -->
              <div class="tab-pane" id="tab_2">
                <div class="row">
                  <div class="col-md-3">
                    <label>Class</label>
                           <?= Html::dropDownList('ref_class', null,
                             ArrayHelper::map(RefClass::find()->where(['fk_branch_id'=>yii::$app->common->getBranch(),'status'=>'active'])->all(), 'class_id', 'title'),['prompt'=>'Select Class','class'=>'form-control','id'=>'getStuClassWise','data-url'=>Url::to(['reports/get-stu-classwise'])]) ?>
                  </div>
                  <div class="col-md-4 showStu" style="display: none">
                            <?php echo Html::label('Student');?>
                              <?php
                                echo Select2::widget([
                                  'name' => 'state_2',
                                  'value' => '',
                                  //'data' => $stuQuery,
                                  'options' => ['multiple' => false, 'placeholder' => 'Select states ...','class'=>'stu','data-url'=>Url::to(['reports/show-stu-data'])]
                              ]); ?>
                        </div>
                        <div style="height: 23px"></div>
                        <div class="col-md-2">
                         <a href="" id="generate-std-ledger-pdf" class="headWise btn btn-success"   style ="display:none;" data-url = "<?php echo Url::to(['reports/student-ledger-pdf'])?>"><i class="fa fa-download"></i> Generate Report</a>
                       
                        </div>
                </div>
                <div class="row">
                  <div class="col-md-12">
                    <div class="nopad">
                        <div class="studentdata"></div>
                    </div>
                  </div>
                </div>
              </div>
              <!-- /.tab-pane -->
              <div class="tab-pane" id="tab_3">
               <div class="row">
                 <div class="col-md-3">
                      <?php  echo '<label>Start Date:</label>';
                            echo DatePicker::widget([
                            'name' => 'overallstart', 
                            'value' => date('01-m-Y'),
                            'options' => ['placeholder' => ' ','id'=>'startDates'],
                            'pluginOptions' => [
                                'format' => 'dd-m-yyyy',
                                'todayHighlight' => true,
                                'autoclose'=>true,
                            ]
                          ]);?>
    
                                </div>
                            <!-- start of class -->
                           <div class="col-md-3">
                            <?php echo '<label>End Date:</label>';
                                  echo DatePicker::widget([
                                  'name' => 'overallEnd', 
                                  'value' => date('d-m-Y'),
                                  'options' => ['placeholder' => ' ','id'=>'endDates'],
                                  'pluginOptions' => [
                                      'format' => 'dd-m-yyyy',
                                      'todayHighlight' => true,
                                      'autoclose'=>true,
                                  ] ]); ?>
                            </div>
                            <div style="height: 23px"></div>
                             <div class="col-md-4 sg_btns">
                                <input type="submit" name="submit" class="headWise btn btn-primary" data-url="<?php echo Url::to(['reports/headwise-payment-recv'])?>" />

                                   <button name="Generate Report" value="Generate Report" class="headWise btn btn-success" data-url="<?php echo Url::to(['reports/headwise-payment-recv-pdf'])?>"><i class="fa fa-download"></i> Generate Report</button>
                                <!-- <input type="submit" name="Generate Report" value="Generate Report" class="cashflow btn btn-success" data-url="<?php // echo Url::to(['reports/overll-cash-flow-pdf'])?>" /> -->
                            </div>
               </div><br />
               <div class="row">
                 <div class="col-md-12">
                   <div class="nopad">
                            <div class="headwise-pay"></div>
                        </div>
                 </div>
               </div>
              </div>
              <!-- /.tab-pane -->
              <div class="tab-pane" id="tab_4">
              <div class="row">
                <div class="col-md-3">
                              <?php echo '<label>Start Date:</label>';
                                echo DatePicker::widget([
                                'name' => 'overallstart', 
                                'value' => date('01-m-Y'),
                                'options' => ['placeholder' => ' ','id'=>'startDatess'],
                                'pluginOptions' => [
                                    'format' => 'dd-m-yyyy',
                                    'todayHighlight' => true,
                                    'autoclose'=>true,
                                ]]);?>
              </div>
                                <!-- start of class -->
              <div class="col-md-3">
                   <?php echo '<label>End Date:</label>';
                            echo DatePicker::widget([
                            'name' => 'overallEnd', 
                            'value' => date('d-m-Y'),
                            'options' => ['placeholder' => ' ','id'=>'endDatess'],
                            'pluginOptions' => [
                                'format' => 'dd-m-yyyy',
                                'todayHighlight' => true,
                                'autoclose'=>true,
                            ]]); ?>
              </div>
                                  <div style="height: 23px"></div>
                                  <div class="col-md-4">
                                  <input type="submit" name="submit" class="studentOverlReport btn btn-primary" data-url="<?php echo Url::to(['reports/student-overll-report'])?>" />
                                  
                                  <button name="Generate Report" class="studentOverlReport btn btn-success" data-url="<?php echo Url::to(['reports/students-overall-report-pdf'])?>"><i class="fa fa-download"></i> Generate Report</button>
                                  </div>
              </div>
            <br>
              <div class="row">
                <div class="col-md-12">
                  <div class="showOverallStudent"></div>
                </div>
              </div>
              </div>
              <!-- /.tab-pane -->
              <div class="tab-pane" id="tab_5">
                <div class="row">
                  <div class="col-md-3">
                    <label>Class</label>
                    <?= Html::dropDownList('ref_class', null,
                        ArrayHelper::map(RefClass::find()->where(['fk_branch_id'=>yii::$app->common->getBranch(),'status'=>'active'])->all(), 'class_id', 'title'),['prompt'=>'Select Class','class'=>'form-control','id'=>'getAnotherStuClassWise','data-url'=>Url::to(['reports/get-stu-receipt-wise'])]) ?>

                </div><br />
                <div class="row">
                  <div class="col-md-12">
                    <div class="nopad">
                            <div class="anotherstudentdata"></div>
                        </div>
                  </div>
                </div>
                </div>
              </div>
              <!-- /.tab-pane -->

              <!--tab 15 yearly class wise fee receive-->
               <div class="tab-pane" id="tab_15">

            <?php $form = ActiveForm::begin(); ?> 
            <div class="row">
              <div class="col-md-3">
                <label for="">Year Wise</label>
                <input type="radio" name="dateWiseClassLedger" id="dateWiseYearLedger" checked="checked" value="1">
                <label for="">Date Wise</label>
                <input type="radio" name="dateWiseClassLedger" id="dateWiseClassLedger" value="2">
              </div>
            </div>
        <div class="row">
           <div class="col-sm-9">
                <div class="col-sm-2 fh_item" style="color: black !important;">
                <?php 
                $class_array = ArrayHelper::map(\app\models\RefClass::find()->where(['fk_branch_id'=>Yii::$app->common->getBranch(),'status'=>'active'])->all(), 'class_id', 'title');
                    echo  $form->field($model, 'fk_class_id')->dropDownList($class_array, ['id'=>'class-id','prompt' => 'Select'.' '. Yii::t('app','Class')]);
                
                    ?>
                </div>
                <div class="col-sm-2 fh_item" style="color: black !important;">
                    <?php
                        echo $form->field($model, 'group_id')->widget(DepDrop::classname(), [
                            'options' => ['id'=>'group-id','value'=>1],
                            'pluginOptions'=>[
                            'depends'=>['class-id'],
                            'prompt' => 'Select Group...',
                            'url' => Url::to(['/site/get-group'])
                        ]
                    ])->label('Group');?>
                </div>
                <div class="col-sm-2 fh_item" style="color: black !important;">
                    <?php
                    echo $form->field($model, 'fk_section_id')->widget(DepDrop::classname(), [
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
                <div class="col-md-3" id="yearLedgerFee">
                    <label>Year</label>
                      <select name="fromYear" id="yearlyClassFeeReport" class="form-control" data-url=<?php echo Url::to(['reports/yearly-classwise-feereport'])?>>
                        <option>Select Year</option>
                        <?php
                           '<select name="SelectYear">';
                           $starting_year  =date('Y', strtotime('-2 year'));
                           $ending_year = date('Y', strtotime('+0 year'));
                              for($starting_year; $starting_year <= $ending_year; $starting_year++) {
                                echo '<option value="'.$starting_year.'">'.$starting_year.'</option>';
                              } 
                               ?>
                      </select>
                  </div>
                  <div id="showDateWiseLedger" style="display: none">
                    <div class="col-md-3">
                 <?php 
                  echo '<label>Start Month:</label>';   
                  echo DatePicker::widget([
                    'name' => 'startdate', 
                    'value' => date('Y-m'),
                    'options' => ['placeholder' => ' ','id'=>'startdateFeeLedger'],
                    'pluginOptions' => [
                        'autoclose' => true,
                        'startView'=>'year',
                        'minViewMode'=>'months',
                        'format' => 'yyyy-mm',
                        'startDate' => '-3m',
                    ]
                  ]);?>
               </div>
               <div class="col-md-3">
                 <?php echo '<label>End Month:</label>'; 
                      echo DatePicker::widget([
                    'name' => 'enddate', 
                    //'value' => date('01-m-Y'),
                    'options' => ['placeholder' => ' ','id'=>'enddateFeeLedger','data-url'=>Url::to(['reports/date-fees'])],
                    'pluginOptions' => [
                        'autoclose' => true,
                        'startView'=>'year',
                        'minViewMode'=>'months',
                        'format' => 'yyyy-mm',
                    ]
                  ]);?>

             </div>
                  </div>
                  <div style="height: 23px"></div>
           </div> 
        </div> 
        <div class="row">
                  <div class="col-md-12" id="classwise-fee-report-yearly"></div>
                </div>
        <?php ActiveForm::end(); ?> 
               </div>
              <!--tab 15 yearly class wise fee receive and start of tab_16-->
              <div class="tab-pane" id="tab_16">
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
                                  'name' => 'feesubmission',
                                  'value' => '',
                                  'options' => ['multiple' => false, 'placeholder' => 'Select Student ...','class'=>'classwisestudent','id'=>'previousSlip','data-url'=>Url::to(['reports/previous-slip'])]
                              ]); ?>
                  
                </div>
              </div>
              <br>
              <div class="row">
                <div class="col-sm-12" id="showPreviousSlip"></div>
              </div>
            </div>
              <!--tab 16 yearly class wise fee receive and start of tab_17-->
              <div class="tab-pane" id="tab_17">
              <!--  public function actionDateLedger(){  -->
              
            </div>
              <!--tab 17 yearly class wise fee receive and start of tab_18-->
              
              <!--tab 18 yearly class wise fee receive and start of tab_19-->
              
              <!--tab 19 yearly class wise fee receive and start of tab_20-->
              <div class="tab-pane" id="tab_20">
                <?php
                 $monthly_fee_receive=[]; 
                $year = date('Y');
                $years = [1=>'Jan',2=>'Feb',3=>'Mar',4=>'Apr',5=>'May',6=>'Jun',7=>'Jul',8=>'Aug',9=>'Sep',10=>'Oct',11=>'Nov',12=>'Dec'];
                ?>
                 <table class="table table-bordered">
                  <thead>
                      <?php
                      foreach ($years as $key => $mon) {
                        ?>
                        <th><?=$mon?></th>
                        <?php
                      }
                      ?>
                  </thead>
                  <tbody>
                    <tr>
                      <?php
                      $i=1; 
                      $total=0; 
                      /*monthly fee graph array*/
                     
                      $count_year_available = \app\models\FeeArears::find()->where('YEAR(from_date)='.$year)->count();  
                      if($count_year_available >0){
                          foreach ($years as $key => $month) { 
                              if($key >=1 && $key<=3){
                               // $yearNext = $year+1;
                               $yearNext = $year;
                                // $yearNext = date('Y', strtotime('+1 year', strtotime($year)) );
                                $curr_year = $yearNext;
                                $curr_month = $key;
                                  $year_month = $yearNext  .'-'.sprintf("%02d", $key); 
                              }else{
                                  $curr_year = $year;
                                  $curr_month = $key;
                                  $year_month = $year.'-'.sprintf("%02d", $key); 
                              } 

                              //echo $year_month;die;
                              $query =  \app\models\FeeArears::find()
                              ->select('sum(arears) total_arears')
                              ->where('month(from_date)='.$curr_month.' and year(from_date)='.$curr_year)->asArray()->one();  
                               
                              if($query['total_arears']>0){
                                echo "<td>".$query['total_arears']."</td>";
                              }else{
                                ?>
                                <td>0</td>
                                <?php
                              } 
                          } 
                      } 
                    ?>
                  </tr>
                  </tbody>
                </table>      
              </div>
              <!--tab 20 yearly class wise fee receive and start of tab_21-->
      <div class="tab-pane" id="tab_21">
        <div class="row">
          <div class="col-md-12">
            <a href="<?=Url::to(['reports/today-all-ledger']) ?>" name="Generate Report" class="btn btn-primary pull-right"><i class="fa fa-download"></i> Generate Report</a>
          </div></div>
        <div class="row">
          <div class="col-md-12"> 
      <table class="table table-bordered">
    <thead>
        <tr style="background: #3c8dbc">
        <th>SR.</th>
        <th>Student</th>
        <th>Parent</th>
        <th>Class</th>
        <th>Roll #</th>
        <th>Fee Title</th>
        <th>Fee</th>
        <th>Transport</th>
        <th>Hostel</th>
        </tr>
    </thead>
    <tbody>
      <?php  
      $i=0;
      $total_amount=0;
      $totaltransportAmnt=0;
      $totalhostelAmnt=0;
      foreach ($todayFeeRcv as $key => $todayFee_rcv) {
        $i++;
         $getHead=\app\models\FeeHead::find()->where(['branch_id'=>yii::$app->common->getBranch(),'id'=>$todayFee_rcv['fee_head_id']])->one();
         $total_amount=$total_amount+$todayFee_rcv['head_recv_amount'];
         $totaltransportAmnt=$totaltransportAmnt+$todayFee_rcv['transport_amount'];
          $totalhostelAmnt=$totalhostelAmnt+$todayFee_rcv['hostel_amount'];
       ?>
        <tr>
          <td><?php echo $i; ?></td>
          <td><?= yii::$app->common->getName($todayFee_rcv['user_id']); ?></td>
          <td><?= yii::$app->common->getParentName($todayFee_rcv['stu_id']); ?></td>
          <td><?= yii::$app->common->getCGSName($todayFee_rcv['class_id'],$todayFee_rcv['group_id'],$todayFee_rcv['section_id']); ?></td>
          <td><?= (!empty($todayFee_rcv['roll_no']))? $todayFee_rcv['roll_no'] :'N/A'; ?></td>
          <td><?php echo strtoupper($getHead['title']) ?></td>
          <td>Rs. <?php echo $todayFee_rcv['head_recv_amount']?></td>
          <td>Rs. <?php echo ($todayFee_rcv['transport_amount'])?$todayFee_rcv['transport_amount']:'0'?></td>
          <td>Rs. <?php echo ($todayFee_rcv['hostel_amount'])?$todayFee_rcv['hostel_amount']:'0'?></td>
        </tr>
      <?php } ?>
      <tr>
        <td></td>
        <th colspan="5">Total</th>
        <th>Rs. <?php echo $total_amount ?></th>
        <th>Rs. <?php echo $totaltransportAmnt ?></th>
        <th>Rs. <?php echo $totalhostelAmnt ?></th>
      </tr>
      <tr>
        <td></td>
        <th colspan="5">Grand Total</th>
        <th>Rs. <?php echo $total_amount+$totaltransportAmnt+$totalhostelAmnt?></th>
      </tr>
    </tbody>
  </table>
        </div>
        </div>
        </div>
              <!--tab 21 yearly class wise fee receive and start of tab_22-->
              
              <!-- end of tab 22 start of tab_23-->

            <!-- /.tab-content -->
          </div>
        </div>
      </div>