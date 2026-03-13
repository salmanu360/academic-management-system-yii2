<?php

use yii\widgets\ActiveForm;
use kartik\depdrop\DepDrop;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use app\widgets\Alert;

/* @var $this yii\web\View */
/* @var $model app\models\Exam */ 

$this->title = 'Shift Students';
$settings = Yii::$app->common->getBranchSettings();
$class_array = ArrayHelper::map(\app\models\RefClass::find()->where(['fk_branch_id'=>Yii::$app->common->getBranch(),'status'=>'active'])->all(), 'class_id', 'title'); 
?>
<?=Alert::widget()?> 
<div class="row">
    <div class="col-md-12">
        <?php echo $this->render('action'); ?>
    </div>
</div>
<div class="panel panel-default">
  <div class="panel-heading">Change Students Section</div>
   <div class="panel-body">
        <?php $form = ActiveForm::begin(['id'=>'promote-student-list-form']); ?>
        <div class="row">
           
           <div class="col-sm-9">
            <div class="col-sm-4 fh_item">
                <input type="hidden" id="subject-url-class" value="<?=Url::to(['/student/shuffle-class'])?>">

                <?= $form->field($model, 'class_id')->dropDownList($class_array, ['id'=>'class-id','prompt' => 'Select Class ...','class'=>'class-shuffle form-control']); ?>
            </div>
            <div class="col-sm-4 fh_item">
                <?php
                // Dependent Dropdown
                echo $form->field($model, 'group_id')->widget(DepDrop::classname(), [
                    'options' => ['id'=>'group-id','class'=>'group-sh form-control'],
                    'pluginOptions'=>[
                        'depends'=>['class-id'],
                        'loadingText' => 'Loading Groups ...',
                        'prompt' => 'Select Group...',
                        'url' => Url::to(['/site/get-group'])
                    ]
                ]);
                ?> 
            </div>
            <div class="col-sm-4 fh_item">
                <input type="hidden" id="subject-urls" value="<?=Url::to(['/student/branch-student-list-shuffle'])?>">
                <?php
                
                // Dependent Dropdown
                echo $form->field($model, 'section_id')->widget(DepDrop::classname(), [
                    'options' => ['id'=>'section-id-shuffle'],
                    'pluginOptions'=>[
                        'depends'=>[
                            'group-id','class-id'
    
                        ],
                        'loadingText' => 'Loading Sections ...',
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

