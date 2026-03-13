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
<div class="filter_wrap content_col tabs grey-form" style="margin-top: -40px">  
    <div class="shade dmc_wrapper fee-gen">
    <section class="invoice">
    
        <div class="exam-form filters_head"> 
        <?php $form = ActiveForm::begin(); ?>
        <div class="row">
           <div class="col-sm-9">
            <div class="col-sm-4 fh_item">
                <?= $form->field($model, 'fk_class_id')->dropDownList($class_array, ['id'=>'class-id','prompt' => 'Select '.Yii::t('app','Class').'...']); ?>
            </div>
            <div class="col-sm-4 fh_item">
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
            <div class="col-sm-4 fh_item">
                <input type="hidden" id="subject-url" value="<?=Url::to(['/exams/cgs-dmc'])?>">
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