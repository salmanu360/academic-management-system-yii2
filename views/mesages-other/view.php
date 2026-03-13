<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\MesagesOther */
?>
<div class="mesages-other-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
          
            'name',
            'designation',
            'organization',
            'date',
            'address',
        ],
    ]) ?>

</div>
