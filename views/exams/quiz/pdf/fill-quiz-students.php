<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;?>
<style type="text/css">
  *{ margin:0; padding:0;}
  th, tr, td  {
    border:1px solid black;
    padding:10px;
    font-size:1em;
  }

  tr:nth-child(even){background-color: #f2f2f2}
  
   
    tr.noborder td {
  border: 0;
}

</style>
<div style="width: 100%; text-align: center; background-color: #337ab7; color: #000; font-size:14px;">
  <h2 style="font-size:16px; font-weight:700; color:#000000; text-transform:capitalize;margin: 0;padding: 15px 0 8px 0;">
    <?=Yii::$app->common->getBranchDetail()->address?>
  </h2>
</div>
<h4 align="center">Award List of <?php echo strtoupper($class_details->title)?></h4>
<div style="margin-left:10px">
    <p><span>Subject: <?= strtoupper($subject_name->title)  ?> </span>&nbsp;&nbsp;&nbsp;&nbsp;<span>: </span>&nbsp;&nbsp;&nbsp;&nbsp;<span>Date: <?php echo $model2->quiz_date?></span> &nbsp;&nbsp;&nbsp;<span>Teacher: <?php echo Yii::$app->common->getName($employeeDetails->user_id) ?> </span></p>
    <p>
    <span>Total Marks: <?= $model2->total_marks ?> </span>&nbsp;&nbsp;&nbsp;&nbsp;<span>Passing Marks: <?= $model2->passing_marks?> </span></p>
</div>
<div class="table-responsive">
    <?php $form = ActiveForm::begin(['id' => 'award-list-form', 'action' => Url::to(['exams/save-quiz'])]); ?>
        <table class="table table-striped" width="100%">
            <thead>
            <tr>
                <th>#</th>
                <th>Registration No.</th>
                <th>Roll No</th>
                <th>Student</th>
                <th>Parent Name</th>
                <th>Obtained Marks</th>
                <th>Remarks</th>
            </tr>
            </thead>
            <tbody>
                <?php
                $i=1;
                foreach ($dataprovider as $key=>$data){
                    $current = \app\models\ExamQuiz::find()->where(['test_id'=>$model2->id,'stu_id'=>$data['stu_id']])->One();
                    $student = \app\models\StudentInfo::findOne($data['stu_id']);
                ?>
                <tr>
                    <td><?=$i?></td>
                    <td>
                        <?=$student->user->username;?>
                    </td>
                    <td> <?=(!empty($student->roll_no)?$student->roll_no : 'N/A');?></td>
                    <td>
                        <?=$student->user->first_name.' '.$student->user->last_name;?>
                        <?=$form->field($model, 'stu_id['.$key.']')->hiddenInput(['value'=>$data['stu_id']])->label(false)?>
                        <?=$form->field($model, 'fk_class_id')->hiddenInput(['value'=>$class_id])->label(false)?>
                        <?=$form->field($model, 'fk_group_id')->hiddenInput(['value'=>$group_id])->label(false)?>
                        <?=$form->field($model, 'fk_subject_id')->hiddenInput(['value'=>$subject_id])->label(false)?>
                        <?=$form->field($model, 'test_id')->hiddenInput(['value'=>$model2->id])->label(false)?>
                    </td>
                    <td>
                        <?php
                        $studentParent = \app\models\StudentParentsInfo::find()->where(['stu_id'=>$data['stu_id']])->One();
                        if($studentParent->first_name){
                            $student_parent = $studentParent->first_name.' '.$studentParent->last_name;
                        }else{
                            $student_parent = 'N/A';
                        }
                        ?>
                        <?=$student_parent;?>
                    </td>
                    <td>
                       <?php if($current->obtained_marks < $model2->passing_marks){
                        echo '<span style="color:red;border:1px solid red">'.$current->obtained_marks.'</span>';
                       }else{
                        echo $current->obtained_marks;
                       }?>

                    </td>
                    <td>
                        <?=$current->remarks ?>
                    </td>
                    </tr>
                    <?php
                    $i++;
                }

            ?>
            </tbody>
        </table>
        <?php ActiveForm::end(); ?>