<?php

use yii\helpers\Html; 
use yii\helpers\Url; 
use yii\widgets\ActiveForm; 
$this->title='Admission';
$id=Yii::$app->request->get('id');
if(isset($id)){
?> 
<?php echo $this->render('step_menu') ?>
<?php } ?>
<div class="panel panel-default"> 
    <div class="panel panel-body">

   <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]) ?>
<?php if (Yii::$app->session->hasFlash('success')): ?>
    <div class="alert alert-success alert-dismissable">
         <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
         <?= Yii::$app->session->getFlash('success') ?>
    </div>
<?php endif; ?>
    <div class="row">
        <div class="col-md-6">
            <?php
                  $lastAmitedStudent=\app\models\User::find()->where(['fk_branch_id'=>Yii::$app->common->getBranch(),'fk_role_id'=>3])->orderBy(['id'=>SORT_DESC])->limit(1)->one();
                   $where = "username LIKE 'c%'";
                  $lastCstudent=\app\models\User::find()->where(['fk_branch_id'=>Yii::$app->common->getBranch(),'fk_role_id'=>3])->andWhere($where)->orderBy(['id'=>SORT_DESC])->limit(1)->one();
                  $whereS = "username LIKE 's%'";
                  $lastSstudent=\app\models\User::find()->where(['fk_branch_id'=>Yii::$app->common->getBranch(),'fk_role_id'=>3])->andWhere($whereS)->orderBy(['id'=>SORT_DESC])->limit(1)->one();
                  
                    if(Yii::$app->common->getBranchSettings()->student_reg_type == 'auto'){?>
                         <?=$form->field($userModel, 'username')->textInput(['readonly'=>true,'maxlength' => true,'value'=>Yii::$app->common->getBranchDetail()->name.'-'.date("Y").'-'.($branch_std_counter+1),'id'=>'registeration','class'=>'form-control input form-control','id'=>'registeration','data-url'=>Url::to('validate-usrname')])->label(Yii::t('app','Register Number').'<span style="color:#a94442;"> *</span>')?>
                    <?php }else{?>
                         <span style="color: red">Last Register No. is:  (<?php echo (!empty($lastAmitedStudent->username)?$lastAmitedStudent->username:'N/A') ?>)
                       </span>
                       <span style="color:red">
                           <?php echo (!empty($lastCstudent->username)?' | '.$lastCstudent->username:'') ?>
                       </span>

                       <span style="color:red">
                           <?php echo (!empty($lastSstudent->username)?' | '.$lastSstudent->username:'') ?>
                       </span>
                        <?=$form->field($model, 'username')->textInput(['maxlength' => true,'id'=>'registeration','class'=>'form-control input form-control','id'=>'registeration','data-url'=>Url::to('validate-usrname')])->label(Yii::t('app','Register Number').'<span style="color:#a94442;"> * </span>')?>
                        <?php } ?>
            <?= $form->field($model, 'first_name')->textInput(['maxlength' => true]) ?>
            <?= $form->field($model, 'last_name')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>
            <?php
            if(!$model->isNewRecord){
                        echo $form->field($model, 'Image')->fileInput();
                        $src=Yii::$app->request->baseUrl.'/uploads/'.$model->Image;
                        echo Html::img( $src, $options = ['width'=>60,'height'=>'60','alt'=>'No Image Uploaded']);
                        //echo $form->field($model, 'Image')->hiddenInput()->label(false);

                    }else{
                        echo $form->field($model, 'Image')->fileInput();
                    } ?>
        </div>
    </div>
    <?= $form->field($model, 'fk_branch_id')->hiddenInput(['value'=>Yii::$app->common->getBranch()])->label(false) ?>

    <?= $form->field($model, 'status')->hiddenInput(['value'=>'active'])->label(false) ?>

    <?= $form->field($model, 'fk_role_id')->hiddenInput(['value'=>3])->label(false) ?>

    <?= $form->field($model, 'created_at')->hiddenInput(['value'=>date('Y:m:d H:i:s')])->label(false) ?>
    <?= $form->field($model, 'updated_at')->hiddenInput(['value'=>date('Y:m:d H:i:s')])->label(false) ?>
    
    <div class="form-group"> 
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?> 
    </div> 

    <?php ActiveForm::end(); ?> 

</div> 
</div> 