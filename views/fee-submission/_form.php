<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;
use kartik\depdrop\DepDrop;
use yii\helpers\Url;

$class_array = ArrayHelper::map(\app\models\RefClass::find()->where(['fk_branch_id'=>Yii::$app->common->getBranch(),'status'=>'active'])->all(), 'class_id', 'title');
?>
<?php if (Yii::$app->session->hasFlash('error')): ?>
   <div class="alert alert-danger">
      <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>  
 
      <?= Yii::$app->session->getFlash('error') ?>
   </div>
<?php endif; ?>
<?php if (Yii::$app->session->hasFlash('success')): ?>
   <div class="alert alert-success">
      <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>  
 
      <?= Yii::$app->session->getFlash('success') ?>
   </div>
<?php endif; ?>

<div class="box box-warning">
    <div class="box-header with-border" style="background: #337ab7; color: white">
        <h3 class="box-title"><i class="fa fa-money"></i> One Time Head Payment</h3>
    </div>
    <?php $form = ActiveForm::begin(); ?>
    <div class="box-body">
    <div class="row">
    <div class="col-md-6">
        <input type="hidden" id="urloforgettingonetimehead" value="<?= Url::to(['class-one-head']);?>">
    <?php echo $form->field($model, 'class')->dropDownList($class_array, ['id'=>'class-id','prompt' => 'Select Class ...','class'=>'form-control']); ?>
     <div class="row">
             <div class="col-sm-4 fh_item">
                <input type="hidden" id="subject-url" value="<?=Url::to(['/student/section-wise-students'])?>">
    <?php echo $form->field($model, 'section')->widget(DepDrop::classname(), [
                    'options' => ['id'=>'section-id','class'=>'form-control onetimeheadclasswise','style'=>'width:521px'],
                    'pluginOptions'=>[
                        'depends'=>[
                            'group-id','class-id'
                        ],
                        'loadingText' => 'Loading Sections ...',
                        'prompt' => 'Select section',
                        'url' => Url::to(['/site/get-section'])
                    ]
                ]); ?>
                
            </div>
         </div>
                <?php echo $form->field($model, 'stu_id')->widget(Select2::classname(), [
            'options' => ['placeholder' => 'Select Student','class'=>'feeSubmissionOnetime','id'=>'subject-inner','data-url'=>Url::to(['get-student-fee'])],
            'pluginOptions' => [
                'allowClear' => true
            ],
        ])->label('Student'); ?>
         
         
            
    </div>
    <div class="col-md-6">
        <?php echo $form->field($model, 'group')->widget(DepDrop::classname(), [
                    'options' => ['id'=>'group-id'],
                    'pluginOptions'=>[
                        'depends'=>['class-id'],
                        'loadingText' => 'Loading Groups ...',
                        'prompt' => 'Select Group...',
                        'url' => Url::to(['/site/get-group'])
                    ]
                ]); ?>
       
         <?php 
    $head = ArrayHelper::map(\app\models\FeeHead::find()->where(['branch_id'=>yii::$app->common->getBranch(),'extra_head'=>0,'one_time_payment'=>1])->all(), 'id', 'title');
    echo $form->field($model, 'fee_head_id')->widget(Select2::classname(), [
        //'data' => $head,
        'options' => ['placeholder' => 'Select Head ...','id'=>'onetimeHeadforOntimesubmission'],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ])->label('One Time Head'); ?>
         <?= $form->field($model, 'head_recv_amount')->textInput(['id'=>'showHeadamount'])->label('Amount') ?>

         <input type="hidden" name="total_amount" id="total_amount" value="">
    </div>
    </div>
    <?= $form->field($model, 'from_date')->hiddenInput(['value' => date('Y-m')])->label(false) ?>
    <?= $form->field($model, 'to_date')->hiddenInput(['value' => date('Y-m')])->label(false) ?>
    <?= $form->field($model, 'year_month_interval')->hiddenInput(['value' => date('Y-m')])->label(false) ?>
    <?= $form->field($model, 'recv_date')->hiddenInput(['value' => date('Y-m-d')])->label(false) ?>
    <?= $form->field($model, 'fee_status')->hiddenInput(['value'=>1])->label(false) ?>
    <?= $form->field($model, 'branch_id')->hiddenInput(['value'=>yii::$app->common->getBranch()])->label(false) ?>
    <div class="row">
        <div class="col-md-6">
            <?php if (!Yii::$app->request->isAjax){ ?>
        <div class="form-group">
            <?= Html::submitButton($model->isNewRecord ? 'Receive' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
            <a href="" class="btn btn-warning">Cancel</a>
        </div>
    <?php } ?>
        </div>
    </div>
    </div>   
<?php ActiveForm::end(); ?>
</div>