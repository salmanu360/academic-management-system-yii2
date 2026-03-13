<?php
use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use app\models\User;
use app\models\EmployeeInfo;
$this->title = 'Leave Applications List';
$this->params['breadcrumbs'][] = $this->title;
?>
        <?php if (Yii::$app->session->hasFlash('create')): ?>
          <div class="alert alert-seccess">
              <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
              <?= Yii::$app->session->getFlash('create') ?>
          </div>
            <?php endif; ?>
         <?php if (Yii::$app->session->hasFlash('Warning')): ?>
          <div class="alert alert-danger">
              <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
              <?= Yii::$app->session->getFlash('Warning') ?>
          </div>
            <?php endif; ?>

            <?php if (Yii::$app->session->hasFlash('approved')): ?>
          <div class="alert alert-success">
              <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
              <?= Yii::$app->session->getFlash('approved') ?>
          </div>
            <?php endif; ?>
<div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header with-border">
              <h3 class="box-title">Leave Applications List</h3>
              <div class="box-tools">
                <?= Html::a('Create Leave Application', ['create'], ['class' => 'btn btn-primary']) ?>&nbsp;
                <?= Html::a('<i class="fa fa-download"></i>Generate PDF', ['generate-pdf-empb'], 
                ['class' => 'btn btn-success','id'=>'generate-employee-pdf']) ?>
        
              </div>
            </div>
            <div class="box-body table-responsive">
           <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute'=>'login_id',
                'label'=>'Name',
                'value'=>function($data){
                    $user=User::find()->where(['id'=>$data->login_id])->one();
                    if(count($user)>0){
                        return $user->first_name .' '. $user->last_name;
                    }else{
                        return 'N/A';
                    }
                }
           ],
           [
                'label'=>'Department',
                'value'=>function($data){
                    $user=User::find()->where(['id'=>$data->login_id])->one();
                    $Employee=EmployeeInfo::find()->where(['user_id'=>$user->id])->one();
                    if(count($Employee)>0){
                        return $Employee->departmentType->Title;
                    }else{
                        return 'N/A';
                    }
                }
           ],
           [
                'label'=>'Designation',
                'value'=>function($data){
                    $user=User::find()->where(['id'=>$data->login_id])->one();
                    $Employee=EmployeeInfo::find()->where(['user_id'=>$user->id])->one();
                    if(count($Employee)>0){
                        return $Employee->designation->Title;
                    }else{
                        return 'N/A';
                    }
                }
           ],
            [
                'attribute'=>'leave_category',
                'value'=>function($data){
                    if(count($data)>0){
                        return $data->leaveCategory->leave_category;
                    }else{
                        return 'N/A';
                    }
                }
           ],
            'from_date',
            'to_date',
            [
            'attribute'=>'approval_status',
            'value'=>function($data){
                if($data->approval_status == 0){return "Pending";}else if($data->approval_status == 1){return "Not Approved";}else{return "Approved";}
            }

             ],
             [
                'header'=>'Actions',
                'class' => 'yii\grid\ActionColumn',
                'template' => "{view} {update}{approved}{notapproved}",
                'buttons' => [
                    'view' => function ($url, $model, $key)
                    {
                        return Html::a('<span class="glyphicon glyphicon-eye-open toltip" data-placement="bottom" width="20"  title="View Student"></span>', ['leave-application/view','id'=>$key]);
                    },
                    'update' => function ($url, $model, $key)
                    {
                        return Html::a('<span class="glyphicon glyphicon-pencil toltip" data-placement="bottom" width="20"  title="Update Student"></span>',Url::to(['leave-application/update','id'=>$key]));
                    },
                    'approved' => function ($url, $model, $key)
                    {
                      return Html::a('<span class="glyphicon glyphicon-ok" data-placement="bottom" width="20" title="Approved"></span>', ['leave-application/approved', 'id' => $key], ['class' => 'profile-link','onclick'=>"return confirm_delete()"]);
                    
                    },

                    'notapproved' => function ($url, $model, $key)
                    {
                       return Html::a('<span class="glyphicon glyphicon-remove" data-placement="bottom" width="20" title="Not Approved"></span>', ['leave-application/not-approved', 'id' => $key], ['class' => 'profile-link','onclick'=>"return confirm_delete()"]);
                    
                    },
                ],
            ],
        ],
    ]); ?>
</div>
</div>
<?php
$script = <<< JS
 //here
function confirm_delete() {
  return confirm('are you sure?');
}
JS;
$this->registerJs($script);
?>