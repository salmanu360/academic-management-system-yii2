<?php 
error_reporting(0);
use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
$colors=\app\models\Colors::find()->one();
 ?>
         <?php $form = ActiveForm::begin([
                'id' => 'loginform',
                'class' => 'form-vertical',
                //'layout' => 'horizontal',
                'fieldConfig' => [
                    'template' => "<div class=\"col-lg-12\">{label}\n{input}</div>\n<div class=\"col-lg-12\">{error}</div>",
                    'labelOptions' => ['class' => 'control-label'],
                ],
            ]); ?>
                 <div class="control-group normal_text" style="background-color:#<?php echo $colors->headerbackgroud; ?> "> 
                 <h3>
                 <span style="color:#<?php echo $colors->sidebartextcolor; ?>">Kryptons Education System</span>
                 </h3>
                 </div>
                <div class="control-group">
                    <div class="controls">
                        <div class="main_input_box">
         <?= $form->field($model, 'username', ['inputTemplate' => '<div class="input-group"><span class="add-on">
         <i class="fa fa-user"></i></span>{input}</div>'])->textInput(['autofocus' => true,'placeholder'=>'Username'])->label(false); ?>
                        </div>
                    </div>
                </div>
                <div class="control-group">
                    <div class="controls">
                        <div class="main_input_box">
         <?= $form->field($model, 'password', ['inputTemplate' => '<div class="input-group"><span class="add-on">
         <i class="fa fa-fw fa-lock"></i></span>{input}</div>'])->passwordInput(['placeholder'=>'Password'])->label(false); ?>
                        </div>
                    </div>
                </div>
                <div class="form-actions">
                    <span class="pull-left"><a href="#" class="flip-link btn btn-inverse" id="to-recover">Lost password?</a></span>
                    <span class="pull-right"><input type="submit" class="btn btn-success" value="Login" /></span>
                </div>
           <?php ActiveForm::end(); ?>
          
            <form id="recoverform" action="#" class="form-vertical">
                <p class="normal_text">Enter your e-mail address below and we will send you instructions how to recover a password.</p>
                
                    <div class="controls">
                        <div class="main_input_box">
                            <span class="add-on"><i class="fa fa-envelope"></i></span><input type="text" placeholder="E-mail address" />
                        </div>
                    </div>
               
                <div class="form-actions">
                    <span class="pull-left"><a href="#" class="flip-link btn btn-inverse" id="to-login">&laquo; Back to login</a></span>
                    <span class="pull-right"><input type="submit" class="btn btn-info" value="Recover" /></span>
                </div>
            </form>