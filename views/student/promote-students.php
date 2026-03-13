<?php
use yii\widgets\ActiveForm;
use kartik\depdrop\DepDrop;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use app\widgets\Alert;
$this->title = 'Promote Student';
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
  <div class="panel-heading">Promote Students</div>
   <div class="panel-body">
        <div class="exam-form filters_head"> 
        <?php $form = ActiveForm::begin(['id'=>'promote-student-list-form']); ?>
        <div class="row">
           
           <div class="col-sm-9">
            <div class="col-sm-4 fh_item">
                <?= $form->field($model, 'class_id')->dropDownList($class_array, ['id'=>'class-id','prompt' => 'Select Class ...']); ?>
            </div>
            <div class="col-sm-4 fh_item">
                <?php
                // Dependent Dropdown
                echo $form->field($model, 'group_id')->widget(DepDrop::classname(), [
                    'options' => ['id'=>'group-id'],
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
                <input type="hidden" id="subject-url" value="<?=Url::to(['/student/branch-student-list'])?>">
                <?php
                echo $form->field($model, 'section_id')->widget(DepDrop::classname(), [
                    'options' => ['id'=>'section-id'],
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
        <div id="subject-details">
            <div id="subject-inner"></div>
        </div> 