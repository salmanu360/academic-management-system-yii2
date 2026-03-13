<?php
  use yii\helpers\Html;
  use yii\helpers\Url;
  use yii\widgets\ActiveForm;
  use yii\helpers\ArrayHelper;
  use kartik\select2\Select2;
  $this->title='Parent SMS';
 $form = ActiveForm::begin(['action'=>'send-all-parents']); ?>
  
 <!--<div class="alert alert-info">Sorry! We are currently working on this module, will be modified very soon.</div>-->
 
<div class="modal-dialog modal-dialog-centered modal-lg" role="document">
<div class="modal-content">
        <div class="modal-header alert alert-info">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title" style="color: black">Send Sms To All Parents</h4>
        </div>
        <div class="modal-body">
          <p>
            <?php
            $ref_class = ArrayHelper::map(\app\models\RefClass::find()->where(['fk_branch_id'=>yii::$app->common->getBranch(),'status'=>'active'])->all(), 'class_id', 'title');
              echo $form->field($subjectModel, 'class_id')->widget(Select2::classname(), [
                'data' => $ref_class,
                'options' => ['placeholder' => 'Select Class ...','multiple'=>true,'required'=>'required'],
                'pluginOptions' => [
                'allowClear' => true,
                'maximumSelectionLength'=> 6,
                ],
                'toggleAllSettings' => [
                 'selectLabel' => false,
                ],
                ])->label('Select Class');
                ?>
            
            <label for="class">Message</label>
              <textarea class="form-control messageCount" name="smsWhole" id="smsWholeSchool" required></textarea>
              <span id="remaining">160 characters remaining</span> <span id="messages">1 message(s)</span>
          </p>
        </div>
        <div class="modal-footer">
        <button class="btn btn-success">Send</button>
        <a href="<?php echo Url::to(['/site']) ?>" class="btn btn-danger"> back</a>
        </div>
      </div>
      </div>
      <?php ActiveForm::end(); ?>