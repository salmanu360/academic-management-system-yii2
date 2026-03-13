<?php
use yii\helpers\Html; 
use yii\grid\GridView; 
use yii\helpers\ArrayHelper;
use yii\widgets\Pjax;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use kartik\select2\Select2;
/* @var $this yii\web\View */ 
/* @var $dataProvider yii\data\ActiveDataProvider */ 
$this->title = 'Home Tasks'; 
$this->params['breadcrumbs'][] = $this->title; 
$classArray = ArrayHelper::map(\app\models\RefClass::find()->where(['fk_branch_id'=>Yii::$app->common->getBranch(),'status'=>'active'])->all(),'class_id','title');
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
    <p> 
        <?php if(Yii::$app->user->identity->fk_role_id == 1){  ?>
        <?= Html::a('Create Home Task', ['admin-task'], ['class' => 'btn btn-success']) ?>
        <button data-toggle="modal" data-target="#sendTaskSms" class="btn btn-info" title="Sent Task in SMS to Parents"><i class="fa fa-fw fa-envelope"></i> </button> 

        <?php }else{?>
        <?= Html::a('Create Home Task', ['task-form'], ['class' => 'btn btn-success']) ?> 

        <?php } ?>
    </p> 
<div class="table-responsive">

    <?= GridView::widget([ 
        'dataProvider' => $dataProvider, 
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'], 
    [   
    'attribute' => 'class_id',
    'filter'=>Html::activeDropDownList ($searchModel,'class_id',$classArray,['prompt' => 'Select '.Yii::t('app','Class'),'class' => 'form-control']),
    'value'=>function($data){
        return $data->class->title;
    }
    ],
    [   
    'attribute' => 'group_id',
    //'filter'=>ArrayHelper::map(\app\models\RefClass::find()->asArray()->all(), 'class_id', 'title'),
    'value'=>function($data){
        if($data->group_id){
            return $data->group->title;
        }else{
            return 'N/A';
        }
    }
    ],
    [   
    'attribute' => 'subject_id',
    //'filter'=>ArrayHelper::map(\app\models\RefClass::find()->asArray()->all(), 'class_id', 'title'),
    'value'=>function($data){
        return $data->subject->title;
    }
    ],
    [   
    'attribute' => 'teacher_id',
    //'filter'=>ArrayHelper::map(\app\models\RefClass::find()->asArray()->all(), 'class_id', 'title'),
    'value'=>function($data){
        return Yii::$app->common->getName($data->teacher->user_id);
    }
    ],
             'class_work',
             'home_task',
             'remarks',
             'date',
            // 'fk_branch_id',
            // 'user_id',
            [
                'class' => 'yii\grid\ActionColumn',
                'header'=>'Actions',
                'template' => "{update} {delete} ",
                'buttons' => [
                'update' => function ($url,$model) {
                    return Html::a(
                        '<span class="glyphicon glyphicon-pencil"></span>', 
                        $url);
                },
                'delete' => function ($url,$model) {
                   return Html::a('<span class="glyphicon glyphicon-trash"></span>', $url, [
                            'title' => Yii::t('app', 'Delete'),
                            'data-confirm' => Yii::t('yii', 'Are you sure you want to delete?'),
                            'data-method' => 'post', 'data-pjax' => '0',
                ]);
                },
            ],
       // }
      
            ], 
        ], 
    ]); ?> 
</div> 
</div> 
</div> 
</div> 

<div class="modal fade" id="sendTaskSms" role="dialog">
    <div class="modal-dialog">
    <?php Pjax::begin(['id' => 'pjax-container']) ?>
    <?php $form = ActiveForm::begin(['action'=>'send-task']); ?>
      <div class="modal-content">
        <div class="modal-header alert alert-info">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title" style="color: black">Send Home Task To Parents</h4>
        </div>
        <div class="modal-body">
          <div id="departmentMessage">
          <?php
          $class_array = ArrayHelper::map(\app\models\RefClass::find()->where(['fk_branch_id'=>Yii::$app->common->getBranch(),'status'=>'active'])->all(), 'class_id', 'title'); 
                echo $form->field($searchModel, 'class_id')->widget(Select2::classname(), [
                    'data' => $class_array,
                    'options' => ['placeholder' => 'Select Class ...','data-url'=>url::to(['general/get-class-details']),'id'=>'getdataclass'],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                    ]); ?>
                    <?php 
                    echo $form->field($searchModel, 'group_id')->widget(Select2::classname(), [
                        'options' => ['placeholder' => 'Select Group','data-url'=>Url::to(['general/get-subjects']),'id'=>'classdatagroup'],
                        'pluginOptions' => [
                            'allowClear' => true
                        ],
                        ]); ?>
              </div>
        </div>
        <div class="modal-footer">
        <button class="btn btn-success">Send</button>
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
      <?php ActiveForm::end(); ?>
      <?php Pjax::end() ?>  
    </div>
  </div>