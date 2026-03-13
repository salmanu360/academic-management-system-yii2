<?php

use yii\helpers\Html; 
use yii\grid\GridView; 
use yii\helpers\ArrayHelper;
/* @var $this yii\web\View */ 
/* @var $dataProvider yii\data\ActiveDataProvider */ 

$this->title = 'Home Tasks'; 
$this->params['breadcrumbs'][] = $this->title;
?> 
<?php if (Yii::$app->session->hasFlash('success')): ?>
            <div class="alert alert-success">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <?= Yii::$app->session->getFlash('success') ?>
            </div>
        <?php endif; ?>
<div class="box box-default">
<div class="box-body">
<div class="home-task-index">  
<div class="table-responsive">
    <?= GridView::widget([ 
        'dataProvider' => $dataProvider, 
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'], 
    [   
    'attribute' => 'class_id',
    'value'=>function($data){
        return $data->class->title;
    }
    ],
    [   
    'attribute' => 'group_id',
    'value'=>function($data){
        return $data->group->title;
    }
    ],
    [   
    'attribute' => 'subject_id',
    'value'=>function($data){
        return $data->subject->title;
    }
    ],
    [   
    'attribute' => 'teacher_id',
    'value'=>function($data){
        return Yii::$app->common->getName($data->teacher->user_id);
    }
    ],
             'class_work',
             'home_task',
             'remarks',
             'date',
            
        ], 
    ]); ?> 
</div> 
</div> 
</div> 
</div> 