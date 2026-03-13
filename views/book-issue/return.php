<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\models\StudentInfo;

/* @var $this yii\web\View */
/* @var $searchModel app\models\search\BookIssueSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Book Issues List';
$this->params['breadcrumbs'][] = $this->title;
?>
<?php if (Yii::$app->session->hasFlash('success')): ?>
          <div class="alert alert-success">
              <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
              <?= Yii::$app->session->getFlash('success') ?>
          </div>
            <?php endif; ?>
<div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header with-border">
              <h3 class="box-title">Book Issue List</h3>
              <div class="box-tools">

               
              </div>
            </div>
            <div class="box-body table-responsive">


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

             [
             'attribute'=>'user_type',
             'value'=>function($data){
                if($data->user_type == 1){
                    return 'Student';
                }else{
                    return 'Employee';
                }
             }
             ],
             [
             'attribute'=>'user_id',
             'value'=>function($data){
                return Yii::$app->common->getName($data->user_id);
             }
             ],

             [
             'attribute'=>'book_id',
             'value'=>function($data){
                if($data){
                    return $data->book->title;
                }else{
                    return 'N/A';
                }
             }
             ],
             [
             'attribute'=>'class_id',
             'value'=>function($data){
                if($data->user_type == 1){
                    $getStudentsClass=StudentInfo::find()->where(['user_id'=>$data->user_id])->one();
                    return $getStudentsClass->class->title;

                }else{
                    return 'N/A';
                }
             }
             ],
             'issue_date',
             'due_date',
            // 'return_date',
             [
             'attribute'=>'fine',
             'value'=>function($data){
                if(!empty($data->fine) > 0){
                    return $data->fine;

                }else{
                    return 'N/A';
                }
             }
             ],

             [
             'attribute'=>'return_date',
             'value'=>function($data){
                if($data->return_date){
                    return $data->return_date;
                }else{
                    return 'Not Return Yet';
                }
             }
             ],
             //'remarks:ntext',
             'status',
            // 'fk_branch_id',

            //['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
</div>
</div>
</div>

