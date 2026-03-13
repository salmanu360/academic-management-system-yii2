<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Receivable */
?>
<div class="receivable-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            [
        'attribute'=>'receivable_category',
        'value'=>function($data){
                return $data->receivablecategory->title;
        }
    ],
    
    [
        'attribute'=>'class_id',
        'value'=>function($data){
            if(count($data->class_id)>0){
                return $data->class->title;
            }else{
                return 'N/A';
            }
        }
    ],
            'name',
            'contact',
            'amount',
            'created_date',
        ],
    ]) ?>

</div>
