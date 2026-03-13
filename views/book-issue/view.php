<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\BookIssue */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Book Issues', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="book-issue-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
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
                if($data->user_type == 1){
                    $getStudents=StudentInfo::find()->where(['stu_id'=>$data->user_id])->one();
                    return Yii::$app->common->getName($getStudents->user_id);
                }else{
                    return Yii::$app->common->getName($data->user_id);
                }
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
                    $getStudentsClass=StudentInfo::find()->where(['stu_id'=>$data->user_id])->one();
                    return $getStudentsClass->class->title;

                }else{
                    return 'N/A';
                }
             }
             ],

            
            'issue_date',
            'due_date',
            'return_date',
            'fine',
            'remarks:ntext',
            'status',
        ],
    ]) ?>

</div>
