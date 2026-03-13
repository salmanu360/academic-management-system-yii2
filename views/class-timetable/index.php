<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;


/* @var $this yii\web\View */
/* @var $searchModel app\models\search\ClassTimetableSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Class Timetables';
$this->params['breadcrumbs'][] = $this->title;
$classArray = ArrayHelper::map(\app\models\RefClass::find()->where(['fk_branch_id'=>Yii::$app->common->getBranch(),'status'=>'active'])->all(),'class_id','title');
?>
         <?php if (Yii::$app->session->hasFlash('success')): ?>
          <div class="alert alert-success">
              <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
              
              <?= Yii::$app->session->getFlash('success') ?>
          </div>
            <?php endif; ?>
<div class="box box-warning">
    <div class="box-header with-border">
        <h3 class="box-title"><i class="fa fa-search"></i> Class Timetables</h3>
    </div>
     <div class="box-body">
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

         
           /* [
            'attribute'=>'class_id',
            'value'=>function($data){
                if($data->class_id){
                return $data->class->title;
                }else{
                    return 'N/A';
                }
            }
            ],*/

         [
        //'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'class_id',
        //'vAlign'=>'middle',
        'filter'=>Html::activeDropDownList ($searchModel,'class_id',$classArray,['prompt' => 'Select '.Yii::t('app','Class'),'class' => 'form-control']),
        'value' => function($model,$key){
            return ucfirst($model->class->title);
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
            'day',
            'start_date',
             'end_date',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
</div>
