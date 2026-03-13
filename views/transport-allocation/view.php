<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\TransportAllocation */
?>
<div class="transport-allocation-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'fk_stop_id',
            'zone_id',
            'route_id',
            'stu_id',
            'status',
            'allotment_date',
            'created_date',
            'branch_id',
        ],
    ]) ?>

</div>
