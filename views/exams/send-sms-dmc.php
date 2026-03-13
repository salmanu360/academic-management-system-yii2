<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\date\DatePicker;
use kartik\depdrop\DepDrop;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\bootstrap\Modal;
$settings = Yii::$app->common->getBranchSettings();
$class_array = ArrayHelper::map(\app\models\RefClass::find()->where(['fk_branch_id'=>Yii::$app->common->getBranch(),'status'=>'active'])->all(), 'class_id', 'title');
?>


<section class="invoice" style="margin-top: -17px">
    <?php if (Yii::$app->session->hasFlash('success')): ?>
    <div class="alert alert-success alert-dismissable">
         <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
         <?= Yii::$app->session->getFlash('success') ?>
    </div>
<?php endif; ?>

<?php $form = ActiveForm::begin(); ?>
<div class="row">
   <div class="col-sm-12">
    <div class="col-sm-2 fh_item">
        <?= $form->field($model, 'fk_class_id')->dropDownList($class_array, ['id'=>'class-id','prompt' => 'Select '.Yii::t('app','Class').'...']); ?>
    </div>
    <div class="col-sm-2 fh_item">
        <?php
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
    <div class="col-sm-2 fh_item">
        <!-- <input type="hidden" id="subject-url" value="<?//=Url::to(['/exams/get-exams'])?>"> -->
         <!-- <input type="hidden" id="subject-url">   -->
        <?php
        echo $form->field($model, 'fk_section_id')->widget(DepDrop::classname(), [
            'options' => ['id'=>'section-id'],
            'pluginOptions'=>[
                'depends'=>[
                    'group-id','class-id'
                ],
                'prompt' => 'Select '.Yii::t('app','section').'...',
                'url' => Url::to(['/site/get-section'])
            ]
        ]);
        ?> 
    </div>
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
                <input type="hidden" id="exam-url" value="<?=Url::to(['dmc-send'])?>">
                <?php
                echo $form->field($model, 'fk_exam_type')->widget(Select2::classname(), [
                'options' => ['id'=>'exam-type-id','class'=>'examttypeStudentMarks'],
                'pluginOptions' => [
                'allowClear' => true
                ],
                ]);
                ?>
            </div> 
   </div> 
</div>
<?php ActiveForm::end(); ?>
</section>
<div class="row">
    <div class="col-md-12" id="exams-inner"></div>
</div>
