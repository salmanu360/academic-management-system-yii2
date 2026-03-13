<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\ClassTimetable */

$this->title = 'Update Class Timetable: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Class Timetables', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="class-timetable-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
