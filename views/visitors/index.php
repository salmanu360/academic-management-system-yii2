<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\search\VisitorsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Visitors';
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
        <h3 class="box-title"> Visitors</h3>
        <span class="pull-right">
        <?= Html::a('Add Visitors', ['create'], ['class' => 'btn btn-success']) ?>
            
        </span>
    </div>
     <div class="box-body">
<div class="visitors-index">
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'name',
            'email:email',
            'phone',
            'cnic',
            // 'company',
             [
             'attribute'=>'to_meet',
             'value'=>function($data){
                return Yii::$app->common->getName($data->to_meet);
             }
             ],
             'representing',
            // 'address:ntext',
             'date',
            // 'branch_id',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
</div>
</div>