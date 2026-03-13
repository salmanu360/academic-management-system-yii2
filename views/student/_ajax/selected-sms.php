    <?php 
use yii\widgets\ActiveForm;
use kartik\depdrop\DepDrop;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use app\models\RefClass;
use app\models\RefGroup;
$this->title = 'Section SMS';
$class_array = ArrayHelper::map(\app\models\RefClass::find()->where(['fk_branch_id'=>Yii::$app->common->getBranch(),'status'=>'active'])->all(), 'class_id', 'title'); 
?>  
<?php if (Yii::$app->session->hasFlash('success')): ?>
            <div class="alert alert-success">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <?= Yii::$app->session->getFlash('success') ?>
            </div>
        <?php endif; ?>
<div style="margin-top: -20px">  
    <div style="margin-bottom: 0!important; background-color: #ffffff !important;padding: 10px"> 
        <div class="row">
          <div class="col-md-6">
            <label for="">Active</label>
            <input type="radio" name="studentAlumniCheck" id="activeStudents" value='1' checked="checked">
            <label for="">Alumni</label>
            <input type="radio" name="studentAlumniCheck" id="alumniStudents" value='0'>
          </div>
        </div>
        <?php $form = ActiveForm::begin(); ?> 
        <div class="row">
           <div class="col-sm-9">
                <div class="col-sm-4 fh_item" style="color: black !important;">
                <?php
                    echo  $form->field($model, 'class_id')->dropDownList($class_array, ['id'=>'class-id','prompt' => 'Select'.' '. Yii::t('app','Class')]);
                    ?>
                </div>
                <div class="col-sm-4 fh_item" style="color: black !important;">
                    <?php
                            echo $form->field($model, 'group_id')->widget(DepDrop::classname(), [
                            'options' => ['id'=>'group-id'],
                            'pluginOptions'=>[
                            'depends'=>['class-id'],
                            'prompt' => 'Select Group...',
                            'url' => Url::to(['/site/get-group'])
                        ]
                    ]);?>
                </div>
                <div class="col-sm-4 fh_item" style="color: black !important;">
                    <input type="hidden" id="subject-url" value="<?=Url::to(['/student/section-students'])?>">
                    <?php
                    
                    // Dependent Dropdown
                    echo $form->field($model, 'section_id')->widget(DepDrop::classname(), [
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
<div  id="subject-details">
            <div id="subject-inner"></div>
        </div> 
<!-- null -->
