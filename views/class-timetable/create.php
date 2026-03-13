<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\ClassTimetable */

$this->title = 'Create Class Timetable';
$this->params['breadcrumbs'][] = ['label' => 'Class Timetables', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="class-timetable-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
