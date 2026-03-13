<?php 
use app\models\EmployeeAttendance;
use app\models\EmployeeInfo;
use yii\helpers\Url;
 ?>
<style type="text/css">
  *{ margin-left:0; padding:0;}
  th, tr, td  {
    border:1px solid black;
    padding:10px;
    font-size:1.5em;
  }

  tr:nth-child(even){background-color: #f2f2f2}
</style>
 <div style="width: 100%; text-align: center; background-color: #337ab7; color: #000; font-size:14px;">
  <h2 style="font-size:16px; font-weight:700; color:#000000; text-transform:capitalize;margin: 0;padding: 15px 0 8px 0;">
    <?=Yii::$app->common->getBranchDetail()->address?>
  </h2>
</div>
                    <table class="table no-margin table-striped">
                      <thead>
                        <tr>
                          <th>Name</th>
                          <th>Attendance</th>
                          <th>Date</th>
                          <th>Time</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php 
                        $getATendance=EmployeeAttendance::find()->where(['date'=>date('Y-m-d')])->all();
                  //  echo '<pre>';print_r($getATendance);die;
                        foreach ($getATendance as $getATendance) {
                          ?>
                          <tr>
                            <td>
                              <?php
                              $employeeInfo=EmployeeInfo::find()->where(['emp_id'=>$getATendance->fk_empl_id])->one();
                              echo Yii::$app->common->getName($employeeInfo->user_id);?>
                            </td>
                            <td><?php echo $getATendance['leave_type'] ?></td>
                            <td><?php echo date('d M Y',strtotime($getATendance['date'])) ?></td>
                            <td><?php echo $getATendance['time'] ?></td>
                          </tr>
                          <?php } ?>
                        </tbody>
                      </table>