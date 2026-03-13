<?php
use yii\helpers\Url;?>
<style type="text/css">
*{ margin:0; padding:0;}
th, tr, td  {
  border:1px solid black;
  padding:8px;
  font-size:1em;
}

tr:nth-child(even){background-color: #f2f2f2}
</style>
<div style="width: 100%; text-align: center; background-color: #337ab7; color: #000; font-size:14px;">
  <h2 style="font-size:16px; font-weight:700; color:#000000; text-transform:capitalize;margin: 0;padding: 15px 0 8px 0;">
    <?=Yii::$app->common->getBranchDetail()->address?>
  </h2>
</div>
<h4 style="color:red;text-align: center;">Quiz Results of <?= Yii::$app->common->getName($student_details->user_id) .', Class ( ' .Yii::$app->common->getcgsName($student_details->class_id,$student_details->group_id,$student_details->section_id);?> ) </h4>
<div class="table-responsive">
  <table class="table table-striped" width="100%">
    <thead>
      <tr style="background: #45983b;color:white">
        <td>Sr.</td>
        <td>Subject</td>
        <td>Teacher</td>
        <td>Total Marks</td>
        <td>Passing Marks</td>
        <td>Obtained Marks</td>
        <td>Remarks</td>
        <td>Date</td>
      </tr>
    </thead>
    <tbody>
      <?php
      $i=1; 
      foreach ($quizResults as $key => $quizResultsvalue) {
        $subject_details=\app\models\Subjects::find()->where(['id'=>$quizResultsvalue['subject_id']])->one();
        $employee = \app\models\EmployeeInfo::find()->where(['fk_branch_id'=>Yii::$app->common->getBranch(),'emp_id'=>$quizResultsvalue['teacher_id']])->one();
        ?>
        <tr>
          <td><?=$i ?></td>
          <td><?= strtoupper($subject_details['title'])  ?></td>
          <td><?= Yii::$app->common->getName($employee['user_id'])  ?></td>
          <td><?= $quizResultsvalue['total_marks']  ?></td>
          <td><?= $quizResultsvalue['passing_marks']  ?></td>
           <td><?php if($quizResultsvalue['obtained_marks'] < $quizResultsvalue['passing_marks']){echo '<span style="color:red;border:1px solid red">'.$quizResultsvalue['obtained_marks'].'</span>';}else{echo $quizResultsvalue['obtained_marks'];}  ?></td>
          <td><?= $quizResultsvalue['remarks']  ?></td>
          <td><?= date('d M Y',strtotime($quizResultsvalue['quiz_date']))  ?></td>
        </tr>
        <?php $i++; } ?>
      </tbody>
    </table>
  </div>