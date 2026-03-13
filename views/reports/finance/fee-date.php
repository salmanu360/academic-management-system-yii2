<?php
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
?>
<?php Pjax::begin(); ?>
<?= GridView::widget([
    'dataProvider' => $dataProvider,
   // 'layout'=>"{sorter}\n{pager}\n{summary}\n{items}",
    'columns' => [
        ['class' => 'yii\grid\SerialColumn'],
        [
          'attribute'=>'stu_id',
          'label'=>'Student',
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
          'value'=>function($data){
            return 'Rs. '.$data->head_recv_amount;
          }
        ],
        [
          'label'=>'Arrears',
          'value'=>function($data){
            $fee_arrears=\app\models\FeeArears::find()->where(['stu_id'=>$data->stu_id,'status'=>1,'fee_head_id'=>$data->fee_head_id])->one();
            if(!empty($fee_arrears)){
              return 'Rs. '.$fee_arrears->arears;
            }else{
              return 'Rs. 0';
            }
          }
        ], 
        [
          'attribute'=>'transport_amount',
          'label'=>'Transport Fee',
          'value'=>function($data){
            return 'Rs. '.$data->transport_amount;
          }
        ],
        [
          'attribute'=>'transport_arrears',
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
?>
<?php Pjax::end(); ?>