<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;

use app\models\EmployeeInfo;
//$dateArray = ArrayHelper::map(\app\models\EmployeeAttendance::find()->where(['date(date)'=>date('Y-m-d')])->all(),'id','date');
/* @var $this yii\web\View */
/* @var $searchModel app\models\search\EmployeeAttendanceSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Employee Attendances';
$this->params['breadcrumbs'][] = $this->title;
?>
<?php if (Yii::$app->session->hasFlash('success')): ?>
          <div class="alert alert-success">
              <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
              <?= Yii::$app->session->getFlash('success') ?>
          </div>
            <?php endif; ?>
<div class="box box-warning">
    <div class="box-header with-border">
        <h3 class="box-title"><i class="fa fa-search"></i> Employee Attendance List (<?= Date('d M,Y')?>)</h3>
        <?= Html::a('Create Employee Attendance', ['attendance'], ['class' => 'btn btn-success pull-right']) ?>

    </div>
     <div class="box-body">
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            //'fk_empl_id',
            [
            'label'=>'Full Name',
            'value'=>function($data){
             $employeeInfo=EmployeeInfo::find()->where(['emp_id'=>$data->fk_empl_id])->one();
             return Yii::$app->common->getName($employeeInfo->user_id);
            }

            ],
            'date',
            'time',
        /*[
        'attribute'=>'date',
        'filter'=>Html::activeDropDownList ($searchModel,'date',$dateArray,['prompt' => 'Select '.Yii::t('app','Date'),'class' => 'form-control']),
        'value' => function($model,$key){
            return $model->date;
        },
         ],*/
            'leave_type',
            'remarks',

            //['class' => 'yii\grid\ActionColumn'],
            [
                        'header'=>'Actions',
                        'class' => 'yii\grid\ActionColumn',
                        'contentOptions' =>['style' => 'width:100px'],
                        'template' => "{update}",
                        'buttons' => [
                            
                            
                            'update' => function ($url, $model, $key)
                            {
                                return Html::a('<span class="glyphicon glyphicon-pencil toltip" data-placement="bottom" width="20"  title="Edit Attendance"></span>',Url::to(['employee/update-attendance','id'=>$key],['class'=>'btn btn-primary btn-xs']));
                            }, 
        ],
        ],
        ],
    ]); ?>
</div>
</div>
