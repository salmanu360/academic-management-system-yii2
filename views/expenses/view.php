<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Expenses */
?>
<div class="expenses-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            //'fk_branch_id',
           // 'expense_category_id',
            [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'expense_category_id',
        'label'=>'Expense Category',
        'value'=>function($data){
            return $data->expenseCategory->title;
            }
            ],
            'title',
            'description:ntext',
            [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'payment_mehtod',
        'value'=>function($data){
            return $data->paymentMehtod->title;
           }
           ],
            'date',
            'amount',
        ],
    ]) ?>

</div>
