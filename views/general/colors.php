<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
/* @var $this yii\web\View */
/* @var $model app\models\Domain */
/* @var $form yii\widgets\ActiveForm */
$this->registerJsFile("@web/js/jscolor.js");
?>
<div class="panel panel-default">
<div class="panel-body">
<div class="domain-form">
    <?php $form = ActiveForm::begin(); ?>
    <?= $form->field($model, 'headerbackgroud')->textInput(['maxlength' => true,'class'=>'jscolor form-control']) ?>
    <?= $form->field($model, 'siderbarbackgroud')->textInput(['maxlength' => true,'class'=>'jscolor form-control']) ?>
    <?= $form->field($model, 'sidebartextcolor')->textInput(['maxlength' => true,'class'=>'jscolor form-control']) ?>
    <?= $form->field($model, 'user_id')->hiddenInput(['value' => Yii::$app->user->id])->label(false)?>
    <?= $form->field($model, 'branch_id')->hiddenInput(['value' => Yii::$app->common->getBranch()])->label(false)?>
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>
</div>
</div>
