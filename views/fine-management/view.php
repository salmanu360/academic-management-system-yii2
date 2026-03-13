<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\FineClassWise */
?>
<div class="fine-class-wise-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            //'id', 
            [ 
                'attribute'=>'class_id',  
                'value'=>function($data){ 
                    $class = \app\models\RefClass::find()->select(['title'])->where(['fk_branch_id'=>Yii::$app->common->getBranch(),'class_id'=>$data->class_id,'status'=>'active'])->one();
                      
                    if($class){
                        return ucfirst($class->title);
                    }else{
                        return 'N/A';
                    } 
                }
            ],
            'amount', 
            [ 
                'attribute'=>'status',  
                'value'=>function($data){ 
                    return ucfirst($data->status);
                }
            ],
             [ 
                'attribute'=>'created_at',
                'label'=>'Created Date',
                'value'=>function($data){ 
                    return date('Y-m-d H:i A',strtotime($data->created_at));
                }
            ], 
        ],
    ]) ?>

</div>
