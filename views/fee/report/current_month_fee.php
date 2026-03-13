<?php
use yii\widgets\ActiveForm;
use kartik\depdrop\DepDrop;
use kartik\date\DatePicker;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\helpers\Html;
use app\widgets\Alert;
use yii\bootstrap\Modal;

 $form = ActiveForm::begin(['id'=>'gen-fee-challan','action'=>'month-bulk-report']); ?>

 <div class="row">
 	<div class="col-md-3">
 		<?php echo $form->field($model, 'class_id')->dropDownList($class_array, ['id'=>'class-id','prompt' => 'Select '.Yii::t('app','Class').'...']); ?>
 	</div>
 	<div class="col-md-3">
 		<?php echo $form->field($model, 'group_id')->widget(DepDrop::classname(), [
                    'options' => ['id'=>'group-id'],
                    'pluginOptions'=>[
                        'depends'=>['class-id'],
                        'prompt' => 'Select '.Yii::t('app','Group').'...',
                        'url' => Url::to(['/site/get-group'])
                    ]
                ]); ?>
 	</div>
 	<div class="col-md-3">
 		<?php echo $form->field($model, 'section_id')->widget(DepDrop::classname(), [
                    'options' => ['id'=>'section-id'],
                    'pluginOptions'=>[
                        'depends'=>[
                            'group-id','class-id'
                        ],
                        'prompt' => 'Select '.Yii::t('app','section').'...',
                        'url' => Url::to(['/site/get-section'])
                    ]
                ]); ?>
 	</div>
 	<div class="col-md-3">
 		<button type="submit" class="btn btn-primary"> Submit</button>
 	</div>
 </div>


<?php ActiveForm::end(); ?>