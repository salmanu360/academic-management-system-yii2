 <?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\widgets\LinkPager;
use kartik\export\ExportMenu;
use yii\widgets\Pjax;
use app\models\User;
use app\models\EmployeeAttendance;
use app\models\EmployeeInfo;
use kartik\date\DatePicker;
$this->title = 'Employee Attendance Report';
?>
<div class="row">
        <div class="col-md-11">
        <a class="btn btn-block btn-social btn-facebook">
                <i class="fa fa-bar-chart-o"></i> Employee Attendance Reports
              </a>
        </div>
        </div>
<br />
 <div class="row">
        <div class="col-md-12">
          <!-- Custom Tabs -->
          <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
              <li class="active"><a href="#tab_1" data-toggle="tab">Today Attendance</a></li>
              <!-- <li><a href="#tab_2" data-toggle="tab">OverAll Staff Attendance</a></li> -->
              <li><a href="#tab_3" data-toggle="tab">Current Month Attendance</a></li>
              <li><a href="#tab_4" data-toggle="tab">Date Wise Attendance</a></li>
              </ul>
            <div class="tab-content">
            <div class="tab-pane active" id="tab_1">
              <a href="<?=\yii\helpers\Url::to(['empl-attnd-report-pdf']) ?>" name="Generate Report" class="btn btn-success pull-right"><i class="fa fa-download"></i> Generate Report</a>
            <table class="table no-margin table-striped">
                  <thead>
                  <tr>
                    <th>Name</th>
                    <th>Attendance</th>
                  </tr>
                  </thead>
                  <tbody>
                  <?php 
                    $getATendance=EmployeeAttendance::find()->where(['date'=>date('Y-m-d')])->all();
                  //  echo '<pre>';print_r($getATendance);die;
                    foreach ($getATendance as $getATendance) {
                    ?>
                  <tr>
                    <td><a target="_blank" href="<?php echo Url::to(['employee/view','id'=>$getATendance->fk_empl_id])?>">
                    <?php
                     $employeeInfo=EmployeeInfo::find()->where(['emp_id'=>$getATendance->fk_empl_id])->one();
                     echo Yii::$app->common->getName($employeeInfo->user_id);?>
                        
                    </a></td>
                    <td><?php echo $getATendance['leave_type'] ?></td>
                    </tr>
                    <?php } ?>
                    </tbody>
                    </table>
            </div>
              <div class="tab-pane" id="tab_2">
              
       
         <a style="margin-top: -18px;" class="btn btn-success pull-right" href="<?= Url::to(['employee/employee-attendance-report-pdf'])?>"><i class="fa fa-download"></i> Generate Report</a> 

<?php Pjax::begin(['id' => 'pjax-container']) ?> <!-- ajax -->  
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
    <?= \kartik\grid\GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

             [
            'label'=>'Full Name',
            'value'=>function($data){
             return Yii::$app->common->getName($data->user_id);
            }

            ],
            [
                'label'=>'Present',
                'value' =>function($data){
                   $query = \app\models\AttendanceMain::find()->where(['fk_user_id'=>$data->user_id])->all();
                   $query = \app\models\EmployeeAttendance::find()->where(['fk_empl_id'=>$data->emp_id,'leave_type'=>'present']);

                    //print_r($query);
                    
                   
                    /*if type is year mont that follwoing query will be executed*/
                    //if($attendance_type == 'year-month'){
                    //    $query->andWhere(['=','DATE_FORMAT(date, "%Y-%m")',$year_month]);
                   // };

                    $presentCount = $query->count();
                    return $presentCount;
                }
            ],
            [
                'label'=>'Leave',
                'value' =>function($data){
                    $query = \app\models\EmployeeAttendance::find()->where(['fk_empl_id'=>$data->emp_id,'leave_type'=>'leave']);

                    /*if type is year mont that follwoing query will be executed*/
                    //if($attendance_type == 'year-month'){
                    //    $query->andWhere(['=','DATE_FORMAT(date, "%Y-%m")',$year_month]);
                   // };

                    $presentCount = $query->count();
                    return $presentCount;
                }
            ],
            [
                'label'=>'Late',
                'value' =>function($data){
                    $query = \app\models\EmployeeAttendance::find()->where(['fk_empl_id'=>$data->emp_id,'leave_type'=>'late']);
                    $presentCount = $query->count();
                    return $presentCount;
                }
            ],
            [
                'label'=>'Late With Excuse',
                'value' =>function($data){
                    $query = \app\models\EmployeeAttendance::find()->where(['fk_empl_id'=>$data->emp_id,'leave_type'=>'Latewithexcuse']);
                    $excuseCount = $query->count();
                    return $excuseCount;
                }
            ],
            [
                'label'=>'Absent',
                'value' =>function($data){
                    $query = \app\models\EmployeeAttendance::find()->where(['fk_empl_id'=>$data->emp_id,'leave_type'=>'absent']);
                    $presentCount = $query->count();
                    return $presentCount;
                }
            ],
            /*[
                'label'=>'Total Present %',
                'value' =>function($data){
                    $query = \app\models\EmployeeAttendance::find()->where(['fk_empl_id'=>$data->emp_id,'leave_type'=>'present']);
                     if($query->count()> 0){
                     $query1 = \app\models\EmployeeAttendance::find()->where(['fk_empl_id'=>$data->emp_id,'leave_type'=>'present'])->one();
                     $datemonth=date('m',strtotime($query1->date));
                     $number = cal_days_in_month(CAL_GREGORIAN, $datemonth, 2018);
                     $presnt= $query->count()/$number*100 .'%';
                     return number_format((float)$presnt, 2);
                     }else{
                        return '0';
                     }
                }
            ],
             [
                'label'=>'Total Leave %',
                'value' =>function($data){
                    $query = \app\models\EmployeeAttendance::find()->where(['fk_empl_id'=>$data->emp_id,'leave_type'=>'leave']);
                     if($query->count()> 0){
                     $query1 = \app\models\EmployeeAttendance::find()->where(['fk_empl_id'=>$data->emp_id,'leave_type'=>'leave'])->one();
                     $datemonth=date('m',strtotime($query1->date));
                     $number = cal_days_in_month(CAL_GREGORIAN, $datemonth, 2018);
                     $lev= $query->count()/$number*100;
                     return number_format((float)$lev, 2);
                     }else{
                        return '0';
                     }
                }
            ],
             [
                'label'=>'Total Late%',
                'value' =>function($data){
                    $query = \app\models\EmployeeAttendance::find()->where(['fk_empl_id'=>$data->emp_id,'leave_type'=>'late']);

                    if($query->count()> 0){
                     $query1 = \app\models\EmployeeAttendance::find()->where(['fk_empl_id'=>$data->emp_id,'leave_type'=>'late'])->one();
                     $datemonth=date('m',strtotime($query1->date));
                     $number = cal_days_in_month(CAL_GREGORIAN, $datemonth, 2018);
                     $shrtl= $query->count()/$number*100;
                     return number_format((float)$shrtl, 2);
                     }else{
                        return '0';
                     }
                }
            ],
            [
                'label'=>'Total Absent %',
                'value' =>function($data){
                    $query = \app\models\EmployeeAttendance::find()->where(['fk_empl_id'=>$data->emp_id,'leave_type'=>'absent']);
                
                     if($query->count()> 0){
                     $query1 = \app\models\EmployeeAttendance::find()->where(['fk_empl_id'=>$data->emp_id,'leave_type'=>'absent'])->one();
                     $datemonth=date('m',strtotime($query1->date));
                     $number = cal_days_in_month(CAL_GREGORIAN, $datemonth, 2018);
                    $absnt= $query->count()/$number*100 .' %';
                     return number_format((float)$absnt, 2);
                      
                     }else{
                        return '0';
                     }
                    
                    
                }
            ],*/

             /*[
                'header'=>'Actions',
                'class' => 'yii\grid\ActionColumn',
                'template' => "{view}",
                'buttons' => [
                    'addEducation'=>function($url, $model, $key){
                        return Html::a('<span class="glyphicon glyphicon-education toltip" data-placement="bottom" width="20"  title="Add Student Education"></span>', ['education/create','id'=>$key]);
                    },
                    'view' => function ($url, $model, $key)
                    {
                        return Html::a('<span class="glyphicon glyphicon-eye-open toltip" data-placement="bottom" width="20"  title="View Student"></span>', ['employee/view','id'=>$key]);
                    },
                    'update' => function ($url, $model, $key)
                    {
                        return Html::a('<span class="glyphicon glyphicon-pencil toltip" data-placement="bottom" width="20"  title="Update Student"></span>',Url::to(['employee/update','id'=>$key]));
                    },
                   
                    'delete' => function ($url, $model, $key)
                    {

                        return Html::a(Yii::t('yii', '<span class="glyphicon glyphicon-trash toltip" data-placement="bottom" width="20" title="In Active Employee"></span>'), 'update-status/'.$model->emp_id.'', [
                            'title' => Yii::t('yii', 'update-status'),
                            'aria-label' => Yii::t('yii', 'update-status'),
                            'onclick' => "
                                if (confirm('Are You Sure You Want To In active this Employee...?')) {
                                    $.ajax('$url', {
                                        type: 'POST'
                                    }).done(function(data) {
                                        $.pjax.reload({container: '#pjax-container'});
                                    });
                                }
                                return false;
                            ",
                        ]);

                    },
                     'pdf'=>function($url, $model, $key){
                        return Html::a('<span class="glyphicon glyphicon-file" data-placement="bottom" width="20"  title="Generate pdf"></span>', ['employee/create-mpdf','id'=>$key]);
                    },

                ],
            ],*/
        ],
    ]); ?>
     <?php Pjax::end() ?>   <!-- end of ajax -->
    
     </div>
              <!-- /.tab-pane -->
               <div class="tab-pane" id="tab_3">
                <a style="margin-top: -18px;" class="btn btn-success pull-right" href="<?= Url::to(['employee/monthly-attendance-report-pdf'])?>"><i class="fa fa-download"></i> Generate Report</a>
                <table class="table no-margin table-striped">
                  <thead>
                  <tr>
                    <th>Name</th>
                    <th>Working Days</th>
                    <th>Total Present</th>
                    <th>Total Absent</th>
                    <th>Total Leave</th>
                    <th>Total Late</th>
                    <th>Total Late With Excuse</th>
                  </tr>
                  </thead>
                  <tbody>
                  <?php 
                  $GetStaff = User::find()
            ->select(['employee_info.emp_id',"concat(user.first_name, ' ' ,  user.last_name) as name",'user.id as user_id'])
            ->innerJoin('employee_info','employee_info.user_id = user.id')
            ->where(['user.status'=>'active'])->asArray()->all();
            foreach ($GetStaff as $staf){
             $countTotalMonthAttendance=yii::$app->db->createCommand("SELECT DISTINCT(date) FROM employee_attendance WHERE MONTH(date) = MONTH(CURRENT_DATE())")->queryAll();
             $present=yii::$app->db->createCommand("SELECT * FROM employee_attendance WHERE MONTH(date) = MONTH(CURRENT_DATE()) and leave_type='present' and fk_empl_id=".$staf['emp_id'])->queryAll();
             $leave=yii::$app->db->createCommand("SELECT * FROM employee_attendance WHERE MONTH(date) = MONTH(CURRENT_DATE()) and leave_type='leave' and fk_empl_id=".$staf['emp_id'])->queryAll();
             $absent=yii::$app->db->createCommand("SELECT * FROM employee_attendance WHERE MONTH(date) = MONTH(CURRENT_DATE()) and leave_type='absent' and fk_empl_id=".$staf['emp_id'])->queryAll();
             $late=yii::$app->db->createCommand("SELECT * FROM employee_attendance WHERE MONTH(date) = MONTH(CURRENT_DATE()) and leave_type='late' and fk_empl_id=".$staf['emp_id'])->queryAll();
             $Latewithexcuse=yii::$app->db->createCommand("SELECT * FROM employee_attendance WHERE MONTH(date) = MONTH(CURRENT_DATE()) and leave_type='Latewithexcuse' and fk_empl_id=".$staf['emp_id'])->queryAll();
              ?>
                  <tr>
                    <td><a target="_blank" href="<?php echo Url::to(['employee/view','id'=>$staf['emp_id']])?>"><?php echo strtoupper($staf['name']); ?></a></td>
                    <td><?php echo count($countTotalMonthAttendance); ?></td>
                    <td><span class="label label-success"><?php echo count($present); ?></span></td>
                    <td><span class="label label-danger"><?php echo count($absent); ?></span></td>
                    <td>
                      <div><span class="label label-warning"><?php echo count($leave); ?></span></div>
                    </td>
                    <td>
                      <div><span class="label label-info"><?php echo count($late); ?></span></div>
                    </td>
                    <td>
                      <div><span class="label label-danger"><?php echo count($Latewithexcuse); ?></span></div>
                    </td>
                  </tr>
                  <?php } ?>
                  </tbody>
                </table>
              </div>
               <div class="tab-pane" id="tab_4">
                <div class="row">
                  <div class="col-md-3">
                 <?php 
                  echo '<label>Select Month:</label>';   
                  echo DatePicker::widget([
                    'name' => 'startdate', 
                    'value' => date('Y-m'),
                    'options' => ['placeholder' => ' ','id'=>'startdateReceivablee'],
                    'pluginOptions' => [
                        'autoclose' => true,
                        'startView'=>'year',
                        'minViewMode'=>'months',
                        'format' => 'yyyy-mm',
                        'startDate' => '-1m',
                    ]
                  ]);?>
               </div>
                  
                        <div class="col-md-1">
                          <div style="height: 23px"></div>
                          <input type="submit" id="dateReceivableReport" name="submits" class="btn btn-primary" data-url=<?php echo Url::to(['employee/date-attendance']) ?>  />
                        </div>
                </div>
                <br />
                      <div class="row">
                        <div class="col-md-12" id="showDateReceivable"></div>
                      </div>
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
