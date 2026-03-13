<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'SMS';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="box">
<div class="box-body">

    <h1><?= Html::encode($this->title) ?></h1>

   <!--  <p>
        <?= Html::a('Create Sms', ['create'], ['class' => 'btn btn-success']) ?>
    </p> -->
    <?php if (Yii::$app->session->hasFlash('success')): ?>
    <div class="alert alert-success alert-dismissable">
         <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
         <?= Yii::$app->session->getFlash('success') ?>
    </div>
<?php endif; ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'price',
            // 'description:ntext',
            'total_sms',
            'validity',
            'purchased_request_date',
            'purchased_date',
            'status',
            //'date_created',

            // ['class' => 'yii\grid\ActionColumn'],

            [
                'header'=>'Actions',
                'class' => 'yii\grid\ActionColumn',
                'contentOptions' =>['style' => 'width:140px'],
                'template' => " {buy} ",
                'buttons' => [
                    'buy' => function ($url, $model, $key)
                    {
                        return Html::a(
                            '<span class="btn btn-info btn-xs toltip">BUY</span>',
                            ['sms/buy', 'id' => $key],
                            [
                                'data-pjax' => "0",
                                'onclick' => 'return confirm("Are you sure you want to buy this package?");'
                            ]
                        );
                        
                    },
                    'update' => function ($url, $model, $key)
                    {
                       return Html::a('<span class="glyphicon glyphicon-pencil btn btn-primary btn-xs toltip" data-placement="bottom" width="20"  title="Edit">', ['employee/update', 'id' => $key]);
                    },
                   
                       'Certificate' => function ($url, $model, $key)
                    { 
                       return Html::a('<span class="glyphicon glyphicon-file btn btn-success btn-xs toltip" data-placement="bottom" width="20"  title="Best Teacher Certificate">', ['employee/best-certificate', 'id' => $key]);
                    },
                    
                     /*'pdf'=>function($url, $model, $key){
                        return Html::a('<span class="glyphicon glyphicon-file" data-placement="bottom" width="20"  title="Generate pdf"></span>', ['employee/create-mpdf','id'=>$key]);
                    },*/
                ],
            ],

        ],
    ]); ?>
</div>
</div>
