<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\grid\GridView;
use yii\widgets\Pjax;
use kartik\select2\Select2;

use yii\widgets\ActiveForm;?>
<div class="box box-success">
<div class="table-responsive">
    <?php $form = ActiveForm::begin(['id' => 'award-list-form', 'action' => Url::to(['exams/save-quiz'])]); ?>
    <div class="container">
        <div class="row">
        
        <div class="col-md-2">
            <h4 style="color:green">Passing marks: <?php echo $model2->passing_marks ?></h4>
        </div>
        <div class="col-md-2">
            <h4 style="color:green">Total marks: <?php echo $model2->total_marks ?></h4>
        </div>
        <div class="col-md-3">
            <h4 style="color:green">Teacher: <?php echo Yii::$app->common->getName($employeeDetails->user_id) ?></h4>
        </div>
    </div>
    </div>
        <table class="table">
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
                        <?=$form->field($model, 'fk_class_id')->hiddenInput(['value'=>$model2->class_id])->label(false)?>
                        <?=$form->field($model, 'fk_group_id')->hiddenInput(['value'=>$model2->group_id])->label(false)?>
                        <?=$form->field($model, 'fk_subject_id')->hiddenInput(['value'=>$model2->subject_id])->label(false)?>
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
                       <?=$form->field($model, 'obtained_marks['.$data['stu_id'].']')->textInput(['maxlength' => true,'onkeypress'=>'return event.charCode >= 13 && event.charCode <= 57','value'=>(isset($current->obtained_marks))?$current->obtained_marks:''])->label(false)?>

                    </td>
                    <td>
                        <?=$form->field($model, 'remarks['.$data['stu_id'].']')->textInput(['value'=>(isset($current->remarks))?$current->remarks:''])->label(false)?>
                    </td>
                    </tr>
                    <?php
                    $i++;
                }

            ?>
            </tbody>
        </table>
        <?php
         if (count($model)>0) {
             ?>
            <div class="container">
                <div class="row">
                <div class="col-md-8">
                <div class="form-group">
                <button type="submit" class="btn btn-success">Save</button>
                <?= Html::a('Generate Blank Awardlist',[
                    'fill-quiz-pdf',
                    'type'=>base64_encode('blank'),
                    'subject_id'=>base64_encode($model2->subject_id),
                    'group_id'=>base64_encode($model2->group_id),
                    'class_id'=>base64_encode($model2->class_id),
                    'passing_marks'=>$model2->passing_marks,
                    'total_marks'=>$model2->total_marks,
                    'teacher_name'=>$employeeDetails->user_id,
                    'quiz_id'=>$model2->id,
                ], ['class' => 'teaser btn btn-danger', 'id' => 'generate-blank-awardlist']) ?>
                <?= Html::a('Generate Awardlist',[
                    'get-quiz-fill-awardlist',
                    //'type'=>base64_encode('blank'),
                    'subject_id'=>$model2->subject_id,
                    'group_id'=>$model2->group_id,
                    'class_id'=>$model2->class_id,
                    'quiz_id'=>$model2->id,
                ], ['class' => 'teaser btn btn-success', 'id' => 'generate-awardlist']) ?>
               </div>
               </div>
                </div>
            </div>
            </div>
        <?php } ActiveForm::end(); ?>
    </div>