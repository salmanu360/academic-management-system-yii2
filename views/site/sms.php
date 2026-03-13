 <?php
 use yii\helpers\Html;
use yii\widgets\ActiveForm;
  $form = ActiveForm::begin(); ?>
<div class="row">
<div class="col-md-2"> 
<?php  echo $this->render('/messages/sidebar.php');?>
</div>
    <div class="col-md-8">
      <?php if (Yii::$app->session->hasFlash('success')): ?>
           <div class="alert alert-success">
              <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
              <?= Yii::$app->session->getFlash('success') ?>
           </div>
            <?php endif;?>
      <div class="box box-primary">
        <div class="box-body">
      <label>Phone:</label>
    <input type="number" name="number" placeholder="923001234567" class="form-control" /><br />
    <label>Message</label>
    <textarea cols="10" rows="10" name="msg" class="form-control messageCount"></textarea>
     <span id="remaining">160 characters remaining</span> <span id="messages">1 message(s)</span>
    </div>
    <div class="box-footer">
          <div class="pull-right">
            <button type="submit" class="btn btn-primary"><i class="fa fa-envelope-o"></i> Send</button>
          </div>
        </div>
  </div>
  </div>
  </div>
 <?php ActiveForm::end(); ?>