<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\AddlibraryCategory */
?>
<div class="addlibrary-category-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'category_name',
            'section_code',
            'fk_branch_id',
            'status',
        ],
    ]) ?>

</div>
