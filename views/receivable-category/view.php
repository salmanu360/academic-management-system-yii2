<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\ReceivableCategory */
?>
<div class="receivable-category-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'title',
            'created_date',
        ],
    ]) ?>

</div>
