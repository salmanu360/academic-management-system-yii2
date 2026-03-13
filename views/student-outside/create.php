<?php

use yii\helpers\Html;
$this->params['breadcrumbs'][] = ['label' => 'Student Outsides', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="student-outside-create">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
