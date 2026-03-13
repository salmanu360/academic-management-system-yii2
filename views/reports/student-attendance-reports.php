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
$this->title = 'Student Attendance Reports';
 ?>
    <div class="row">
        <div class="col-md-12">
        <a class="btn btn-block btn-social btn-facebook">
                <i class="fa fa-bar-chart-o"></i> Student Attendance Reports
              </a>
        </div>
        </div>
<br>
<div class="row">
        <div class="col-md-12">
          <!-- Custom Tabs -->
          <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
              <li class="active"><a href="#tab_1" data-toggle="tab">Today Attendance</a></li>
              <li><a href="#tab_7" data-toggle="tab">Today Name Attendance</a></li>
              <li><a href="#tab_8" data-toggle="tab">Last 4 days Attendance</a></li>
              <li><a href="#tab_2" data-toggle="tab">Today Class Attendance</a></li>
              <li><a href="#tab_3" data-toggle="tab">Today Group Attendance</a></li>
              <li><a href="#tab_4" data-toggle="tab">Today Section Attendance</a></li>
              <li><a href="#tab_5" data-toggle="tab">OverAll Attendance</a></li>
              <li><a href="#tab_6" data-toggle="tab">Date Wise Attendance</a></li>
              <!-- <li><a href="#tab_9" data-toggle="tab">Yearly Attendance</a></li> -->
            </ul>
            <div class="tab-content">
              <div class="tab-pane active" id="tab_1">
               <?php 
                    $attendance_std_query = \app\models\StudentAttendance::find()
                    ->select(['count(*) as total','student_attendance.leave_type'])
                    ->innerJoin('student_info si','si.stu_id=student_attendance.fk_stu_id')
                    ->where(['si.fk_branch_id'=>Yii::$app->common->getBranch(),'date(student_attendance.date)'=>date('Y-m-d'),'si.is_active'=>1])
                    ->groupBy('student_attendance.leave_type')
                    ->asArray()
                    ->all();
                     ?>

                     <table class="table table-striped">
                      <thead>
                      <tr>
                      <th>Leave Type</th>
                      <th>Total Students</th>
                      </tr>
                      </thead>
                      <tbody>
                      <?php foreach ($attendance_std_query as $overall) { ?>
                      <tr>
                      <td><?php echo ucfirst($overall['leave_type']); ?></td>
                      <td><?php echo $overall['total']; ?></td>
                      </tr>
                      <?php } ?>
                      </tbody>
                      </table>
                      </div>
              <!-- /.tab-pane -->
              <div class="tab-pane" id="tab_2">
                <?php $classwise=yii::$app->db->createCommand("select count(DISTINCT(sa.fk_stu_id)) as total,rc.class_id,rc.title,sa.leave_type from student_attendance sa inner join student_info si on si.stu_id=sa.fk_stu_id inner join ref_class rc on rc.class_id=si.class_id where si.fk_branch_id='".yii::$app->common->getBranch()."' and si.is_active=1 and date(sa.date)='".date("Y-m-d")."' group by rc.class_id,sa.leave_type")->queryAll();
                $class_wise_array=[];
                                $cls='';
                                 foreach ($classwise as $claswise) {
                                 // echo '<pre>';print_r($claswise);die;
                                  $classid= $claswise['class_id'];
                                  $leave_type = $claswise['leave_type'];
                                    if($cls==''){
                                     if(isset($leave_type)){
                                         $class_wise_array[$classid][$leave_type] = $claswise['total'];
                                        }
                                    
                                        $class_wise_array[$claswise['class_id']]['title'] = $claswise['title'];
    
                                    }else if($cls == $classid){
    
                                        if(isset($leave_type)){
                                           $class_wise_array[$classid][$leave_type] = $claswise['total'];
                                        }
                                    
                                        $class_wise_array[$claswise['class_id']]['title'] = $claswise['title'];
                                    }else{
                                      if(isset($leave_type)){
                                          $class_wise_array[$classid][$leave_type] = $claswise['total'];
                                        }
                                    
                                         $class_wise_array[$claswise['class_id']]['title'] = $claswise['title'];
                                        }
    
    
                                    
                                 ?>
    <?php 
                                    $cls=$classid;
    
                                }
                               // echo "<pre>";print_r($class_wise_array);die;
                ?>
                <table class="table table-striped">
                                <thead>
                                 <tr>
                                <th> Class</th>
                                <!-- <th>Leave Type</th> -->
                                <th>Present</th>
                                <th>Absent</th>
                                <th>Leave</th>
                                <th>Late</th>
                               <th>Late With Excuse</th>
                                </tr>
                                </thead>
                                <tbody>
                               <?php  //$sum=0;
                                foreach ($class_wise_array as $displayclass) {
                                  ?>
                                <tr>
                                <td><?php echo $displayclass['title']; ?></td>
                                <td><?php if(isset($displayclass['present'])){
                                      echo  $displayclass['present']; ?>
                                <?php }else{ echo "0";}?> </td>
                                <td><?php if(isset($displayclass['absent'])){
                                    echo  $displayclass['absent'] ?>
                                <?php }else{ echo "0"; }?></td>
                                <td>
                                <?php if(isset($displayclass['leave'])){
                                     echo  $displayclass['leave']; ?>
                                <?php }else{ echo "0"; }?></td>
                                <td><?php if(isset($displayclass['late'])){
                                     echo $displayclass['late']; ?>
                                <?php }else{  echo "0";  }?> </td>
                                <td><?php if(isset($displayclass['Latewithexcuse'])){
                                     echo $displayclass['Latewithexcuse']; ?>
                                <?php }else{  echo "0";  }?> </td>
                                </tr>
                                <?php }
                               //echo $sum;
                              // die;
                                //echo "<pre>";print_r($class_wise_array);die;
                                ?>
                              </tbody>
                              </table>
              </div>
              <!-- /.tab-pane -->
              <div class="tab-pane" id="tab_3">
               <?php $groupwise=yii::$app->db->createCommand("select rc.class_id,rc.title,rg.group_id,rg.title as group_title, count(*) as `no_of_students_in_school`,leave_type from `student_attendance` st inner join student_info si on si.stu_id=st.fk_stu_id inner join ref_class rc on rc.class_id=si.class_id left join ref_group rg on rg.group_id=si.group_id where date(st.date)='".date('Y-m-d')."' and si.fk_branch_id='".yii::$app->common->getBranch()."' AND si.is_active=1 group by rc.class_id,rg.group_id,leave_type")->queryAll();
    
                            $groups_array=[];
                            $groupsVar='';
                            foreach ($groupwise as $grp) {
                                $cls_id=$grp['class_id'];
                                $g_group_type=$grp['leave_type'];
                                //echo 'pre>';print_r($grp);
                                if($groupsVar == ''){
                                    if(isset($g_group_type)){
    
                                         $groups_array[$cls_id][$g_group_type] = $grp['no_of_students_in_school'];
    
                                   }
                                         $groups_array[$grp['class_id']]['title'] = $grp['title'];
                                         $groups_array[$grp['class_id']]['group_title'] = $grp['group_title'];
    
                                }else if($groupsVar == $cls_id){
                                    if(isset($g_group_type)){
                                           $groups_array[$cls_id][$g_group_type] = $grp['no_of_students_in_school'];
                                        }
                                         $groups_array[$grp['class_id']]['title'] = $grp['title'];
                                         $groups_array[$grp['class_id']]['group_title'] = $grp['group_title'];
    
    
                                }else{
                                    if(isset($g_group_type)){
                                          $groups_array[$cls_id][$g_group_type] = $grp['no_of_students_in_school'];
                                        }
                                    
                                         $groups_array[$grp['class_id']]['title'] = $grp['title'];
                                         $groups_array[$grp['class_id']]['group_title'] = $grp['group_title'];
    
                                }
                                
                                $groupsVar=$cls_id;
                            }
                               // echo "<pre>";print_r($groups_array);die;
                            ?>
                            <table class="table table-striped">
                            <thead>
                            <tr>
                            <th>Class</th>
                            <th>Group </th>
                            <!-- <th>No Of student In school</th>
                                                        <th>Leave Type</th> -->
                            <th>Present</th>
                            <th>Absent</th>
                            <th>Leave</th>
                            <th>Late</th>
                            <th>Late With Excuse</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($groups_array as $group) {
                                  //  echo '<pre>';print_r($group);
                                   // continue;
                                    if($group['group_title'] == NULL){
    
                                    }else{
                                 ?>
                              <tr>
                              <td><?php echo $group['title']; ?></td>
                              <td><?php if($group['group_title'] == ''){echo "N/A";}else{
                                                              echo $group['group_title'];
                                                              }
                                                               ?></td>
                              <td><?php if(isset($group['present'])){
                                  echo  $group['present']; ?>
                              <?php }else{
                                     echo "0";
                                     }?>
                              </td>
                              <td>
                              <?php if(isset($group['absent'])){
                                                                  echo  $group['absent']; ?>
                              <?php }else{
                                                                  echo "0";
                                                            }?>
                              </td>
                              <td>
                              <?php if(isset($group['leave'])){
                                                                  echo  $group['leave']; ?>
                              <?php }else{
                                                                  echo "0";
                                                            }?>
                                    </td>
                              <td>
                              <?php if(isset($group['late'])){
                                                                  echo  $group['late']; ?>
                              <?php }else{
                                                                  echo "0";
                                                            }?>
                              </td>
                              <td>
                              <?php if(isset($group['Latewithexcuse'])){
                                     echo $group['Latewithexcuse']; ?>
                                <?php }else{  echo "0";  }?> </td>
                              </tr>
                              <?php } 
                               // die;
                                }
                                ?>
                            </tbody>
                            </table>
                      </div>
              <!-- /.tab-pane -->

               <!-- 
                tab example
               <div class="tab-pane" id="tab_4">
                Lorem Ipsum is simply dummy text of the printing and typesetting industry.
                </div> -->
                <div class="tab-pane" id="tab_4">
                <?php
                $sectionQuery=yii::$app->db->createCommand("select count(DISTINCT(sa.fk_stu_id)) as total,rc.class_id,rc.title,rg.group_id,rg.title as group_title,rs.section_id,rs.title as section_title,sa.leave_type from student_attendance sa 
                        inner join student_info si on si.stu_id=sa.fk_stu_id
                        inner join ref_class rc on rc.class_id=si.class_id 
                        left join ref_group rg on rg.group_id=si.group_id
                        left join ref_section rs on rs.section_id=si.section_id
                        where si.fk_branch_id='".yii::$app->common->getBranch()."' and si.is_active=1 and date(sa.date)='".date("Y-m-d")."' 
                        group by rc.class_id,rc.title,rg.group_id,rg.title,rs.section_id,rs.title,sa.leave_type")->queryAll();
                      $section_array=[];
                    $sectionId="";
                    $classId="";
                    //echo count($sectionQuery);
                    foreach ($sectionQuery as $key=>$section) {
                        $section_leave_type=$section['leave_type'];
                        $class_id= $section['class_id'];
                        $section_id= $section['section_id'];
    
                        if($classId == '' && $sectionId ==''){
                            //echo '1<br/>';
                            if(isset($section_leave_type)){
                             $section_array[$class_id.'_'.$section['section_id']][$section_leave_type] = $section['total'];
                            }
                            $section_array[$section['class_id'].'_'.$section['section_id']]['title'] = $section['title'];
                            $section_array[$section['class_id'].'_'.$section['section_id']]['group_title'] = $section['group_title'];
                            $section_array[$section['class_id'].'_'.$section['section_id']]['section_title'] = $section['section_title'];
                        }
                        else if($classId == $class_id && $sectionId == $section_id ){
                            //echo '2<br/>';
                            if(isset($section_leave_type)){
                                   $section_array[$class_id.'_'.$section['section_id']][$section_leave_type] = $section['total'];
                            }
                            $section_array[$section['class_id'].'_'.$section['section_id']]['title'] = $section['title'];
                            $section_array[$section['class_id'].'_'.$section['section_id']]['group_title'] = $section['group_title'];
                            $section_array[$section['class_id'].'_'.$section['section_id']]['section_title'] = $section['section_title'];
                        }else{
                            //echo '3<br/>';
                            if(isset($section_leave_type)){
                                $section_array[$class_id.'_'.$section['section_id']][$section_leave_type] = $section['total'];
                            }
                            $section_array[$section['class_id'].'_'.$section['section_id']]['title'] = $section['title'];
                            $section_array[$section['class_id'].'_'.$section['section_id']]['group_title'] = $section['group_title'];
                            $section_array[$section['class_id'].'_'.$section['section_id']]['section_title'] = $section['section_title'];
                        }
    
                       //echo $classId."---".$class_id.'----'.$sectionId."<br/>";
    
                        $classId    = $section['class_id'];
                        $sectionId  = $section['section_id'];
    
                     }
    
                   // die; 
                    // echo '<pre>';print_r($section_array);die;
                     ?>
                     <table class="table table-striped">
                      <thead>
                      <tr>
                      <th>Class</th>
                      <th>Group</th>
                      <th>Section</th>
                      <th>Present</th>
                      <th>Absent</th>
                      <th>Leave</th>
                      <th>Late</th>
                      <th>Late With Excuse</th>
                      </tr>
                      </thead>
                      <tbody>
                      <?php foreach ($section_array as $sections) {
                                                     // echo $sections['present'];
                      
                                                     // echo '<pre>';print_r($sections);
                                                     // continue;
                                                      
                                                   ?>
                      <tr>
                      <td> <?php echo $sections['title']; ?></td>
                      <td><?php if($sections['group_title'] == ''){echo "N/A";}else{
                          echo $sections['group_title'];
                          }
                          ?>
                      </td>
                      <td>
                      <?php if($sections['section_title'] == ''){echo "N/A";}else{
                             echo $sections['section_title'];
                             }
                             ?> </td>
                      <td><?php if(isset($sections['present'])){
                          echo  $sections['present']; ?>
                      <?php }else{echo "0";}?> </td>
                      <td><?php if(isset($sections['absent'])){
                            echo  $sections['absent']; ?>
                      <?php }else{ echo "0";}?></td>
                      <td>
                      <?php if(isset($sections['leave'])){
                             echo  $sections['leave']; ?>
                      <?php }else{
                             echo "0";
                             }?>
                      </td>
                      <td><?php if(isset($sections['late'])){
                            echo $sections['late']; ?>
                      <?php }else{  echo "0";  }?> </td>
                      <td>
                              <?php if(isset($sections['Latewithexcuse'])){
                                     echo $sections['Latewithexcuse']; ?>
                                <?php }else{  echo "0";  }?> </td>
                      </tr>
                      <?php  } ?>
                      </tbody>
                      </table>
                </div>

              <!-- /.tab-pane -->

              <div class="tab-pane" id="tab_5">
              <div class="row">
                <div class="col-md-3 col-sm-3">
                 <label for="class">Class</label>
                  <?= Html::dropDownList('ref_class', null,
                      ArrayHelper::map(RefClass::find()->where(['fk_branch_id'=>yii::$app->common->getBranch(),'status'=>'active'])->all(), 'class_id', 'title'),['prompt'=>'Select Class','class'=>'form-control','id'=>'getStuClassWiseStu','data-url'=>Url::to(['reports/get-stu-classwise-stu'])]) ?>
                </div>
                 <div class="col-md-3 showStu" style="display: none">
                            <?php echo Html::label('Student');?>
                              <?php
                                echo Select2::widget([
                                  'name' => 'state_2',
                                  'value' => '',
                                  //'data' => $stuQuery,
                                  'options' => ['multiple' => false, 'placeholder' => 'Select states ...','class'=>'studnts','data-url'=>Url::to(['reports/show-stu-data-stu'])]
                              ]);
                              ?>
                        </div>
              </div>
              <br />
              <div class="row">
                <div class="col-md-12">
                  <div class="attendance"></div>
                </div>
              </div>
                </div>
                <!-- /.tab-pane -->
              <div class="tab-pane" id="tab_6">
               <div class="row">
                <div class="col-md-6 inp-lst">
                    <label> <input type="radio" name="overall" id="overallAtt" checked="checked" /> Date Range </label>
                    <label> <input type="radio" name="overall" id="other" /> Date Range With Class </label>
                </div>
              </div>
              <div class="row">
            <div class="showDate">
            <div class="col-md-3">
            <?php 
                                             echo '<label>Start Date:</label>';
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
                                                  ]);
                                          ?>
            </div>
            </div>
            <!-- end of class --> 
            <!-- testing -->
            <div class="col-sm-12 actifr_r" style="display: none" id="displayclasses">
            <?php $form = ActiveForm::begin(); ?>
            <div class="row">
            <div class="col-sm-3">
            <?php
             $class_array = ArrayHelper::map(\app\models\RefClass::find()->where(['fk_branch_id'=>Yii::$app->common->getBranch(),'status'=>'active'])->all(), 'class_id', 'title'); 
                         ?>
            <?= $form->field($model, 'class_id')->dropDownList($class_array, ['id'=>'class-id','prompt' => 'Select Class ...']); ?>
            </div>
            <div class="col-sm-3">
            <?php
                // Dependent Dropdown
                echo $form->field($model, 'group_id')->widget(DepDrop::classname(), [
                    'options' => ['id'=>'group-id'],
                    'pluginOptions'=>[
                        'depends'=>['class-id'],
                        'prompt' => 'Select Group...',
                        'url' => Url::to(['/site/get-group'])
                    ]
                ]);
            ?>
            </div>
            <div class="col-sm-3">
            <?php           
                    // Dependent Dropdown
                    echo $form->field($model, 'section_id')->widget(DepDrop::classname(), [
                        'options' => ['id'=>'section-ids'],
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
            <?php ActiveForm::end(); ?>
            <input type="submit" name="submits" id="submitcls" class="submitcls btn btn-primary" data-url="<?php echo Url::to(['reports/show-cls'])?>" style="display: none;margin-top:28px;"  />
            </div>
            <div id="subject-inner">
            </div>
            <!-- end of testing --> 
            <br>
            <div class="col-md-2">
            <input type="submit" name="submit" class="submitAttendance btn btn-primary" data-url="<?php echo Url::to(['reports/show-overall'])?>" />
            </div>
            </div>
             <div class="row">
            <div id="overalls">
            </div>
            <br />
            <div id="overallsCls">
            </div>
            <div id="overallsGrps">
            </div>
            </div>
                </div>
                <!-- end of tab6 and start of tab 7 -->
              <div class="tab-pane" id="tab_7">
               <a href="<?php echo Url::to(['reports/student-attendance-report-pdf']) ?>" class="btn btn-primary pull-right">Generate Report</a>
                     <table class="table table-striped">
                      <thead>
                      <tr class="info">
                      <th>SR.</th>
                      <th>Student</th>
                      <th>Parent</th>
                      <th>Parent Contact</th>
                      <th>Class</th>
                      <th>Group</th>
                      <th>Section</th>
                      <th>Leave Type</th>
                      </tr>
                      </thead>
                      <tbody>
                      <?php 
                      $i=0;
                      foreach ($nameAttendance as $nameStudent) {
                        $i++;
                      $studentId=\app\models\StudentInfo::find()->where(['stu_id'=>$nameStudent->fk_stu_id])->one();
                      $parentTable=\app\models\StudentParentsInfo::find()->where(['stu_id'=>$nameStudent->fk_stu_id])->one();
                       ?>
                      <tr>
                      <td><?= $i; ?></td>
                      <td><?php echo Yii::$app->common->getName($studentId->user_id);?></td>
                      <td><?php echo Yii::$app->common->getParentName($nameStudent->fk_stu_id);?></td>
                      <td><?php echo $parentTable->contact_no?></td>
                      <td><?php echo $studentId->class->title; ?></td>
                      <td><?php echo (!empty($studentId->group->title))? $studentId->group->title :'N/A'; ?></td>
                      <td><?php echo (!empty($studentId->section->title)) ? $studentId->section->title : 'N/A'; ?></td>
                      <td><?php echo ucfirst($nameStudent->leave_type); ?></td>
                      
                      </tr>
                      <?php } ?>
                      </tbody>
                      </table>
                      </div>
                <!-- end of tab7 and start of tab 8 -->
                <div class="tab-pane" id="tab_8">
               <a href="<?php echo Url::to(['reports/last-four-days-pdf']) ?>" class="btn btn-primary pull-right">Generate Report</a>
                     <table class="table table-striped">
                      <thead>
                      <tr class="info">
                      <th>SR.</th>
                      <th>Student</th>
                      <th>Parent</th>
                      <th>Parent Contact</th>
                      <th>Class</th>
                      <th>Group</th>
                      <th>Section</th>
                      <th>Leave Type</th>
                      <th>Date</th>
                      </tr>
                      </thead>
                      <tbody>
                      <?php 
                      $i=0;
                      //echo '<pre>';print_r($beforeAttendance);die();
                      foreach ($beforeAttendance as $sevenDays) {
                        $i++;
                      $studentId=\app\models\StudentInfo::find()->where(['stu_id'=>$sevenDays['fk_stu_id']])->one();
                      $parentTable=\app\models\StudentParentsInfo::find()->where(['stu_id'=>$sevenDays['fk_stu_id']])->one();
                       ?>
                      <tr>
                      <td><?= $i; ?></td>
                      <td><?php echo Yii::$app->common->getName($studentId->user_id);?></td>
                      <td><?php echo Yii::$app->common->getParentName($sevenDays['fk_stu_id']);?></td>
                      <td><?php echo $parentTable->contact_no?></td>
                      <td><?php echo $studentId->class->title; ?></td>
                      <td><?php echo (!empty($studentId->group->title))? $studentId->group->title :'N/A'; ?></td>
                      <td><?php echo (!empty($studentId->section->title)) ? $studentId->section->title : 'N/A'; ?></td>
                      <td><?php echo ucfirst($sevenDays['leave_type']); ?></td>
                      <td><?php echo $sevenDays['date']; ?></td>
                      </tr>
                      <?php } ?>
                      </tbody>
                      </table>
                      </div>
                <!-- end of tab 8 and start of tab 9-->
                <div class="tab-pane" id="tab_9">
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
            <div class="col-md-3">
                        <?php
                        $month = [1=>'Jan',2=>'Feb',3=>'Mar',4=>'Apr',5=>'May',6=>'Jun',7=>'Jul',8=>'Aug',9=>'Sep',10=>'Oct',11=>'Nov',12=>'Dec'];
                        echo '<label>Month</label>';
                        echo Html::dropDownList('ref_class', null,$month,['prompt'=>'Select Month','class'=>'form-control']) ?>
                  </div>
            </div>
                </div>
                <!-- end of tab 9 and start of tab 10--> 
                 <!-- /.tab-pane -->
            </div>
            <!-- /.tab-content -->
          </div>
          <!-- nav-tabs-custom -->
        </div>
        <!-- /.col -->
        <!-- /.col -->
      </div>