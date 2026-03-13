<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;
use yii\helpers\Url;
use kartik\depdrop\DepDrop;
use kartik\date\DatePicker;

$settings = Yii::$app->common->getBranchSettings();
$class_array = ArrayHelper::map(\app\models\RefClass::find()->where(['fk_branch_id'=>Yii::$app->common->getBranch(),'status'=>'active'])->all(), 'class_id', 'title'); 
?>
<div class="fee-arears-form">
    <?php $form = ActiveForm::begin(); ?>
                <?php
                if($model->isNewRecord !=1){
                $user_id = \app\models\StudentInfo::find()->select(['class_id'])->where(['stu_id'=>$model->stu_id])->one();?>
                <input type="hidden" name="FeeArears[class]" value="<?php echo $user_id->class_id ?>">
                <label for="">Class</label>
                <input type="text" class="form-control" value="<?php echo $user_id->class->title; ?>" readonly>
               <?php }else{
                echo $form->field($model, 'class')->dropDownList($class_array, ['id'=>'class-id','prompt' => 'Select Class ...']); 
                }
                ?>
                <?php
                if($model->isNewRecord !=1){
                $user_id = \app\models\StudentInfo::find()->select(['group_id'])->where(['stu_id'=>$model->stu_id])->one();?>
                <input type="hidden" name="FeeArears[group]" value="<?php echo $user_id->group_id ?>">
                <?php if(count($user_id->group_id)>0){ ?>
                <label for="">Group</label>
                <input type="text" class="form-control" value="<?php echo $user_id->group->title; ?>" readonly>
                <?php } ?>
               <?php }else{
                echo $form->field($model, 'group')->widget(DepDrop::classname(), [
                    'options' => ['id'=>'group-id'],
                    'pluginOptions'=>[
                        'depends'=>['class-id'],
                        'loadingText' => 'Loading Groups ...',
                        'prompt' => 'Select Group...',
                        'url' => Url::to(['/site/get-group'])
                    ]
                ]);
            }
                ?>
                <div class="row">
                <div class="col-sm-4 fh_item">
                <input type="hidden" id="subject-url" value="<?=Url::to(['/student/section-wise-students'])?>">
                <?php
                if($model->isNewRecord !=1){
                $user_id = \app\models\StudentInfo::find()->select(['section_id'])->where(['stu_id'=>$model->stu_id])->one();?>
                <input type="hidden" name="FeeArears[section]" value="<?php echo $user_id->section_id?>">

                <label for="">Section</label>
                <input type="text" class="form-control" value="<?php echo $user_id->section->title; ?>" style="width:578px" readonly>
               <?php }else{
                echo $form->field($model, 'section')->widget(DepDrop::classname(), [
                    'options' => ['id'=>'section-id','style'=>'width:578px'],
                    'pluginOptions'=>[
                        'depends'=>[
                            'group-id','class-id'
                        ],
                        'loadingText' => 'Loading Sections ...',
                        'prompt' => 'Select section',
                        'url' => Url::to(['/site/get-section'])
                    ]
                ]);
            }
                ?>
            </div>
        </div>
    <?php
    if($model->isNewRecord !=1){?>
    <label for="">Student</label>
        <input type="text" class="form-control" value="<?php echo yii::$app->common->getName($model->student->user_id); ?>" readonly>
   <?php     
   echo $form->field($model, 'stu_id')->hiddenInput(['value'=>$model->stu_id])->label(false);
    }else{
    $model->stu_id=(count($model->stu_id)>0)?yii::$app->common->getName($model->student->user_id):'';
    echo $form->field($model, 'stu_id')->widget(Select2::classname(), [
            'options' => ['placeholder' => 'Select Student','class'=>'classwisestudent','id'=>'subject-inner'],
            'pluginOptions' => [
                'allowClear' => true
            ],
        ]);
    }
    ?>
    <?php 
    if($model->isNewRecord != 1){?>
    <label for="">Fee Head</label>
        <input type="text" class="form-control" value="<?php echo $model->head->title; ?>" readonly>
    <?php
        echo $form->field($model, 'fee_head_id')->hiddenInput(['value'=>$model->fee_head_id])->label(false);
    }else{
        $head = ArrayHelper::map(\app\models\FeeHead::find()->where(['branch_id'=>yii::$app->common->getBranch(),'extra_head'=>0])->all(), 'id', 'title');
    echo $form->field($model, 'fee_head_id')->widget(Select2::classname(), [
        'data' => $head,
        'options' => ['placeholder' => 'Select Head ...'],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]);
    }?>

    <?= $form->field($model, 'arears')->textInput() ?>
    <?php if($model->isNewRecord != 1){
                  echo  $form->field($model, 'from_date')->widget(DatePicker::classname(), [
                         'options' => ['value' => $model->from_date],
                         'pluginOptions' => [
                             'autoclose'=>true,
                             'format' => 'yyyy/mm/dd',
                             'todayHighlight' => true,
                             //'endDate' => '+0d',
                             //'startDate' => '-0d',
                         ]
                     ])->label('Arrears Month');
                }else{
                   echo  $form->field($model, 'from_date')->hiddenInput(['value'=>date('Y-m-d')])->label(false);
                } ?>

    <?= $form->field($model, 'status')->hiddenInput(['value'=>1])->label(false) ?>
    <?= $form->field($model, 'date')->hiddenInput(['value'=>date('Y-m-d')])->label(false) ?>
    <?= $form->field($model, 'branch_id')->hiddenInput(['value'=>yii::$app->common->getBranch()])->label(false) ?>
	<?php if (!Yii::$app->request->isAjax){ ?>
	  	<div class="form-group">
	        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
	    </div>
	<?php } ?>
    <?php ActiveForm::end(); ?>
</div>