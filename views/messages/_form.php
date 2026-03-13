<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\User;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $model app\models\Messages */
/* @var $form yii\widgets\ActiveForm */
?>

<!-- <div class="messages-form">

    <?php //$form = ActiveForm::begin(); ?>

    <?//= $form->field($model, 'user_id')->textInput() ?>

    <?//= $form->field($model, 'message')->textarea(['rows' => 6]) ?>

    <?//= $form->field($model, 'reply')->textarea(['rows' => 6]) ?>

    <?//= $form->field($model, 'send_date')->textInput() ?>

    // $form->field($model, 'reply_date')->textInput() ?>

    <?//= $form->field($model, 'fk_branch_id')->textInput() ?>

    <div class="form-group">
        <?//= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php // ActiveForm::end(); ?>

</div> -->
<?php 
$basePath = Yii::$app->request->baseUrl.'/messages/create';

Pjax::begin([
        'enablePushState' => false,
        'id'=>'pjax-container-student-search'
    ]); ?>
    <?php $form = ActiveForm::begin([
    'action'=>$basePath
    ]); ?>

<div class="row">
<div class="col-md-12">
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Compose New Message</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <div class="form-group">
                <!-- <input class="form-control" placeholder="To:"> -->
                <?php 
                $EmplQuery = User::find()
                        ->select(['employee_info.emp_id',"user.id,concat(user.first_name, ' ' ,  user.last_name) as name"])
                        ->innerJoin('employee_info','employee_info.user_id = user.id')
                        ->where(['employee_info.fk_branch_id'=>yii::$app->common->getBranch()])->asArray()->all();
                    $stuArray = ArrayHelper::map($EmplQuery,'id','name');
                    echo $form->field($model, 'user_id')->widget(Select2::classname(), [
                    'data'=>$stuArray,
                    'options' => ['placeholder' => 'Select A User'],
                    'pluginOptions' => [
                    'allowClear' => true
                    ],
                    ])->label(false);?>
              </div>
              <div class="form-group">
                <!-- <input class="form-control" placeholder="Subject:"> -->
                <?= $form->field($model, 'subject')->textInput(['placeholder'=>'Subject'])->label(false); ?>

              </div>
              <div class="form-group">
                    
                      <?= $form->field($model, 'message')->textarea(['rows' => 6])->label(false); ?>
                    
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
        </div>
        </div>
        

    <?= $form->field($model, 'send_date')->hiddenInput(['value'=>date('Y-m-d')])->label(false) ?>

    <?= $form->field($model, 'fk_branch_id')->hiddenInput(['value'=>yii::$app->common->getBranch()])->label(false) ?>
    <?php ActiveForm::end(); ?>
    <?php Pjax::end() ?>
