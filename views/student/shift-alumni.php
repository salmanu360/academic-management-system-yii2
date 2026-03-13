<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\widgets\Pjax;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
$this->title = 'Student Info';
$this->params['breadcrumbs'][] = $this->title;
?>
<section class="invoice">
    <?php Pjax::begin(['enablePushState' => false, 'timeout'=>false, 'id'=>'pjax-container']); ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        /*'filterModel' => $searchModel,*/
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'class' => 'yii\grid\CheckboxColumn',
                // you may configure additional properties here
                
            ],
            [
                'label'=>'Registeration No.',
                'value'     => function($data){
                    return $data->user->username;
                }
            ],
             [
            'label'=>'Full Name',
            'value'=>function($data){
                return Yii::$app->common->getName($data->user->id);
            }
            ],

            [
                'label'=>'Father Name',
                'value'     => function($data){
                    $father_record = $data->getStudentParentsInfos()->limit(1)->one();
                    if(count($father_record) >0){
                        return Yii::$app->common->getParentName($father_record->stu_id);
                    }else{
                        return 'N/A';
                    }

                }
            ],
            [
                'label'=>'Admission Date',
                'filter'=>'',
                'value'     => function($data){
                    return date('d M,Y',strtotime($data->registration_date));
                }
            ],
        ],
    ]); ?>

    <?php Pjax::end() ?>
     <!--upcoming class selection-->
    <?php $form = ActiveForm::begin(); ?>
    <div class="row">
    <div class="col-md-12">
        <div class="alert alert-warning" id="success-alert">
            <button type="button" class="close" data-dismiss="alert">x</button>
            <strong>Note! </strong>Please Select Student to shift to Alumni
        </div>
    </div>
    </div>
        <div class="row">
        

        <div class="col-md-3">
        	<br />
            <?=Html::submitButton('Shift Students',['class'=>'btn btn-success btn-promote-std','id'=>'btn-promote-std','data-url'=>Url::to(['student/save-alumni'])])?>
        </div>
        </div>

    <?php $form = ActiveForm::end(); ?>

</section>