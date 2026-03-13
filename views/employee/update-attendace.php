<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\EmployeeInfo;
use yii\helpers\Url;
$employeeInfo=EmployeeInfo::find()->where(['emp_id'=>$model->fk_empl_id])->one();?>
<div class="box box-warning">
    <div class="box-header with-border">
        <h3 class="box-title"><i class="fa fa-edit"></i> Edit Attendance of <?php echo Yii::$app->common->getName($employeeInfo->user_id); ?>
        </h3>
<a href="<?php echo Url::to(['/employee/attendance-list'])?>" class="btn btn-success pull-right">Back</a>
    </div>
     <div class="box-body">
    <?php $form = ActiveForm::begin(); ?>
    <?php $form->field($model, 'fk_empl_id')->textInput() ?>
    <?php $form->field($model, 'date')->textInput() ?>
    <?php
    echo '<label>'."Attendance Type".'</label>';
                   $attendance_array= ['present' => 'present','absent' => 'Absent', 'leave' => 'Leave','shortleave'=>'Short Leave','late' => 'Late'];
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