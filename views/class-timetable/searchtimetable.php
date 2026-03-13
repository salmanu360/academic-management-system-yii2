<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use kartik\select2\Select2;
?>
<?php if (Yii::$app->session->hasFlash('success')): ?>
          <div class="alert alert-success">
              <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
              
              <?= Yii::$app->session->getFlash('success') ?>
          </div>
            <?php endif; ?>
<?php
 $form = ActiveForm::begin(); ?>
    <div class="box box-warning">
    <div class="box-header with-border">
        <h3 class="box-title"><i class="fa fa-search"></i> Select Criteria to display Timetable</h3>
    </div>
    <div class="box-body">
    <div class="row">
        <div class="col-md-6">
             <?=$form->field($model, 'checktimetableshow')->radioList([1 => 'Subject Wise', 0 => 'Class Wise'], ['itemOptions' => ['class' =>'radio-inline','id'=>'checktimetable']])->label(false)?>

        </div>
    </div>
    <div class="row">

        <div class="col-md-12">
        
            <div class="row">
                
                <div class="col-md-3">   
            <?php 
            $settings = Yii::$app->common->getBranchSettings();
            $class_array = ArrayHelper::map(\app\models\RefClass::find()->where(['fk_branch_id'=>Yii::$app->common->getBranch(),'status'=>'active'])->all(), 'class_id', 'title'); 
             echo $form->field($model, 'class_id')->widget(Select2::classname(), [
                            'data' => $class_array,
                            'options' => ['placeholder' => 'Select Class ...','data-url'=>url::to(['general/get-class-details']),'id'=>'getdataclass'],
                            'pluginOptions' => [
                                'allowClear' => true
                            ],
                        ]);
                        ?>
      
                </div>
                <div class="col-md-3"> 
               <?php 
      echo $form->field($model, 'group_id')->widget(Select2::classname(), [
        'options' => ['placeholder' => 'Select Group','data-url'=>Url::to(['general/get-subjects']),'id'=>'classdatagroup'],
        'pluginOptions' => [
        'allowClear' => true
        ],
        ]);
       ?>     
        </div>
        <div id="showSubject">
         <div class="col-md-3">     
            <?php 
      echo $form->field($model, 'subject_id')->widget(Select2::classname(), [
        'options' => ['placeholder' => 'Select Subject','data-url'=>Url::to(['class-timetable/get-subjects-timetable']),'id'=>'getSubjectsdata'],
        'pluginOptions' => [
        'allowClear' => true
        ],
        ]);
       ?>     
        </div>
        <div class="col-md-2">
        <div style="height: 24px"></div>
        <a href="javascript:Void(0)" id="searchtimetable" style="background: #727272;border-color: #525252" data-url="<?php echo Url::to(['class-timetable/search-timetable-show']) ?>" class="btn btn-primary pull-right"><i class="fa fa-search"></i> Search</a>
   
        </div>
          </div>
        <div id="showSubjectSearch" style="display: none">
        <div class="col-md-2">
        <div style="height: 24px"></div>
            <a href="javascript:Void(0)" id="searchtimetableClasswise" style="background: #727272;border-color: #525252" data-url="<?php echo Url::to(['class-timetable/search-timetable-class-show']) ?>" class="btn btn-primary pull-right"><i class="fa fa-search"></i> Search</a>
        </div>
        </div>
     
                </div>
                </div>
        <?php ActiveForm::end(); ?>
        
       
    </div>
    </div>

    </div>
    
<div class="renderSearchView"></div>


<!-- <div class="box-footer">
                            <button id="searchtimetable" type="submit" style="background: #727272;border-color: #525252" data-url="<?php //echo Url::to(['class-timetable/search-timetable-show']) ?>" class="btn btn-primary pull-right"><i class="fa fa-search"></i> Search</button>
                        </div> -->