<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

$this->title = 'Student Outsides';
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
        <h3 class="box-title"> Outside Students</h3>
        <a class="btn btn-info pull-right" style="margin-left:10px" href="<?=Url::to(['student-outside/download']) ?>" title="Download Acadmy Students List" data-pjax="0"><i class="glyphicon glyphicon-download-alt"></i></a>
        <?= Html::a('Add Outside Student', ['create'], ['class' => 'btn btn-success pull-right']) ?>

    </div>
     <div class="box-body">
<div class="student-outside-index">
    <p>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'name',
            [
            'attribute'=>'class_id',
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
            'attribute'=>'section_id',
            'value'=>function($data){
                return $data->section->title;
                }
            ],
             'parent_name',
             'regesteration_date',
             'organization',
             'contact_no',
             'parent_contact',
             [
                        'header'=>'Actions',
                        'class' => 'yii\grid\ActionColumn',
                        'contentOptions' =>['style' => 'width:100px'],
                        'template' => "{view}&nbsp;{update}&nbsp;{delete}&nbsp;{SendSms}",
                        'buttons' => [
                            'SendSms' => function ($url, $model, $key)
                            {
                                return Html::a('<i class="fa fa-envelope-o data-toggle="modal" data-target="#myModal" title="Send SMS"></i>','javascript:void(0);',['data-toggle'=>'modal','data-target'=>'#myModal','data-stu_id'=>$key,'id'=>'stu']);
                            }, 
                        ],
                    ],
        ],
    ]); ?>
</div>
</div>
</div>
<div class="container">
  <div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Send SMS</h4>
          <input type="hidden" name="getstudent_id" id="stu_id" value=""/>
        </div>
        <div class="modal-body">
        <label for="">Student</label>
        <input type="radio" name="smsSend" value="student">
        <label for="">Parent</label> 
        <input type="radio" name="smsSend" value="parent" checked="checked">
        <textarea class="form-control" name="text" id="textareasms"></textarea>
          <div id="sucmsg" style="color: green;"></div>
        </div>
        <div class="modal-footer">
        <button type="button" class="btn green-btn" id="sendSms" data-url="<?php echo Url::to(['student/outsider-text'])?>">Send</button>
          <button type="button" class="btn green-btn" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>
</div>
