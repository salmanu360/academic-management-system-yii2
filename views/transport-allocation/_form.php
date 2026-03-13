<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use kartik\date\DatePicker;
use app\models\Zone;
use app\models\Stop;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $model app\models\TransportAllocation */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="transport-allocation-form">

    <?php $form = ActiveForm::begin(); ?>

    <?php
                $zone = ArrayHelper::map(Zone::find()->where(['fk_branch_id'=>yii::$app->common->getBranch()])->all(), 'id', 'title');
                echo $form->field($model, 'zone_id')->widget(Select2::classname(), [
                    'data' => $zone,
                    'options' => ['placeholder' => 'Select Zone ...','class'=>'zonechange zonePrev','data-url'=>\yii\helpers\Url::to(['student/get-route'])],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ]);
                if($model->isNewRecord !=1){
                    $gtRoute = ArrayHelper::map(\app\models\Route::find()->all(), 'id', 'title');
                     echo $form->field($model, 'route_id')->widget(Select2::classname(), [
                    'data' => $gtRoute,
                     'options' => ['placeholder' => 'Select Route ...','class'=>'route routePrev','data-url'=>\yii\helpers\Url::to(['student/get-stop'])],
                     'pluginOptions' => [
                         'allowClear' => true
                     ],
                 ]);
                }else{
                   $model->route_id=(count($model->route_id)>0)?$model->fkRoute->title:'';
                    echo $form->field($model, 'route_id')->widget(Select2::classname(), [
                     'options' => ['placeholder' => 'Select Route ...','class'=>'route routePrev','data-url'=>\yii\helpers\Url::to(['student/get-stop'])],
                     'pluginOptions' => [
                         'allowClear' => true
                     ],
                    ]);
                }

                if($model->isNewRecord!='1'){
                $gtStop = ArrayHelper::map(Stop::find()->all(), 'id', 'title');
                echo $form->field($model, 'fk_stop_id')->widget(Select2::classname(), [
                    'data' => $gtStop,
                     'options' => ['prompt' => 'Select Stop ...','class'=>'stop'],
                     'pluginOptions' => [
                         'allowClear' => true
                     ],
                 ]);
                }else{
               $model->fk_stop_id=(count($model->fk_stop_id)>0)?$model->stop->title:'';
               echo $form->field($model, 'fk_stop_id')->widget(Select2::classname(), [
                    //'data' => $items,
                     'options' => ['placeholder' => 'Select Stop ...','class'=>'stop stopPrev'],
                     'pluginOptions' => [
                         'allowClear' => true
                     ],
                 ]);
            }
            ?>
            <label for="class">Class</label>
                  <?= Html::dropDownList('ref_class', null,
                      ArrayHelper::map(\app\models\RefClass::find()->where(['fk_branch_id'=>yii::$app->common->getBranch(),'status'=>'active'])->all(), 'class_id', 'title'),['prompt'=>'Select Class','class'=>'form-control','id'=>'studentClassWise','data-url'=>Url::to(['student/class-wise-students'])]) ?>
           <br />

            <?php 
            if($model->isNewRecord !=1){
            $model->stu_id=yii::$app->common->getName($model->stu_id);
            echo $form->field($model, 'stu_id')->widget(Select2::classname(), [
            'options' => ['placeholder' => 'Select Student','class'=>'classwisestudent'],
            'pluginOptions' => [
            'allowClear' => true
            ],
            ])->label('Student'); 
            }else{
            $model->stu_id=(count($model->stu_id)>0)?yii::$app->common->getName($model->stu_id):'';
            echo $form->field($model, 'stu_id')->widget(Select2::classname(), [
            'options' => ['placeholder' => 'Select Student','class'=>'classwisestudent'],
            'pluginOptions' => [
            'allowClear' => true
            ],
            ])->label('Student');
            }

            
           ?>

         <?php 
         echo $form->field($model, 'allotment_date')->widget(DatePicker::classname(), [
                        'options' => ['value' => date('Y-m-d'),'class'=>'inputes','id'=>'alomentdate'],
                        'pluginOptions' => [
                            'autoclose'=>true,
                            'format' => 'yyyy-mm-dd',
                            'todayHighlight' => true,
                            //'startDate' => '-2y',
                        ]
                    ]);
          ?>
          <div class="alert alert-warning">
          <strong>Note!</strong> If Not giving discount,then don't fill discount field.
        </div>
         <?= $form->field($model, 'discount_amount')->textInput(['type'=>'number']) ?>

         <?= $form->field($model, 'branch_id')->hiddenInput(['value'=>yii::$app->common->getBranch()])->label(false) ?>

  
	<?php if (!Yii::$app->request->isAjax){ ?>
	  	<div class="form-group">
	        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
	    </div>
	<?php } ?>

    <?php ActiveForm::end(); ?>
    
</div>
