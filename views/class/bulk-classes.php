<?php 
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;

 ?>
<div class="col-md-2"></div>
              <div class="col-md-8">
<div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Bulk Classes Creation</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            <?php $form = ActiveForm::begin(['action'=>'bulk-classes-insert']); ?>
            
                
              
              <div class="box-body">
                <div class="row">
                <div class="col-md-5">
        <label id="classLabel">Number Of Classes To Create:</label>
        <input type="text" name="rows" class="form-control" id="pasval" onkeypress='return event.charCode >= 13 && event.charCode <= 57' data-url=<?php echo Url::to(['branch/get-input'])?> />
        
               <label id="claserror" style="color:red"></label>
                
                </div>
                
                  <div class="col-md-5">
                  <?php $session = ArrayHelper::map(\app\models\RefSession::find()->where(['fk_branch_id'=>Yii::$app->common->getBranch()])->all(), 'session_id', 'title'); ?>
                   <?= $form->field($model, 'fk_session_id')->widget(Select2::classname(), [
                    'data' => $session,
                    'options' => ['placeholder' => 'Select Session ...','data-url'=>Url::to(['branch/create-class']),'class'=>'sessn'],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ]);
                ?>
                </div>
                <div class="col-md-2">
                <div style="height: 23px"></div>
                   <input type="button" id="classesCreate" class="btn btn-success" value="Create" />
                </div>
               
                <!-- <div style="height: 8px"></div>
                 <div class="form-group">
                
                <input type="button" id="classesCreate" class="btn btn-success" value="Create" />
                
                 </div> -->
                 </div>
                 <div class="row">
                   <div class="col-md-10">
                     <div class="shows"></div>
                   </div>
                 </div>
                 </div>

              <!-- /.box-body -->

              <div class="box-footer">
                <?= Html::submitButton($model->isNewRecord ? 'Save Classes' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
              </div>
              <?php ActiveForm::end(); ?>
          </div>
          </div>
            </div>