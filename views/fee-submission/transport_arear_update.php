<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Fee Submissions';
?>
<div class="fee-submission-index">

    <h3>Update tranport arears</h3>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            [

                'label'=>'Student',
                'value'=>function($data){
            $studentInfo = Yii::$app->common->getStudent($data->stu_id);
            return Yii::$app->common->getName($studentInfo->user_id);
               }
            ],
            // 'fee_head_id',
            // 'head_recv_amount',
            'transport_arrears',
            //'hostel_amount',
            //'transport_arrears',
            //'hostel_arrears',
            //'absent_fine',
            //'sibling_discount',
            //'from_date',
            //'to_date',
            //'year_month_interval',
            //'recv_date',
            //'fee_status',
            //'branch_id',

            [
            'header'=>'Actions',
            'class' => 'yii\grid\ActionColumn',
            'template' => "{update}",
            'buttons' => [
            'update' => function ($url, $model, $key)
            {
                   
              // $headAmount= '<i value="'.Url::to(['update','id'=>$key]).'" class="fa fa-money modalButton" style="color:red;cursor:pointer" title="Update Head Fee"></i>';
            return Html::a('<span class="glyphicon glyphicon-pencil toltip" data-placement="bottom" width="20"  title="Update Transport Arears"></span>',Url::to(['transport-edit-form','id'=>$key]));
                 
             //return \Yii\helpers\Html::button(['value'=>Url::to(['Transport-update-arears','id'=>$key]),'class'=>'btn btn-success','id'=>'modalButton']);

            },
        ],
        ],
        ],
    ]); ?>
</div>