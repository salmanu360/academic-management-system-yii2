<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\StudentOutside */

$this->title = 'Update Acadmy Student: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Acadmy Student', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="student-outside-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
