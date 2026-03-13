<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\FeeSubmission */
?>
<div class="fee-submission-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'stu_id',
            'fee_head_id',
            'head_recv_amount',
            'transport_amount',
            'hostel_amount',
            'transport_arrears',
            'hostel_arrears',
            'absent_fine',
            'sibling_discount',
            'from_date',
            'to_date',
            'year_month_interval',
            'recv_date',
            'fee_status',
            'branch_id',
        ],
    ]) ?>

</div>
