<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\search\VisitorsSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="visitors-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'name') ?>

    <?= $form->field($model, 'email') ?>

    <?= $form->field($model, 'phone') ?>

    <?= $form->field($model, 'cnic') ?>

    <?php // echo $form->field($model, 'company') ?>

    <?php // echo $form->field($model, 'to_meet') ?>

    <?php // echo $form->field($model, 'representing') ?>

    <?php // echo $form->field($model, 'address') ?>

    <?php // echo $form->field($model, 'date') ?>

    <?php // echo $form->field($model, 'branch_id') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
