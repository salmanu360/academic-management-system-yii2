 <?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
$form = ActiveForm::begin(); ?>
  <div class="row">
  	<div class="col-md-8"><div class="alert alert-info">
  <span style="color: black">
  <strong>Note!</strong> Please clean and briefly write your query. we will contact you with in a hour.
  </span>
</div></div>
  </div>
<div class="row">
    <div class="col-md-8">
      <?php if (Yii::$app->session->hasFlash('success')): ?>
           <div class="alert alert-success">
              <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
              <?= Yii::$app->session->getFlash('success') ?>
           </div>
            <?php endif;?>
      <div class="box box-primary">
        <div class="box-body">
       <?= $form->field($model, 'subject')->textInput(['maxlength' => true]) ?>
    <br />
    <?= $form->field($model, 'message')->textarea(array('rows'=>10,'cols'=>10)); ?>
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