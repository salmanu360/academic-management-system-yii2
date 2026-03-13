<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\ExamGrading */
?>
<div class="exam-grading-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'grade',
            'marks_obtain_from',
            'marks_obtain_to',
            'grade_name',
        ],
    ]) ?>

</div>
