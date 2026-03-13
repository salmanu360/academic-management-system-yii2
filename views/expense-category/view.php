<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\ExpenseCategory */
?>
<div class="expense-category-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'title',
            //'fk_branch_id',
            'status',
        ],
    ]) ?>

</div>
