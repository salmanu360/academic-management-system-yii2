<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use kartik\select2\Select2;
use app\models\ClassTimetable;
$this->registerCssFile(Yii::getAlias('@web').'/css/timepicker/bootstrap-datetimepicker.min.css',['depends' => [yii\web\JqueryAsset::className()]]); ?>
<?php $this->registerJsFile(Yii::getAlias('@web').'/js/timepicker/bootstrap-datetimepicker.min.js',['depends' => [yii\web\JqueryAsset::className()]]); 
if($model->isNewRecord !=1){
    $groupid=$model->group_id;
$gettimetableData = ClassTimetable::find()
                    ->where([
                        'fk_branch_id'  =>Yii::$app->common->getBranch(),
                        'class_id'   => $model->class_id,
                        'subject_id'=>$model->subject_id,
                        'group_id'   => ($groupid)?$groupid:null,
                        ])->one();
                }
?>
<div class="box box-warning">
    <div class="box-header with-border">
        <h3 class="box-title"><i class="fa fa-search"></i> Select Criteria</h3>
    </div>
    <?php $form = ActiveForm::begin(); ?>
    <div class="box-body">
    <div class="row">
    <div class="col-md-6">
            <?php 
            if($model->isNewRecord != 1){
    }else{
        $settings = Yii::$app->common->getBranchSettings();
            $class_array = ArrayHelper::map(\app\models\RefClass::find()->where(['fk_branch_id'=>Yii::$app->common->getBranch(),'status'=>'active'])->all(), 'class_id', 'title'); 
             echo $form->field($model, 'class_id')->widget(Select2::classname(), [
                            'data' => $class_array,
                            'options' => ['placeholder' => 'Select Class ...','data-url'=>url::to(['general/get-class-details']),'id'=>'getdataclass'],
                            'pluginOptions' => [
                                'allowClear' => true
                            ],
                        ]);
         }
                        ?>
    <?php 
    if($model->isNewRecord != 1){
    }else{
         echo $form->field($model, 'subject_id')->widget(Select2::classname(), [
        'options' => ['placeholder' => 'Select Subject','data-url'=>Url::to(['class-timetable/get-subjects-timetable']),'id'=>'getSubjectsdata'],
        'pluginOptions' => [
        'allowClear' => true
        ],
        ]);
    }
       ?>   
    <label for="">Starting Time</label>
    <div class="input-group bootstrap-timepicker timepicker">
    <input type="text" id="starttime" class="form-control input-small timepicker1 starttime" name="ClassTimetable[start_date]">
    <span class="input-group-addon"><i class="glyphicon glyphicon-time"></i></span>
    </div>
</div>
<div class="col-md-6">
    <?php 
    if($model->isNewRecord != 1){

    }else{
      echo $form->field($model, 'group_id')->widget(Select2::classname(), [
        'options' => ['placeholder' => 'Select Group','data-url'=>Url::to(['general/get-subjects']),'id'=>'classdatagroup'],
        'pluginOptions' => [
        'allowClear' => true
        ],
        ]);
  }
       ?>  
       <?php 
       if($model->isNewRecord != 1){
          $form->field($model, 'day')->textInput(['value'=>$gettimetableData->day]);
         }else{
            $dayname= ['monday' => 'Monday','tuesday' => 'Tuesday', 'wednesday' => 'Wednesday','thursday' => 'Thursday', 'friday' => 'Friday','saturday' => 'Saturday'];
    echo $form->field($model, 'day')->widget(Select2::classname(), [
                            'data' => $dayname,
                            'options' => ['placeholder' => 'Select Day ...','multiple' => true],
                            'pluginOptions' => [
                                'allowClear' => true
                            ],
                        ]);
         }
                        ?> 
    <label for="">Ending Time</label>
    <div class="input-group bootstrap-timepicker timepicker">
    <input type="text" id="endtime" class="form-control input-small timepicker1 endtime" name="ClassTimetable[end_date]" value="">
    <span class="input-group-addon"><i class="glyphicon glyphicon-time"></i></span>
    </div> 
</div>
</div>  
<?= $form->field($model, 'fk_branch_id')->hiddenInput(['value'=>yii::$app->common->getBranch()])->label(false) ?>
<div class="row">
    <div class="col-md-6">
        <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Save' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>
    </div>
</div>
</div>
    <?php ActiveForm::end(); ?>
</div>
<?php
$script = <<< JS
$('.timepicker1').timepicker({defaultTime: ''});
JS;
$this->registerJs($script);
?>