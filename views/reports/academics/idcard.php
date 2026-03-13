<?php 
use yii\widgets\ActiveForm;
use kartik\depdrop\DepDrop;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use app\models\RefClass;
use app\models\RefGroup;
use kartik\select2\Select2;
$this->title = 'Id Card';
$class_array = ArrayHelper::map(\app\models\RefClass::find()->where(['fk_branch_id'=>Yii::$app->common->getBranch(),'status'=>'active'])->all(), 'class_id', 'title'); 
?>
<div class="panel panel-default panel-body">
	<div class="row">
  <div class="col-md-12">
    <strong style="color:red">Student Card</strong>
    <a style="margin-left: 5px;" class="btn btn-danger pull-right" href="<?php echo Url::to(['accounts']) ?>">Back</a>
  </div></div>
<div class="row">
	<div class="col-md-12">
		<div class="row">
			<?php $form = ActiveForm::begin(['action'=>'id-card']); ?> 
           <div class="col-sm-12">
           		<div class="col-md-2">		
			 <?php
			 $class_array = ArrayHelper::map(\app\models\RefClass::find()->where(['fk_branch_id'=>Yii::$app->common->getBranch(),'status'=>'active'])->all(), 'class_id', 'title');
			  echo $form->field($model, 'class')->dropDownList($class_array, ['id'=>'class-id','prompt' => 'Select Class ...']);  ?>
			</div>
                <div class="col-md-3">
				<?php 
				echo $form->field($model, 'group')->widget(DepDrop::classname(), [
                    'options' => ['id'=>'group-id'],
                    'pluginOptions'=>[
                        'depends'=>['class-id'],
                        'loadingText' => 'Loading Groups ...',
                        'prompt' => 'Select Group...',
                        'url' => Url::to(['/site/get-group'])
                    ]
                ]); ?>
			</div>
                <div class="col-sm-3">
                <input type="hidden" name="" id="getIactiveStudentsInput" value="<?= Url::to(['student/get-active-students']) ?>">
                <?php echo $form->field($model, 'section')->widget(DepDrop::classname(), [
                    'options' => ['id'=>'section-id','class'=>'section-id-inactive form-control'],
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
            <div class="col-sm-3">
			  	<?php echo $form->field($model, 'stu_id')->widget(Select2::classname(), [
            	'options' => ['placeholder' => 'Select Student','class'=>'studentIdSlc','id'=>'subject-inner','data-url'=>Url::to(['leaving-pdf'])],
            	'pluginOptions' => [
                'allowClear' => true
            	],
	        	])->label('Student');
 				?>
			  </div>
                <div class="col-md-2">
                	<button class="btn btn-success" style="margin-top: 23px">Submit</button>
                </div>
           </div> 
            <?php ActiveForm::end(); ?>
        </div>
	</div>
</div>
</div>

<!-- get id card view -->
<?php if(Yii::$app->request->get('card')){?>
here
<?php } ?>
<!-- get id card view end-->