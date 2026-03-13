<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\search\LeaveApplicationSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="leave-application-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'login_id') ?>

    <?= $form->field($model, 'leave_category') ?>

    <?= $form->field($model, 'from_date') ?>

    <?= $form->field($model, 'to_date') ?>

    <?php // echo $form->field($model, 'reason') ?>

    <?php // echo $form->field($model, 'fk_branch_id') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
