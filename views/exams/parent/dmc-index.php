<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\date\DatePicker;
use kartik\depdrop\DepDrop;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
$this->registerCssFile(Yii::getAlias('@web').'/css/site.css',['depends' => [yii\web\JqueryAsset::className()]]);
$settings = Yii::$app->common->getBranchSettings();
$class_array = ArrayHelper::map(\app\models\RefClass::find()->where(['fk_branch_id'=>Yii::$app->common->getBranch(),'status'=>'active'])->all(), 'class_id', 'title'); 
$this->title = 'Exam\'s DMC';
?> 
<div class="filter_wrap content_col tabs grey-form">  
    <div class="shade dmc_wrapper fee-gen">
    <section class="invoice" style="margin-top: -15px">
        <div class="exam-form filters_head"> 
        <?php $form = ActiveForm::begin(); ?>
        <div class="row">
      <div class="form-inline">
            <div class="col-sm-3">
                <select name="" id="examTypeParent" class="form-control" data-url="<?= Url::to(['exams/std-dmc-parent']) ?>">
                    <option value="">Select Class</option>
                    <?php foreach($examYear as $getType){ ?>
                    <option value="<?=$getType->fk_exam_type ?>"><?= $getType->fkExamType->type ?></option>
                    <?php } ?>
                </select>
            </div>
        </div>
        </div>
          
        <?php ActiveForm::end(); ?> 
        </div>
        </section>
        <div  id="subject-details">
            <div id="subject-inner"></div>
        </div> 
    </div>
</div>