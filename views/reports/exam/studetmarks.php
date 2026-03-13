    <?php 
use yii\helpers\Url;
$studentinfo=\app\models\StudentInfo::find()->where(['stu_id'=>$stu_id])->one();
if(isset($_GET['e_id'])){?>
<style type="text/css">
  *{ margin-left:0; padding:0;}
  th, tr, td  {
    border:1px solid black;
    padding:10px;
    font-size:1em;
  }

  tr:nth-child(even){background-color: #f2f2f2}
</style>
<div style="width: 100%; text-align: center; background-color: #337ab7; color: #000; font-size:14px;">
  <h2 style="font-size:16px; font-weight:700; color:#000000; text-transform:capitalize;margin: 0;padding: 15px 0 8px 0;">
    <?=Yii::$app->common->getBranchDetail()->address?>
  </h2>
</div>
<h3 style="text-align: center;"><?php echo ucfirst($examName->fkExamType->type) ?> Marks Sheet of <?php echo yii::$app->common->getName($studentinfo->user_id) .' S/D/O '. yii::$app->common->getParentName($stu_id)  ?></h3>
  <?php }else{
     ?>
     
    <a href="<?php echo Url::to(['studentmarks-against-exam','e_id'=>$id,'s_id'=>$stu_id,'class_id'=>$class_id,'g_id'=>$group_id,'section_id'=>$section_id]) ?>" name="Generate Report" class="btn btn-primary pull-right"><i class="fa fa-download" aria-hidden="true"></i> Generate Report</a>

    <span style="color:green"><?php echo ucfirst($examName->fkExamType->type) ?> Marks Sheet of <?php echo yii::$app->common->getName($studentinfo->user_id) .' S/D/O '. yii::$app->common->getParentName($stu_id) .' ( '.yii::$app->common->getCGSName($class_id,$group_id,$section_id) .')';  ?></span>
    <?php } ?>

    <table class="table table-bordered">
      <thead>
         <tr class="success">
          <th>SR</th>
          <th>Subject</th>
          <th>Total Marks</th>
          <th>Passing Marks</th>
          <th>Marks Obtained</th>
          <th>Remarks</th>
          <th>Date</th>
          </tr>
       </thead>
       <tbody>
        <?php 
        $i=0;
        $total=0;
        $totalMarks=0;
        $passingMarks=0;
        foreach ($examData as $examArray) {
         $studentMarks=\app\models\StudentMarks::find()->where(['fk_student_id'=>$stu_id,'fk_exam_id'=>$examArray->id])->one();
          if($studentMarks['marks_obtained'] != 0){
          $total=$total+$studentMarks->marks_obtained;
          $totalMarks=$totalMarks+$examArray->total_marks;
          $passingMarks=$passingMarks+$examArray->passing_marks;
          $i++;
          ?>
          <tr>
          <th><?=$i; ?></th>
         <th><?= $examArray->fkSubject->title ?></th>
          <th><?= $examArray->total_marks ?></th>
          <th><?= $examArray->passing_marks ?></th>
          <th><?= $studentMarks['marks_obtained'] ?></th>
          <th><?= $studentMarks['remarks'] ?></th>
          <th><?= $examArray->start_date ?></th>
        </tr>
       <?php }else{
        $error=1;
        } }?>
        <tr>
          <th colspan="2">Total Marks</th>
          <th><?php echo $totalMarks; ?></th>
          <th><?php echo $passingMarks; ?></th>
          <th><?php echo $total; ?></th>    
          <td></td>
          <td></td>
        </tr>
       </tbody>
    </table>