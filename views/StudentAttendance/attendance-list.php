<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\search\StudentAttendanceSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Student Attendances';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="student-attendance-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Student Attendance', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'fk_stu_id',
            'date',
            'leave_type',
            'remarks',
            // 'fk_branch_id',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
