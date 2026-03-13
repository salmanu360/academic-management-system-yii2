<?php
use yii\helpers\Html;
use yii\helpers\Url;
use kartik\date\DatePicker;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;
use kartik\grid\GridView;
Pjax::begin();
?>
<div class="panel panel-default panel-body">
  <div class="row">
  <div class="col-md-12">
    <strong style="color:red">Search Month Ledger</strong>
    <a class="btn btn-danger pull-right" href="<?php echo Url::to(['accounts']) ?>">Back</a>
    
  </div></div>
  <?php $form = ActiveForm::begin(['method'=>'get']); ?>
<div class="row">
  <div class="col-md-3">
   <?php 
   echo '<label>Start Date:</label>';   
   echo DatePicker::widget([
    'name' => 'startdate', 
    'value' => date('Y-m'),
    'options' => ['placeholder' => ' ','id'=>'startdateFee'],
    'pluginOptions' => [
      'autoclose' => true,
      'startView'=>'year',
      'minViewMode'=>'months',
      'format' => 'yyyy-mm',
                       // 'startDate' => '-1m',
    ]
  ]);?>
</div>
<div class="col-md-3">
 <?php echo '<label>End Date:</label>'; 
 echo DatePicker::widget([
  'name' => 'enddate', 
                    //'value' => date('01-m-Y'), 'id'=>'enddateFee' when want in ajax
  'options' => ['placeholder' => ' ','data-url'=>Url::to(['reports/date-fee'])],
  'pluginOptions' => [
    'autoclose' => true,
    'startView'=>'year',
    'minViewMode'=>'months',
    'format' => 'yyyy-mm',

  ]
]);?>
</div>
<div class="col-md-2">
  <button class="btn btn-success" style="margin-top: 23px">Show Ledger</button>
</div>
</div>
<?php   ActiveForm::end(); ?>
<br />
<div class="row">
  <!-- <div class="col-sm-12" id="showDateWiseFee"></div> -->
  <?php if(Yii::$app->request->get()){
    $total = 0;
   ?>
  <?= GridView::widget([ 
    'dataProvider' => $dataProvider,
      'responsive'=>true,
      'hover'=>true,
      'showFooter' => true,
      // 'exportContainer' => ['class' => 'btn-group-sm'],
      //'export' => false,
         
      'panel' => [
      'type'=>'info',
                 ],
      /*'toolbar'=> [
       '{export}',
    ],*/
    /*'export'=>[
        'fontAwesome'=>true,
        'icon' => '',
        'label' => 'Print View',
        'target' => GridView::TARGET_SELF,
        'showConfirmAlert' => false,
    ],*/
    /*'panel'=>[
        'type'=>'danger',
        'heading'=>'',
    ],*/
   // 'layout'=>"{sorter}\n{pager}\n{summary}\n{items}",
    'columns' => [
        ['class' => 'yii\grid\SerialColumn'],
        
        [
          'attribute'=>'stu_id',
          'label'=>'Student',
          'footer' => 'Ledger of '.date('M Y',strtotime($start)).' - '.date('M Y',strtotime($end)),
          'value'=>function($data){
            $student_info=\app\models\StudentInfo::find()->where(['stu_id'=>$data->stu_id])->one();
            return Yii::$app->common->getName($student_info->user_id);
          }
        ],
        [
          'attribute'=>'stu_id',
          'label'=>'Father',
          'value'=>function($data){
            $student_info=\app\models\StudentInfo::find()->where(['stu_id'=>$data->stu_id])->one();
            return Yii::$app->common->getParentName($student_info->stu_id);
          }
        ],
        [
          'attribute'=>'stu_id',
          'label'=>'Class',
          'value'=>function($data){
            $student_info=\app\models\StudentInfo::find()->where(['stu_id'=>$data->stu_id])->one();
            return Yii::$app->common->getCGSName($student_info->class_id,$student_info->group_id,$student_info->section_id);
          }
        ],
        
        [
          'attribute'=>'fee_head_id',
          'label'=>'Fee Title',
          'value'=>function($data){
            $getHead=\app\models\FeeHead::find()->where(['branch_id'=>yii::$app->common->getBranch(),'id'=>$data->fee_head_id])->one();
            return strtoupper($getHead->title);
          }
        ],

        [
          'attribute'=>'head_recv_amount',
          'label'=>'Fee Rcv.',
          'footer' => \app\models\FeeSubmission::getTotal($dataProvider->models, 'head_recv_amount'),
          'value'=>function($data){
            return 'Rs. '.$data->head_recv_amount;
          }
        ],
        [
          'label'=>'Arrears',
          'value'=>function($model, $key, $index, $widget) use (&$total){
            $fee_arrears=\app\models\FeeArears::find()->where(['stu_id'=>$model->stu_id,'status'=>1,'fee_head_id'=>$model->fee_head_id])->one();
            if(!empty($fee_arrears)){
              $total +=$fee_arrears->arears;
              $widget->footer = 'Rs. '.$total;
              return 'Rs. '.$fee_arrears->arears;
            }else{
              return 'Rs. 0';
            }
          }
        ], 
        [
          'attribute'=>'transport_amount',
          'label'=>'Transport Fee',
           'footer' => \app\models\FeeSubmission::getTotal($dataProvider->models, 'transport_amount'),
          'value'=>function($data){
            return 'Rs. '.$data->transport_amount;
          }
        ],
        [
          'attribute'=>'transport_arrears',
          'footer' => \app\models\FeeSubmission::getTotal($dataProvider->models, 'transport_arrears'),
          'value'=>function($data){
            $transport_arrears=\app\models\FeeSubmission::find()->where(['stu_id'=>$data->stu_id,'fee_status'=>1,'fee_head_id'=>$data->fee_head_id])->one();
            if(!empty($transport_arrears)){
              return 'Rs. '.$transport_arrears->transport_arrears;
            }else{
              return 'Rs. 0';
            }
          }
        ],
        [
          'attribute'=>'recv_date',
          'value'=>function($data){
            return date('d M Y',strtotime($data->recv_date));
          }
        ], 

        //['class' => 'yii\grid\ActionColumn'],
    ],
]);
} 
?>
<?php Pjax::end(); ?>
</div>
</div>