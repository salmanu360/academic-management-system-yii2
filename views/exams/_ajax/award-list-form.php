<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\widgets\ActiveForm;

$examtype_array = ArrayHelper::map(\app\models\ExamType::find()->where(['fk_branch_id'=>Yii::$app->common->getBranch()])->all(), 'id', 'type');
/* @var $this yii\web\View */
/* @var $searchModel app\models\search\ExamsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
//'-'.$modelExam->fkSection->title.
?>
<div class="row">
    <div class="col-md-6">
        <div class="alert alert-danger">Put -0 in obtained marks when student is Absent</div>
    </div>
</div>
<h3><?=$modelExam->fkExamType->type.'-'.$modelExam->fkClass->title.'-'.$modelExam->fkSubject->title?><?=($modelExam->fk_subject_division_id)?'-'.$modelExam->fkSubjectDivision->title:''?></h3>
<div class="create-award-list-form ">
    <?php $form = ActiveForm::begin(['id' => 'award-list-form', 'action' => Url::to(['exams/save-award-list']),'enableClientValidation' => false]); ?>
    <?=$form->field($model, 'fk_exam_id')->hiddenInput(['value'=>$modelExam->id])->label(false)?>
    <input type="hidden" id="total-marks" value="<?=$modelExam->total_marks?>"/>
    <div class="table-responsive">
        <table class="table">
            <thead>
            <tr>
                <th>#</th>
                <th>Registration No.</th>
                <th>Roll No</th>
                <th>Student</th>
                <th>Parent Name</th>
                <th>Obtained Marks</th>
               <!-- <th>Remarks</th> -->
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
                    <td><?=$i?></td>
                    <td>
                        <?=$student->user->username;?>
                    </td>
                    <td> <?=(!empty($student->roll_no)?$student->roll_no : 'N/A');?></td>
                    <td>
                        <?= Yii::$app->common->getName($student->user_id)?>
                        <?=$form->field($model, 'fk_student_id['.$key.']')->hiddenInput(['value'=>$data['stu_id']])->label(false)?>
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
                        <?=$form->field($model, 'marks_obtained['.$key.']')->textInput(['value'=>(isset($current->marks_obtained))?$current->marks_obtained:''])->label(false)?>
                    </td>
                    <td>
                        <?=$form->field($model, 'remarks['.$key.']')->hiddenInput(['value'=>(isset($current->remarks))?$current->remarks:''])->label(false)?>
                        <input type="hidden" value="<?= $section_id ?>" name='section_id'>
                    </td>
                    <!-- <input type="hidden" value="<?= $section_id ?>" name='section_id'> -->
                    </tr>
                    <?php
                    $i++;
                }

            ?>
            </tbody>
        </table>
        <?php
         if (count($modelExam)>0) {
             ?>
            <div class="form-group">
                <?= Html::button('Save', ['class' => 'teaser btn btn-success', 'id' => 'save-award-list']) ?>
                <?= Html::a('Generate Blank Awardlist',[
                    'generate-blank-awardlist',
                    'type'=>base64_encode('blank'),
                    'exam_id'=>base64_encode($modelExam->id),
                    'section_id'=>base64_encode($section_id),
                    /*'class_id'=>$modelExam->fk_class_id,
                    'group_id'=>($modelExam->fk_group_id)?$modelExam->fk_group_id:null,
                    'group_id'=>$modelExam->fk_section_id,*/
                ], ['class' => 'teaser btn btn-danger', 'id' => 'generate-blank-awardlist']) ?>
                <?= Html::a('Generate Awardlist',[
                    'generate-blank-awardlist',
                    'type'=>base64_encode('std_marks'),
                    'exam_id'=>base64_encode($modelExam->id),
                    'section_id'=>base64_encode($section_id),
                    /*'class_id'=>$modelExam->fk_class_id,
                    'group_id'=>($modelExam->fk_group_id)?$modelExam->fk_group_id:null,
                    'group_id'=>$modelExam->fk_section_id,*/
                ], ['class' => 'teaser btn btn-danger', 'id' => 'generate-blank-awardlist']) ?>
            </div>
            <?php
         }
         ActiveForm::end();
        ?>
    </div>
</div>