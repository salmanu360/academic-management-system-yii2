<?php 
use app\models\EmployeeAttendance;
use app\models\User;
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
tr.noborder td{border:0;}
</style>
<div style="width: 100%; text-align: center; background-color: #337ab7; color: #000; font-size:14px;">
  <h2 style="font-size:16px; font-weight:700; color:#000000; text-transform:capitalize;margin: 0;padding: 15px 0 8px 0;">
    <?=Yii::$app->common->getBranchDetail()->address?>
  </h2> 
</div>
<h3 style='text-align:center'> Employee Attendance Report(<?php echo date('M Y') ?>)
</h3>
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
      <td><?php echo strtoupper($staf['name']); ?></td>
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
<br>
<br>
<br>
<div style="float: right;">Signature__________________</div>
