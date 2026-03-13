  <style type="text/css">
  *{ margin:0; padding:0;}
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
<h3 style='text-align:center'>Attendance Report of <?= date('d M Y') ?></h3>
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