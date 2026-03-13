<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\LeaveCategory */
?>
<div class="leave-category-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'leave_category',
            'status',
        ],
    ]) ?>

</div>
