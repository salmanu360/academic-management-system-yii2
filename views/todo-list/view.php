<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\TodoList */
?>
<div class="todo-list-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'title',
            'start_date',
            'end_date',
            'branch_id',
        ],
    ]) ?>

</div>
