<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\StudentAttendance */

$this->title = 'Update Student Attendance: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Student Attendances', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="student-attendance-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
