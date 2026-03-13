<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\StudentInfo;
use yii\helpers\Url;
$StudentInfo=StudentInfo::find()->where(['stu_id'=>$model->fk_stu_id])->one();
/* @var $this yii\web\View */
/* @var $model app\models\EmployeeAttendance */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="box box-warning">
    <div class="box-header with-border">
        <h3 class="box-title"><i class="fa fa-edit"></i> Edit Attendance of <?php echo Yii::$app->common->getName($StudentInfo->user_id); ?>
        </h3>
<a href="<?php echo Url::to(['/student/attendance-list'])?>" class="btn btn-success pull-right">Back</a>

    </div>
     <div class="box-body">

    <?php $form = ActiveForm::begin(); ?>
	
    <?php $form->field($model, 'fk_stu_id')->textInput() ?>
    <?php $form->field($model, 'date')->textInput() ?>

    <?php
    echo '<label>'."Attendance Type".'</label>';
                   $attendance_array= ['present' => 'present','absent' => 'Absent', 'leave' => 'Leave','late' => 'Late','Latewithexcuse' => 'Late with excuse'];
                  echo Html::activeDropDownList($model, 'leave_type',$attendance_array,['class'=>'form-control']);
                  ?>
                  <br>

    <?= $form->field($model, 'remarks')->textarea(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
</div>

