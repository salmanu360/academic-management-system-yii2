<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\FeeHead */
?>
<div class="fee-head-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'title',
        [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'extra_head',
        'value'=>function($data){
            if($data->extra_head == 0){
                return 'No';
            }else{
                return 'Yes';
            }
        }
    ],
       [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'one_time_payment',
        'value'=>function($data){
            if($data->one_time_payment == 0){
                return 'No';
            }else{
                return 'Yes';
            }
        }
    ],
      [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'promotion_head',
        'value'=>function($data){
            if($data->promotion_head == 0){
                return 'No';
            }else{
                return 'Yes';
            }
        }
    ],
            'date',
        ],
    ]) ?>

</div>
