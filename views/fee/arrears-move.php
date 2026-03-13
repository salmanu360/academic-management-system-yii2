<?php 
use yii\widgets\ActiveForm;
use kartik\depdrop\DepDrop;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use app\models\RefClass;
use app\models\RefGroup;
$this->title = 'Arrears Move | Report';

$class_array = ArrayHelper::map(\app\models\RefClass::find()->where(['fk_branch_id'=>Yii::$app->common->getBranch(),'status'=>'active'])->all(), 'class_id', 'title'); 
$this->registerCssFile(Yii::getAlias('@web').'/css/site.css',['depends' => [yii\web\JqueryAsset::className()]]);
?>  
<?php if (Yii::$app->session->hasFlash('success')): ?>
           <div class="alert alert-success">
              <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
              <?= Yii::$app->session->getFlash('success') ?>
           </div>
            <?php endif; ?>
<div class="pad margin no-print" style="margin-top: -20px">  
    <div class="callout callout-info" style="margin-bottom: 0!important; background-color: #ffffff !important;"> 
        <h4 style="color:red"><i class="fa fa-info"></i>: Fee which is not submitted in <?php echo date('M Y') ?> Or shift fee in arrears to <?php echo date('M Y',strtotime('+1 month')) ?></h4>
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
                            'options' => ['id'=>'group-id','value'=>1],
                            'pluginOptions'=>[
                            'depends'=>['class-id'],
                            'prompt' => 'Select Group...',
                            'url' => Url::to(['/site/get-group'])
                        ]
                    ]);?>
                        
                        
                </div>
                <div class="col-sm-4 fh_item" style="color: black !important;">
                    <input type="hidden" id="subject-url" value="<?=Url::to(['/fee/get-current-month-notsubmited-fee'])?>">
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