<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\depdrop\DepDrop;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use kartik\select2\Select2;

$this->title = 'Exam Details';
$settings = Yii::$app->common->getBranchSettings();
$class_array = ArrayHelper::map(\app\models\RefClass::find()->where(['fk_branch_id'=>Yii::$app->common->getBranch(),'status'=>'active'])->all(), 'class_id', 'title');
?>
<?php if (Yii::$app->session->hasFlash('success')): ?>
    <div class="alert alert-success alert-dismissable">
         <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
         <?= Yii::$app->session->getFlash('success') ?>
    </div>
<?php endif; ?>
<?php if (Yii::$app->session->hasFlash('error')): ?>
    <div class="alert alert-danger alert-dismissable">
         <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
         <?= Yii::$app->session->getFlash('error') ?>
    </div>
<?php endif; ?>
<section class="invoice" style="margin-top: -20px">
      <div class="row">
        <?php $form = ActiveForm::begin(); ?> 
            <div class="col-sm-3 fh_item">
                    <?= $form->field($model, 'fk_class_id')->dropDownList($class_array, ['id'=>'class-id','prompt' => 'Select '.Yii::t('app','Class').'...']); ?>
                </div>
            <div class="col-sm-2 fh_item">
                <?php
                // Dependent Dropdown
                echo $form->field($model, 'fk_group_id')->widget(DepDrop::classname(), [
                    'options' => ['id'=>'group-id'],
                    'pluginOptions'=>[
                        'depends'=>['class-id'],
                        'prompt' => 'Select '.Yii::t('app','Group').'...',
                        'url' => Url::to(['/site/get-group'])
                    ]
                ]);
                ?>

            </div>
            <!-- <div class="col-sm-2 fh_item">
                <?php
               /* echo $form->field($model, 'fk_section_id')->widget(DepDrop::classname(), [
                    'options' => ['id'=>'section-id'],
                    'pluginOptions'=>[
                        'depends'=>[
                            'group-id','class-id'
                        ],
                        'prompt' => 'Select '.Yii::t('app','section').'...',
                        'url' => Url::to(['/site/get-section'])
                    ]
                ]);*/
                ?>
            </div> -->
            <div class="col-sm-2">
                <label>Select Year</label>
                 <select name="fromYear" class="form-control" id="examYear" data-url="<?php echo Url::to(['get-exam-by-year']) ?>">
                    <option>Select Year</option>
                    <?php
                       $starting_year  =date('Y', strtotime('-1 year'));
                       $ending_year = date('Y', strtotime('+0 year'));
                          for($starting_year; $starting_year <= $ending_year; $starting_year++) {
                            echo '<option value="'.$starting_year.'">'.$starting_year.'</option>';
                          }             
                         //echo '</select>'; 
                       ?>
                    </select>
            </div>
            <div class="col-sm-3 exam-dropdown-list fh_item">
                <input type="hidden" id="exam-url" value="<?=Url::to(['/exams/get-exams-list'])?>">
                <?php
                // Dependent Dropdown
               /* echo $form->field($model, 'fk_exam_type')->widget(DepDrop::classname(), [
                    'options' => ['id'=>'exam-type-id'],
                    'pluginOptions'=>[
                        'depends'=>[
                            'exam-section-id',
                            'group-id',
                            'class-id'
                        ],
                        'prompt' => 'Select '.Yii::t('app','Exam Type').'...',
                        'url' => Url::to(['/site/get-exams-list'])
                    ]
                ]);*/
                echo $form->field($model, 'fk_exam_type')->widget(Select2::classname(), [
               
                'options' => ['id'=>'exam-type-id','class'=>'examttypeStudentMarks'],
                'pluginOptions' => [
                'allowClear' => true
                ],
                ]);
                ?>
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