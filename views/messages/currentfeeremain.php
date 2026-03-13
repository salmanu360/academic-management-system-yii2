<?php use yii\helpers\Url; 
use yii\helpers\Html;
use yii\widgets\ActiveForm;?>
<?php if (Yii::$app->session->hasFlash('success')): ?>
   <div class="alert alert-success">
      <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
      <?= Yii::$app->session->getFlash('success') ?>
   </div>
<?php endif; ?>
<div class="row">
<div class="col-sm-2">
<?php echo $this->render('sidebar.php');?>
 </div>
 <div class="col-md-10">   
<div class='box box-default'>
<div class='box-body'>
	<div class="alert-warning" style="padding: 15px">
		Send SMS to all parents who not given <?= date('M')?> Month Fee
	</div>
	<?php  $form = ActiveForm::begin(); ?>
    <textarea cols="10" rows="10" name="msg" class="form-control messageCount">Dear Parents, You are hereby informed to pay your child's monthly fee.Ignore this message if paid. Thank you</textarea>
     <span id="remaining">160 characters remaining</span> <span id="messages">1 message(s)</span>
		<div class="box-footer">
          <div class="pull-right">
            <button type="submit" class="btn btn-primary"><i class="fa fa-envelope-o"></i> Send</button>
          </div>
        </div>
        <?php ActiveForm::end(); ?>
</div>
</div>
 </div>
 </div>