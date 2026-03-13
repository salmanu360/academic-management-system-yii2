<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\noticeboard */
?>
<div class="noticeboard-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            //'fk_branch_id',
            'title',
            'notice',
            'date',
        ],
    ]) ?>

</div>
