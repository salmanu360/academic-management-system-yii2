<?php 
use yii\helpers\Html;
use yii\widgets\ActiveForm;
$this->title='Profile';
 if (Yii::$app->session->hasFlash('success')): ?>
           <div class="alert alert-success">
              <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
              <?= Yii::$app->session->getFlash('success') ?>
           </div>
            <?php endif; ?>
<div class="row">
<div class="col-md-1"></div>
<div class="col-md-8">
<div class="box box-default">
        <div class="box-header with-border">
          <h3 class="box-title">Profile</h3>

          <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
            <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
          </div>
        </div>
        <!-- /.box-header -->
        <div class="box-body">
        <?php  $form = ActiveForm::begin();?>
        <?= $form->field($model, 'first_name')->textInput(['value'=>$loginUser->first_name,'readonly'=>'readonly']) ?>
        <?= $form->field($model, 'middle_name')->textInput(['value'=>$loginUser->middle_name,'readonly'=>'readonly']) ?>
        <?= $form->field($model, 'last_name')->textInput(['value'=>$loginUser->last_name,'readonly'=>'readonly']) ?>
        <!-- <?//= $form->field($model, 'password_hash')->passwordInput(['value'=>$loginUser->password_hash])->label('Change Password') ?> -->
        <?= $form->field($model, 'Image')->fileInput() ?>
    
                 <?php 
                    
                    $src=Yii::$app->request->baseUrl.'/uploads/'.$loginUser->Image;
                    echo Html::img( $src, $options = ['width'=>60,'height'=>'60'] );
               
                  ?><br><br>
         <button class="btn btn-success">Update Profile</button>
        <?php ActiveForm::end(); ?>

        </div>
        </div>
        </div>
        </div>
