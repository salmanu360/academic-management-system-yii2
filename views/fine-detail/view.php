<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\FineDetail */
?>
<div class="fine-detail-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'fk_branch_id',
            'fk_fine_typ_id',
            'remarks',
            'created_date',
            'updated_date',
            'amount',
            'is_active',
            'fk_stu_id',
            'payment_received',
        ],
    ]) ?>

</div>
