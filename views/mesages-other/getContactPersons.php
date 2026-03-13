  <?php
  use yii\helpers\Html;
  use yii\widgets\ActiveForm;
  use kartik\select2\Select2;
  use yii\helpers\ArrayHelper;

  ?>
  <?php if (Yii::$app->session->hasFlash('success')): ?>
           <div class="alert alert-success">
              <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
              <?= Yii::$app->session->getFlash('success') ?>
           </div>
            <?php endif; ?>
  <div class="row">
    <div class="col-md-2"><?php echo $this->render('/messages/sidebar.php');?></div>
    <div class="col-md-8">
      <div class="box box-primary">
        <div class="box-header with-border">
          <h3 class="box-title">Compose New Message</h3>
        </div>
        <!-- /.box-header -->
        <?php $form = ActiveForm::begin(); ?>
        <div class="box-body">
          <div class="form-group">
            <div class="form-group field-messages-user_id">
              <?php $MesagesOther = ArrayHelper::map(\app\models\MesagesOther::find()->where(['fk_branch_id'=>yii::$app->common->getBranch()])->all(), 'id', 'name');
              echo $form->field($model, 'person_id')->widget(Select2::classname(), [
                'data' => $MesagesOther,
                'options' => ['placeholder' => 'Select Name ...','multiple'=>true],
                'pluginOptions' => [
                'allowClear' => true
                ],
                ])->label('To');
                ?>
              </div>              
            </div>
            <div class="form-group">
              <div class="form-group field-messages-message required">
               <?= $form->field($model, 'message')->textArea(['rows' => 6,'class'=>'form-control messageCount'])->label('Message') ?>
               <span id="remaining">160 characters remaining</span> <span id="messages">1 message(s)</span>
               <div class="help-block"></div>
             </div>                    
           </div>    
         </div>
         <!-- /.box-body -->
         <div class="box-footer">
          <div class="pull-right">
            <button type="submit" class="btn btn-primary"><i class="fa fa-envelope-o"></i> Send</button>
          </div>

        </div>
        <!-- /.box-footer -->
      </div>
      <!-- /. box -->
      <?php ActiveForm::end(); ?>
    </div>
  </div>