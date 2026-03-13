<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\search\AssigmentsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Assigments';
?>
 <?php if (Yii::$app->session->hasFlash('success')): ?>
           <div class="alert alert-success">
              <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
              <?= Yii::$app->session->getFlash('success') ?>
           </div>
            <?php endif; ?>
<div class="box box-warning">
    <div class="box-header with-border">
        <h3 class="box-title"><i class="fa fa-file"></i>&nbsp;Assigments List</h3>
        <span class="pull-right"><?= Html::a('Create Assigments', ['create'], ['class' => 'btn btn-success']) ?></span>

    </div>
     <div class="box-body">
    <p>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'title',
             [
             'attribute'=>'class_id',
              'value' => function($data){
               return ucfirst($data->class->title);
              },
             ],
             [
            'attribute'=>'group_id',
            'value'=>function($data){
                if(count($data->group_id) >0){
                return $data->group->title;
                }else{
                    return 'N/A';
                }
            }
            ],
            [
            'attribute'=>'subject_id',
            'value'=>function($data){
                if(count($data->subject_id) >0){
                return $data->subject->title;
                }else{
                    return 'N/A';
                }
            }
            ],
             [
            'attribute'=>'assign_by',
            'value'=>function($data){
             return Yii::$app->common->getName($data->assign_by); 
            }
            ],
             [
            'attribute'=>'date_of_submission',
            'value'=>function($data){
                return date('d M-Y',strtotime($data->date_of_submission));    
            }
            ],

            // 'image',
            [
            'attribute'=>'status',
            'format'=>'raw',
            'value'=>function($data){
                if($data->status == 'open'){
                    return '<span class="label label-info">'.$data->status.'</span>';
                }else if($data->status == 'submitted'){
                    return '<span class="label label-success">'.$data->status.'</span>';
                }else if($data->status == 'closed'){
                    return '<span class="label label-danger">'.$data->status.'</span>';
                }else if($data->status == 'onhold'){
                    return '<span class="label label-danger">'.$data->status.'</span>';
                }else if($data->status == 'reassign'){
                    return '<span class="label label-warning">'.$data->status.'</span>';
                }
            }
            ],

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
</div>
