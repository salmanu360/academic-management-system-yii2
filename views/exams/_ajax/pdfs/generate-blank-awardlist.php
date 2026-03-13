<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\widgets\ActiveForm;

$examtype_array = ArrayHelper::map(\app\models\ExamType::find()->where(['fk_branch_id'=>Yii::$app->common->getBranch()])->all(), 'id', 'type');?>
<style>
    table{
        width: 100%;
        max-width: 100%;
        margin-bottom: 20px;
    }
    tbody{
        display: table-row-group;
        vertical-align: middle;
        border-color: inherit;
    }
    thead{
        display: table-header-group;
        vertical-align: middle;
        border-color: inherit;
    }
    tr{
        display: table-row;
        vertical-align: inherit;
        border-color: inherit;
    }
    th{
        vertical-align: bottom;
        border-bottom: 2px solid #CCCCCC;
    }
    td{
        padding: 8px;
        line-height: 1.42857143;
        vertical-align: top;
        border-bottom: 1px solid #CCCCCC;
    }
    /**
 * tr:nth-child(even){background-color: #f2f2f2}
 */
    th, tr, td  {
    border:0.5px solid black;
    padding:7px;
  }
</style>
<div style="width: 100%; text-align: center; background-color: #337ab7; color: #000; font-size:14px;">
  <h2 style="font-size:16px; font-weight:700; color:#000000; text-transform:capitalize;margin: 0;padding: 15px 0 8px 0;">
    <?=Yii::$app->common->getBranchDetail()->address?>
  </h2>
</div>
<h3 style="text-align: center;">
  <!--   <?php
  /* ucfirst($modelExam->fkExamType->type).' AwardList of '.$modelExam->fkClass->title.'-'.$modelExam->fkSection->title.'-'.$modelExam->fkSubject->title?><?=($modelExam->fk_subject_division_id)?'-'.$modelExam->fkSubjectDivision->title:'';*/

  ?> -->
    <?php
  echo ucfirst($modelExam->fkExamType->type).' AwardList of '.Yii::$app->common->getCGSName($modelExam->fk_class_id,$modelExam->fk_group_id,$section_id).'-'.$modelExam->fkSubject->title?><?=($modelExam->fk_subject_division_id)?'-'.$modelExam->fkSubjectDivision->title:'';?>
        
    </h3>
<div class="create-award-list-form ">
    <input type="hidden" id="total-marks" value="<?=$modelExam->total_marks?>"/>
    <div class="table-responsive">
        <table class="table">
            <thead>
            <tr>
                <th>#</th>
                <th><?=Yii::t('app','Registration No')?>.</th>
                <th><?=Yii::t('app','Roll No')?>.</th>
                <th><?=Yii::t('app','Student')?></th>
                <th><?=Yii::t('app','Parent Name')?></th>
                <?=($type=='std_marks')?"<th>".Yii::t('app','Total Marks')."</th>":""?>
                <th><?=Yii::t('app','Obtained Marks')?></th>
                <!-- <th><?//=Yii::t('app','Remarks')?></th> -->
            </tr>
            </thead>
            <tbody>
            <?php
                $i=1;
                foreach ($dataprovider as $key=>$data){
                    $student = \app\models\StudentInfo::findOne($data['stu_id']);
                    $current = \app\models\StudentMarks::find()->where(['fk_exam_id'=>$modelExam->id,'fk_student_id'=>$student->stu_id])->One();
                    //return $student->user->first_name.' '.$student->user->last_name;
                ?>
                    <tr>
                        <td><?=$i?></td>
                        <td><?=$student->user->username;?> </td>
                        <td> <?=(!empty($student->roll_no)?$student->roll_no : 'N/A');?></td>
                        <td><?= Yii::$app->common->getName($student->user_id)?> </td>
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
                        <?=($type=='std_marks')?"<td>".$modelExam->total_marks."</td>":''?>
                        <td><?=($type=='std_marks')?$current['marks_obtained']:''?></td>
                        <!-- <td>
                            <?//=($type=='std_marks')?$current['remarks']:''?>
                        </td> -->
                    </tr>
                    <?php $i++; } ?>
            </tbody>
        </table>
    </div>
</div>