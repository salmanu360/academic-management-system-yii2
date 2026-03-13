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

                <?= Html::a('Create Book Issue', ['create'], ['class' => 'btn btn-primary']) ?>&nbsp;
                <!-- <?//= Html::a('<i class="fa fa-download"></i>Generate PDF', ['generate-pdf-empb'], 
                      //  ['class' => 'btn btn-success','id'=>'generate-employee-pdf']) ?> -->
        
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
                    if(count($getStudentsClass)>0){
                    return $getStudentsClass->class->title;
                }else{
                    return 'N/A';
                }

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
             
             [
             'attribute'=>'status',
             'format'=>'raw',
             'value'=>function($data){
                if($data->status == 'return'){
                    return '<span style="color:green">'.$data->status.'</span>';
                }else if($data->status == 'renewal'){
                    return '<span style="color:red">'.$data->status.'</span>';
                    
                }else{
                    return '<span style="color:#f39c12">'.$data->status.'</span>';

                }
             }
             ],
            // 'fk_branch_id',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
</div>
</div>
</div>

