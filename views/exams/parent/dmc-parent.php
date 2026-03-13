<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\depdrop\DepDrop;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use kartik\select2\Select2;

$this->title = 'Exam Details';
$student=\app\models\StudentInfo::find()->where(['user_id'=>Yii::$app->user->identity->id])->one();
//echo '<pre>';print_r($getParentsChilds);die;
$settings = Yii::$app->common->getBranchSettings();
$class_array = ArrayHelper::map(\app\models\RefClass::find()->where(['fk_branch_id'=>Yii::$app->common->getBranch(),'status'=>'active'])->all(), 'class_id', 'title');
?>
<section class="invoice" style="margin-top: -15px">
      <div class="row">
        <?php $form = ActiveForm::begin(); ?> 
        <div class="col-sm-3 exam-dropdown-list fh_item">
        <input type="hidden" id="exam-url" value="<?=Url::to(['/exams/get-exams-list-parent'])?>">
                <label for="">Select Exam</label>
                <select name="" id="exam-type-id" class="form-control examttypeStudentMarks" data-url="<?= Url::to(['exams/std-dmc-parent']) ?>">
                    <option value="">Select Class</option>
                    <?php foreach($examYear as $getType){ ?>
                    <option value="<?=$getType->fk_exam_type ?>"><?= $getType->fkExamType->type ?></option>
                    <?php } ?>
                </select>
            </div>
        <?php ActiveForm::end(); ?>
        <!-- /.col -->
      </div>
      </section>
<div class="filter_wrap content_col tabs grey-form">  
  
    <div class="form-midd action-head">
    </div>
    <div class="form-midd shade fee-gen">
        
        <div  id="subject-details">
            <div id="exams-inner"></div>
        </div> 
    </div>
</div> 