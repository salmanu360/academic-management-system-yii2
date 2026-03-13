<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\MessageControl */
?>
<div class="message-control-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'message_id',
            'message:ntext',
            'created_at',
        ],
    ]) ?>

</div>
