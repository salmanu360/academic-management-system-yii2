<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\FeeSubmission */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="fee-submission-form">
    <?php $form = ActiveForm::begin(); ?>
    <?= $form->field($model, 'transport_arrears')->textInput() ?>
    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>