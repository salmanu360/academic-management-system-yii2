<?php 

use yii\helpers\Url;
use app\models\User;
use app\models\StudentAttendance;
use app\models\StudentInfo;
use app\models\RefClass;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use kartik\depdrop\DepDrop;
$this->registerCssFile(Yii::getAlias('@web').'/css/site.css',['depends' => [yii\web\JqueryAsset::className()]]);
?>
<?php if (Yii::$app->session->hasFlash('success')): ?>
    <div class="alert alert-success">
      <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
      <?= Yii::$app->session->getFlash('success') ?>
    </div>
  <?php endif; ?>
<?php
$settings = Yii::$app->common->getBranchSettings();
 $class_array = ArrayHelper::map(\app\models\RefClass::find()->where(['status'=>'active','fk_branch_id'=>Yii::$app->common->getBranch()])->all(), 'class_id', 'title'); ?>
<div class="box box-warning">
    <div class="box-header with-border">
        <h3 class="box-title"><i class="fa fa-search"></i> Student Section Attendance (<?= Date('d M,Y')?>)</h3>
    </div>
     <div class="box-body">
        <div class="exam-form filters_head"> 
        <?php $form = ActiveForm::begin(); ?> 
        <div class="row">
           <div class="col-sm-9">
            <div class="col-sm-4 fh_item">
                <?= $form->field($model, 'fk_class_id')->dropDownList($class_array, ['id'=>'class-id','prompt' => 'Select Class ...','class'=>'form-control']); ?>
                <!-- add class getClassStudent if someone want class attendance and remove s from id="class-urls -->
            </div>
            <div class="col-sm-4 fh_item">
                 <?php
                    // Dependent Dropdown
                    echo $form->field($model, 'fk_group_id')->widget(DepDrop::classname(), [
                        'options' => ['id'=>'group-id','class'=>'form-control'],
                        'pluginOptions'=>[
                            'depends'=>['class-id'],
                            'prompt' => 'Select Group...',
                            'url' => Url::to(['/site/get-group'])
                        ]
                    ]);
                ?>
                 <!-- add class getGroupstudents if someone want group attendance and remove s from id="id="group-urls -->
            </div>
            <div class="col-sm-4 fh_item">
                <input type="hidden" id="subject-url" value="<?=Url::to(['/student/get-stu'])?>">
                <input type="hidden" id="class-urls" value="<?=Url::to(['/student/get-class-stu-attendance'])?>">
                <input type="hidden" id="group-urls" value="<?=Url::to(['/student/get-group-stu-attendance'])?>">
                <?php
                // Dependent Dropdown
                echo $form->field($model, 'fk_section_id')->widget(DepDrop::classname(), [
                    'options' => ['id'=>'section-id'],
                    'pluginOptions'=>[
                        'depends'=>[
                            'group-id','class-id'
                        ],
                        'prompt' => 'Select section',
                        'url' => Url::to(['/site/get-section'])
                    ]
                ]);
                ?>
            </div>
           </div> 
        </div>   
        <?php ActiveForm::end(); ?> 
        </div>
    </div>
    </div>
         <div id="subject-details">
            <div id="subject-inner"></div>
         </div>